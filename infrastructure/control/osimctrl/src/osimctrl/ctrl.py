import os.path
import re
import subprocess
import sys

commands = ["attach", "start", "status"]

### UTILITY FUNCTIONS ###
def chdir(dir):
  os.chdir(dir)
  print "Executing chdir to %s" % dir
  
def execCmd(cmd):  
  print "Executing command: %s" % cmd

  # Use Popen instead of subprocess.check_output as latter only exists in Python 2.7 onwards
  # output = subprocess.check_output(cmd, shell=True, stderr=subprocess.STDOUT)  
  output = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE).communicate()[0]
  
  # print "For output got: %s" % output
  return output

def findScreen(screenName):
  screenList = ""
  
  try:
    screenList = getScreenList()
  except subprocess.CalledProcessError as cpe:
    screenList = cpe.output
    
  #print "screenList: %s" % screenList
  # TODO: Need to improve this for screens with ( in their name
  return re.search("\s+(\d+\.%s)\s+\(" % screenName, screenList)
  
def getScreenList():
  return execCmd("screen -list")

### MAIN FUNCTIONS ###
def execCommand(command, binaryPath, componentName, screenName):
  if command == "attach":
    attachToComponent(screenName)
  elif command == "status":
    getComponentStatus(componentName, screenName)
  elif command == "start":
    startComponent(binaryPath, componentName, screenName)
  else:
    print "Command %s not recognized" % command        
      
def attachToComponent(screenName):
  screen = findScreen(screenName)
  
  if screen == None:
    print "Did not find screen named %s for attach" % screenName
  else:
    print "Found screen %s" % screen.group(1) 
    execCmd("screen -x %s" % screenName)
  
def getComponentStatus(componentName, screenName):
    
  # We'll check screen even if we found PID so that we can get screen information        
  screen = findScreen(screenName)
    
  if screen == None:
    print "Did not find screen named %s" % screenName
  else:
    print "Found screen %s" % screen.group(1)      
    
  if screen != None:
    print "Status: Running"
  else:
    print "Status: Stopped"
  
def startComponent(binaryPath, pidPath, componentName, screenName):
  screen = findScreen(screenName)
  
  if screen != None:
    print >> sys.stderr, "Screen session %s already started." % (screen.group(1))
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
