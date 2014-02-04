#!/usr/bin/python

import sys
import osimctrl.ctrl as osc

### CONFIGURE THESE PATHS ###
binaryPath = "/home/opensim/opensim/opensim-current/bin"
pidPath = "/tmp/OpenSim.pid"
componentName = "OpenSim"
screenName = componentName
### END OF CONFIG ###

### SCRIPT ###
if len(sys.argv) < 2 or sys.argv[1] != "start":
  print >> sys.stderr, "Usage: %s start" % sys.argv[0]
  sys.exit(2)

osc.startComponent(binaryPath, pidPath, componentName, screenName)