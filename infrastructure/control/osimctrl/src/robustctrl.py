#!/usr/bin/python

import sys
import osimctrl.osimctrl as osc

try:
    import config
except ImportError:
    print >> sys.stderr, "Cannot find config.py.  Have you copied this from config.py.example?"
    sys.exit(1)

##############################
### OPTIONAL CONFIGURATION ###
##############################

# You can change the component name if you want to control a different executable
componentName = "Robust"

# You can change the screen name if desired - it doesn't have to be the same as the component name
screenName = componentName

# You can change the mono command if you need to specify an exact path to mono or you want to add/remove switches
monoPath = "mono --debug"

##############
### SCRIPT ###
##############
osc.main(config.binaryPath, config.screenPath, config.switches, monoPath, componentName, screenName)
