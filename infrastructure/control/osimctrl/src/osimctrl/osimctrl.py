import os.path
import re
import subprocess
import sys

commands = ["attach", "start", "status"]

#########################
### UTILITY FUNCTIONS ###
#########################
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

############
### MAIN ###
############
class osimctrl:
  def __init__(self, binaryPath, componentName, screenName):
    self.binaryPath = binaryPath
    self.componentName = componentName
    self.screenName = screenName
    
  def execCommand(self, command):
    if command == "attach":
      self.attachToComponent()
    elif command == "status":
      self.getComponentStatus()
    elif command == "start":
      self.startComponent()
    else:
      print "Command %s not recognized" % command        
        
  def attachToComponent(self):
    screen = findScreen(self.screenName)
    
    if screen == None:
      print "Did not find screen named %s for attach" % self.screenName
    else:
      print "Found screen %s" % screen.group(1) 
      execCmd("screen -x %s" % self.screenName)
    
  def getComponentStatus(self):
      
    # We'll check screen even if we found PID so that we can get screen information        
    screen = findScreen(self.screenName)
      
    if screen == None:
      print "Did not find screen named %s" % self.screenName
    else:
      print "Found screen %s" % screen.group(1)      
      
    print "OpenSimulator path: %s" % self.binaryPath
    
    if screen != None:
      print "Status: Running"
    else:
      print "Status: Stopped"
    
  def startComponent(self):
    screen = findScreen(self.screenName)
    
    if screen != None:
      print >> sys.stderr, "Screen session %s already started." % (screen.group(1))
      sys.exit(1)
      
    chdir(self.binaryPath)
    
    execCmd("screen -S %s -d -m mono --debug %s.exe" % (self.screenName, self.componentName))
    
    screen = findScreen(self.screenName)
    if screen != None:
      print "%s starting in screen instance %s" % (self.componentName, screen.group(1))
    else:
      print >> sys.stderr, "ERROR: %s did not start." % self.componentName
      exit(1)
  
    execCmd("screen -x %s" % self.screenName)
