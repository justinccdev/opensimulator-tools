#!/usr/bin/python

import sys
import osimctrl.ctrl as osc

### CONFIGURE THESE PATHS ###
binaryPath = "/home/opensim/opensim/opensim-current/bin"
pidPath = "/tmp/Robust.pid"
### END OF CONFIG ###

componentName = "Robust"
screenName = componentName

### SCRIPT ###
if len(sys.argv) < 2 or not sys.argv[1] in osc.commands:
  print >> sys.stderr, "Usage: %s %s" % (sys.argv[0], "|".join(osc.commands))
  sys.exit(2)

osc.execCommand(sys.argv[1], binaryPath, pidPath, componentName, screenName)
