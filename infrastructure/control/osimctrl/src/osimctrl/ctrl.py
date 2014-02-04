import os.path
import re
import subprocess
import sys

commands = ["start", "status"]

### UTILITY FUNCTIONS ###
def chdir(dir):
  os.chdir(dir)
  print "Executing chdir to %s" % dir
  
"""Returns true if the pidPath exists.  False otherwise."""
def checkPid(pidPath):
  return os.path.exists(pidPath)
  
def execCmd(cmd):  
  print "Executing command: %s" % cmd
  output = subprocess.check_output(cmd, shell=True, stderr=subprocess.STDOUT)
  # print "For output got: %s" % output
  return output

def findScreen(screenName):
  screenList = ""
  
  try:
    screenList = getScreenList()
  except subprocess.CalledProcessError as cpe:
    screenList = cpe.output
    
  #print "screenList: %s" % screenList
  return re.search("\s+(\d+\.%s)" % screenName, screenList)
  
def getScreenList():
  return execCmd("screen -list")

### MAIN FUNCTIONS ###
def execCommand(command, binaryPath, pidPath, componentName, screenName):
  if command == "status":
    getComponentStatus(pidPath, componentName, screenName)
  elif command == "start":
    startComponent(binaryPath, pidPath, componentName, screenName)
  else:
    print "Command %s not recognized" % command        
      
def getComponentStatus(pidPath, componentName, screenName):
  foundPid = checkPid(pidPath)
  
  if not foundPid:
    print "Did not find PID at %s" % pidPath
  else:
    print "Found PID at %s" % pidPath
    
  # We'll check screen even if we found PID so that we can get screen information        
  screen = findScreen(screenName)
    
  if screen == None:
    print "Did not find screen named %s" % screenName
  else:
    print "Found screen %s" % screen.group(1)      
    
  if screen != None:
    if foundPid:
      print "Status: Running"
    else:
      print "Status: Running (but no PID file found)"
  else:
    if foundPid:
      print "Status: Running (but not in a screen instance)"
    else:
      print "Status: Stopped"
  
def startComponent(binaryPath, pidPath, componentName, screenName):
  if checkPid(pidPath):
    print >> sys.stderr, "ERROR: %s PID file %s still present.  Assuming %s has been started already.  If not, please delete this file and retry." % (componentName, componentName, pidPath)
    sys.exit(1)
  
  # If PID isn't set then we'll check the screen list.  
  # However, this is a much less perfect mechanism since OpenSimulator may have been started outside screen
  if findScreen(screenName):
    print >> sys.stderr, "ERROR: Screen session named %s for %s already started." % (screenName, componentName)
    sys.exit(1)
    
  chdir(binaryPath)
  
  execCmd("screen -S %s -d -m mono --debug %s.exe" % (screenName, componentName))
  
  screen = findScreen(screenName)
  if screen != None:
    print "%s starting in screen instance %s" % (componentName, screen.group(1))
  else:
    print >> sys.stderr, "ERROR: %s did not start." % componentName
    exit(1)

  execCmd("screen -x %s" % screenName)
