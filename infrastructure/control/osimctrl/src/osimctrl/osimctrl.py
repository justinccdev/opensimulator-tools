import os.path
import re
import string
import subprocess
import sys
import time

commands = ["attach", "start", "status", "stop"]

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

def sanitizeString(input):
  return input.replace("\r", r"\r")

############
### MAIN ###
############
class osimctrl:
  @property
  def pollingTimeMax(self):
    return self._pollingTimeMax
  
  @pollingTimeMax.setter
  def pollingTimeMax(self, value):
    if value < 0:
      raise "Max polling time %s invalid as one cannot set a value less than 0" % value
    
    self._pollingTimeMax = value
  
  def __init__(self, binaryPath, screenPath, componentName, screenName):
    self._binaryPath = binaryPath
    self._screenPath = screenPath
    self._componentName = componentName
    self._screenName = screenName
    self._pollingTimeMax = 300
    self._pollingInterval = 1
    self._pollingNotificationInterval = 5
        
  def execCommand(self, command):
    if command == "attach":
      self.attachToComponent()
    elif command == "status":
      self.getComponentStatus()
    elif command == "start":
      self.startComponent()
    elif command == "stop":
      self.stopComponent()
    else:
      print "Command %s not recognized" % command        
        
  def attachToComponent(self):
    screen = findScreen(self._screenName)
    
    if screen == None:
      print "Did not find screen named %s for attach" % self._screenName
    else:
      print "Found screen %s" % screen.group(1) 
      execCmd("%s -x %s" % (self._screenPath, self._screenName))
    
  def getComponentStatus(self):
      
    # We'll check screen even if we found PID so that we can get screen information        
    screen = findScreen(self._screenName)
      
    if screen == None:
      print "Did not find screen named %s" % self._screenName
    else:
      print "Found screen %s" % screen.group(1)      
      
    print "OpenSimulator path: %s" % self._binaryPath
    
    if screen != None:
      print "Status: Active"
    else:
      print "Status: Inactive"
    
  def startComponent(self):
    screen = findScreen(self._screenName)
    
    if screen != None:
      print >> sys.stderr, "Screen session %s already started." % (screen.group(1))
      sys.exit(1)
      
    chdir(self._binaryPath)
    
    execCmd("%s -S %s -d -m mono --debug %s.exe" % (self._screenPath, self._screenName, self._componentName))
    
    screen = findScreen(self._screenName)
    if screen != None:
      print "%s starting in screen instance %s" % (self._componentName, screen.group(1))
    else:
      print >> sys.stderr, "ERROR: %s did not start." % self._componentName
      exit(1)
  
    execCmd("%s -x %s" % (self._screenPath, self._screenName))
    
  def stopComponent(self):
    screen = findScreen(self._screenName)
    
    if screen == None:
      print >> sys.stderr, "No screen session named %s to stop" % self._screenName
      sys.exit(1)
      
    execCmd("%s -S %s -p 0 -X stuff quit$(printf \r)" % (self._screenPath, self._screenName))
    
    timeElapsed = 0
    
    while timeElapsed < self._pollingTimeMax:        
      time.sleep(self._pollingInterval)
      timeElapsed += self._pollingInterval      
      
      screen = findScreen(self._screenName)
      
      if screen == None:
        print "Screen instance %s terminated." % self._screenName
        return
      
      if timeElapsed % self._pollingNotificationInterval == 0:
        print "Waited %s seconds for screen named %s to terminate" % (timeElapsed, self._screenName)
        
    print >> sys.stderr, "Screen %s has not terminated after %s seconds.  Please investigate." % (self._screenName, self._pollingTimeMax)