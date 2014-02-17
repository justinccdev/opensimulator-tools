import argparse
import os.path
import re
import string
import subprocess
import sys
import time

#########################
### UTILITY FUNCTIONS ###
#########################
def chdir(dir):
  os.chdir(dir)
  print "Executing chdir to %s" % dir
  
def execCmd(cmd):  
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
  
  def __init__(self, binaryPath, screenPath, monoPath, componentName, screenName):
    self._binaryPath = binaryPath
    self._screenPath = screenPath
    self._monoPath = monoPath
    self._componentName = componentName
    self._screenName = screenName
    self._pollingTimeMax = 300
    self._pollingInterval = 1
    self._pollingNotificationInterval = 5
        
  def execCommand(self, command, opts):
    if command == "attach":
      self.attachToComponent()
    elif command == "status":
      self.getComponentStatus()
    elif command == "start":
      self.startComponent(opts)
    elif command == "stop":
      self.stopComponent()
    elif command == "restart":
      self.restartComponent(opts)
    else:
      print "Command %s not recognized" % command        
        
  def attachToComponent(self):
    screen = self.findScreen()
    
    if screen == None:
      print "Did not find screen named %s for attach" % self._screenName
    else:
      print "Found screen %s" % screen.group(1) 
      execCmd("%s -x %s" % (self._screenPath, self._screenName))
    
  def getComponentStatus(self):
      
    # We'll check screen even if we found PID so that we can get screen information        
    screen = self.findScreen()
      
    if screen == None:
      print "Did not find screen named %s" % self._screenName
    else:
      print "Found screen %s" % screen.group(1)      
      
    print "OpenSimulator path: %s" % self._binaryPath
    
    if screen != None:
      print "Status: Active"
    else:
      print "Status: Inactive"
    
  def startComponent(self, opts):
    screen = self.findScreen()
    
    if screen != None:
      print >> sys.stderr, "Screen session %s already started." % (screen.group(1))
      return False
      
    chdir(self._binaryPath)
    
    execCmd("%s -S %s -d -m %s %s.exe" % (self._screenPath, self._screenName, self._monoPath, self._componentName))
    
    screen = self.findScreen()
    if screen != None:
      print "%s starting in screen instance %s" % (self._componentName, screen.group(1))
    else:
      print >> sys.stderr, "ERROR: %s did not start." % self._componentName
      return False
  
    if not opts.noattach:
      execCmd("%s -x %s" % (self._screenPath, self._screenName))
    
  def stopComponent(self):
    screen = self.findScreen()
    
    if screen == None:
      print >> sys.stderr, "No screen session named %s to stop" % self._screenName
      return False
      
    execCmd("%s -S %s -p 0 -X stuff quit$(printf \r)" % (self._screenPath, self._screenName))
    
    timeElapsed = 0
    
    while timeElapsed < self._pollingTimeMax:        
      time.sleep(self._pollingInterval)
      timeElapsed += self._pollingInterval      
      
      screen = self.findScreen()
      
      if screen == None:
        print "Screen instance %s terminated." % self._screenName
        return True
      
      if timeElapsed % self._pollingNotificationInterval == 0:
        print "Waited %s seconds for screen named %s to terminate" % (timeElapsed, self._screenName)
        
    print >> sys.stderr, "Screen %s has not terminated after %s seconds.  Please investigate." % (self._screenName, self._pollingTimeMax)
    return False
  
  def restartComponent(self, opts):
    self.stopComponent()
    self.startComponent(opts)
    
  def findScreen(self):
    screenList = ""
    
    try:
      screenList = self.getScreenList()
    except subprocess.CalledProcessError as cpe:
      screenList = cpe.output
      
    #print "screenList: %s" % screenList
    # TODO: Need to improve this for screens with ( in their name
    return re.search("\s+(\d+\.%s)\s+\(" % self._screenName, screenList)
    
  def getScreenList(self):
    return execCmd("%s -list" % self._screenPath)  
  
###############################
### COMMON SCRIPT FUNCTIONS ###
###############################
def main(binaryPath, screenPath, monoPath, componentName, screenName):
  commands = OSimCtrl.Commands
  parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)
  
  parser.add_argument(
    'command', 
    choices = commands.keys(), 
    help = "\n".join(["%s - %s" % (k, v['help']) for k, v in commands.iteritems()]))
  
  parser.add_argument(
    '-n', 
    '--noattach', 
    help = "Start and restart ommmands will not attach to the started screen instance.",
    action = "store_true") 
  
  opts = parser.parse_args()
  
  osimctrl = OSimCtrl(binaryPath, screenPath, monoPath, componentName, screenName)
  osimctrl.execCommand(opts.command, opts)  