#!/usr/bin/python

import argparse
import sys
import osimctrl.osimctrl as osc

### CONFIGURE THESE PATHS ###

# The path to your OpenSimulator binary directory
binaryPath = "/home/opensim/opensim/opensim-current/bin"

### END OF CONFIG ###

componentName = "Robust"
screenName = componentName

### SCRIPT ###
parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

# TODO: construct help automatically from ctrl module
parser.add_argument('command', choices = osc.commands, help = "attach - Attach to screen process for this component if running.\nstart  - Start this component in a screen process.\nstatus - Status of this component")

opts = parser.parse_args()

osc.execCommand(opts.command, binaryPath, componentName, screenName)