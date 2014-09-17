import argparse
import os.path
import re
import signal
import string
import subprocess
import sys
import time

#########################
### UTILITY FUNCTIONS ###
#########################
def chdir(dir, verbose = False):
    os.chdir(dir)
    if verbose:
    	print "Executing chdir to %s" % dir

def execCmd(cmd, verbose = False):
    if verbose:
    	print "Executing command: %s" % sanitizeString(cmd)

    # Use Popen instead of subprocess.check_output as latter only exists in Python 2.7 onwards
    # output = subprocess.check_output(cmd, shell=True, stderr=subprocess.STDOUT)
    output = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE).communicate()[0]

    # print "For output got: %s" % output
    return output

def sanitizeString(input):
    return input.replace("\r", r"\r")

################
### OSIMCTRL ###
################
class OSimCtrl:
    Commands = {
      "start" : { "help" : "Start this component in a screen process." },
      "stop" : { "help" : "Stop this component." },
      "restart" : { "help" : "Stop and start this component." },
      "attach" : { "help" : "Attach to screen process for this component if running." },
      "status" : { "help" : "Status of this component." },
    }

    @property
    def pollingTimeMax(self):
        return self._pollingTimeMax

    @pollingTimeMax.setter
    def pollingTimeMax(self, value):
        if value < 0:
            raise "Max polling time %s invalid as one cannot set a value less than 0" % value

        self._pollingTimeMax = value

    def __init__(self, binaryPath, screenPath, switches, monoPath, componentName, screenName):
        self._binaryPath = binaryPath
        self._screenPath = screenPath
        self._switches = switches
        self._monoPath = monoPath
        self._componentName = componentName
        self._screenName = screenName
        self._pollingTimeMax = 300
        self._pollingInterval = 1
        self._pollingNotificationInterval = 5

    def execCommand(self, command, opts):
        """Execute a command.  Returns True on success, False otherwise."""
        if command == "attach":
            self.attachToComponent(opts)
        elif command == "status":
            self.getComponentStatus(opts)
        elif command == "start":
            self.startComponent(opts)
        elif command == "stop":
            self.stopComponent(opts)
        elif command == "restart":
            self.restartComponent(opts)
        else:
            print "Command %s not recognized" % command

    def attachToComponent(self, opts):
        """Attach to a screen running the component.  Returns True on success, False otherwise."""
        screen = self.findScreen(opts.verbose)

        if screen == None:
            print "Did not find screen named %s for attach" % self._screenName
            return False
        else:
            print "Found screen %s" % screen
            execCmd("%s -x %s" % (self._screenPath, screen))
            return True

    def getComponentStatus(self, opts):
        """Get the status of the given component.  Returns True if active, False if inactive or problem finding."""

        # We'll check screen even if we found PID so that we can get screen information
        screen = self.findScreen(opts.verbose)

        if screen == None:
            print "Did not find screen named %s" % self._screenName
        else:
            print "Found screen %s" % screen

        if opts.verbose:
            print "OpenSimulator path: %s" % self._binaryPath

        if screen != None:
            print "Status: ### Active ###"
            return True
        else:
            print "Status: ### Inactive ###"
            return False

    def startComponent(self, opts):
        """Start the given component.  Returns True on success, False otherwise"""
        screen = self.findScreen(opts.verbose)

        if screen != None:
            print >> sys.stderr, "Screen session %s already started." % (screen)
            return False

        chdir(self._binaryPath, opts.verbose)

        cmd = "%s %s.exe %s" % (self._monoPath, self._componentName, self._switches)

        if opts.autorestart:
            cmd = "bash -c 'set carryon=true; trap \"carryon=false\" SIGUSR1; while $carryon; do %s; done'" % (cmd)

        execCmd("%s -S %s -d -m %s" % (self._screenPath, self._screenName, cmd), opts.verbose)

        screen = self.findScreen(opts.verbose)
        if screen != None:
            print "%s starting in screen instance %s" % (self._componentName, screen)
        else:
            print >> sys.stderr, "ERROR: %s did not start." % self._componentName
            return False

        if opts.attach:
            execCmd("%s -x %s" % (self._screenPath, self._screenName))

        return True

    def stopComponent(self, opts):
        """Stop the given component.  Returns True on success, False if the component was already stopped or if there was a problem stopping."""
        screen = self.findScreen(opts.verbose)

        if screen == None:
            print >> sys.stderr, "No screen session named %s to stop" % self._screenName
            return False

        print "Stopping screen instance %s" % screen

        (autoRestartPid, comm) = execCmd("ps --ppid %s -o pid=,comm=" % screen.split(".")[0], opts.verbose).split()

        # Any uncaught signal sent to mono (including SIGUSR1) will kill it
        if comm == "bash":
            os.kill(int(autoRestartPid), signal.SIGUSR1)

        execCmd("%s -S %s -p 0 -X stuff quit$(printf \r)" % (self._screenPath, screen), opts.verbose)

        timeElapsed = 0

        while timeElapsed < self._pollingTimeMax:
            time.sleep(self._pollingInterval)
            timeElapsed += self._pollingInterval

            screen = self.findScreen(opts.verbose)

            if screen == None:
                print "Screen instance %s terminated." % self._screenName
                return True

            if timeElapsed % self._pollingNotificationInterval == 0:
                print "Waited %s seconds for screen named %s to terminate" % (timeElapsed, self._screenName)

        print >> sys.stderr, "Screen %s has not terminated after %s seconds.  Please investigate." % (self._screenName, self._pollingTimeMax)
        return False

    def restartComponent(self, opts):
        """Restart the given component.  Returns True on success, False otherwise."""
        self.stopComponent(opts)
        return self.startComponent(opts)

    def findScreen(self, verbose = False):
        """Try to find the screen instance for this component.  Returns the screen pid.name on success, None otherwise."""
        screenList = ""

        try:
            screenList = self.getScreenList(verbose)
        except subprocess.CalledProcessError as cpe:
            screenList = cpe.output

        #print "screenList: %s" % screenList
        # TODO: Need to improve this for screens with ( in their name
        res = re.search("\s+(\d+\.%s)\s+\(" % self._screenName, screenList)

        if not res == None:
            return res.group(1)
        else:
            return None

    def getScreenList(self, verbose = False):
        """Get a list of available screens directly from the screen command."""
        return execCmd("%s -list" % self._screenPath, verbose)

