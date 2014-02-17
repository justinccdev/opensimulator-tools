#!/usr/bin/python

import argparse
import sys
import osimctrl.osimctrl as osc

#############################
### CONFIGURE THESE PATHS ###
#############################

# The path to your OpenSimulator binary directory
binaryPath = "/home/opensim/opensim/opensim-current/bin"

# You can change this to an exact path if required.
screenPath = "screen"

#####################
### END OF CONFIG ###
#####################

componentName = "OpenSim"
screenName = componentName

##############
### SCRIPT ###
##############
commands = osc.osimctrl.Commands
parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

parser.add_argument(
  'command', 
  choices = commands.keys(), 
  help = "\n".join(["%s - %s" % (k, v['help']) for k, v in commands.iteritems()]))

opts = parser.parse_args()

osimctrl = osc.osimctrl(binaryPath, screenPath, componentName, screenName)
osimctrl.execCommand(opts.command)