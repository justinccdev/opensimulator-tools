# README #

A simple couple of scripts for starting components of OpenSimulator as a user rather
than root.  Incorporates checks to avoid starting a component instance if one
is already running.

Starts named screen instances for the components.  To shutdown the components
you need to connect to the appropriate screen instance and do this manually.

# Files #

## simctrl.py ##

This will control a simulator.  It is the only file you need if you are running OpenSimulator in standalone mode

## robustctrl.py ##

This will control a robust instance.

# Installation #

1. Copy *.py files and the osimctrl/ directory to a location of your choice
2. Edit configuration sections for your OpenSimulator paths and pid diles