###############################
### COMMON SCRIPT FUNCTIONS ###
###############################
def main(binaryPath, screenPath, switches, monoPath, componentName, screenName):
    checkSanity(binaryPath, componentName)

    commands = OSimCtrl.Commands
    parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

    parser.add_argument(
      'command',
      choices = commands.keys(),
      help = "\n".join(["%s - %s" % (k, v['help']) for k, v in commands.iteritems()]))

    parser.add_argument(
      '-r',
      '--autorestart',
      help = "Automatically restart component if it crashes.  With this option, it can only be stopped via the stop command, not by manually attaching to the screen and shutting down the component.",
      action = "store_true")

    parser.add_argument(
      '-a',
      '--attach',
      help = "Start and restart commmands will also attach to the started screen instance.",
      action = "store_true")

    parser.add_argument(
      '-v',
      '--verbose',
      help = "Be verbose about commands executed",
      action = "store_true")

    opts = parser.parse_args()

    osimctrl = OSimCtrl(binaryPath, screenPath, switches, monoPath, componentName, screenName)
    osimctrl.execCommand(opts.command, opts)

def checkSanity(binaryPath, componentName):
    """Check that the configured paths really exist"""

    path = "%s/%s.exe" % (binaryPath, componentName)

    if not os.path.exists(path):
        print >> sys.stderr, "config.binaryPath '%s' does not exist!  Aborting." % path
        sys.exit(1)
