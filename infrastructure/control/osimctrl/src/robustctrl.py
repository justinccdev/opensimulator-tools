#!/usr/bin/python

import sys
import osimctrl.osimctrl as osc

##########################
### MAIN CONFIGURATION ###
##########################

# The path to your OpenSimulator binary directory
binaryPath = "/home/opensim/opensim/opensim-current/bin"

##############################
### OPTIONAL CONFIGURATION ###
##############################

# You can change the component name if you want to control a different executable
componentName = "Robust"

# You can change the screen name if desired - it doesn't have to be the same as the component name
screenName = componentName

# You can change the mono command if you need to specify an exact path to mono or you want to add/remove switches
monoPath = "mono --debug"

# You can change this to an exact path if required.
screenPath = "screen"

##############
### SCRIPT ###
##############
osc.main(binaryPath, screenPath, monoPath, componentName, screenName)