#!/usr/bin/python

import os.path
import re
import subprocess
import sys

### CONFIGURE THESE PATHS ###
binaryPath = "/home/opensim/opensim/opensim-current/bin"
pidPath = "/tmp/OpenSim.pid"
### END OF CONFIG ###

if os.path.exists(pidPath):
  print >> sys.stderr, "ERROR: OpenSim PID file %s still present.  Assuming OpenSim has been started already." % pidPath
  sys.exit(1)

# If PID isn't set then we'll check the screen list.  
# However, this is a much less perfect mechanism since OpenSimulator may have been started outside screen
screenList = ""

try:
  screenList = subprocess.check_output("screen -list", shell=True)
except:
  None

if re.match("\s+\d+\.OpenSim", screenList):
  print >> sys.stderr, "ERROR: Screen session for OpenSim already started."
  sys.exit(1)
