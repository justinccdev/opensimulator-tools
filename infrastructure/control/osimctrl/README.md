# README #

A simple couple of scripts for starting components of OpenSimulator.  This is an
alternative to the /etc/init.d script found elsewhere in this repository.

These scripts work using screen instances.

It would be possible to make these scripts much more sophisticated (e.g.
automatic detection of whether the grid services are running before starting a
simulator, etc.).  

However, currently they are deliberately being kept simple in order to keep the
user close to what is really happening.  This is to facilitate debugging and the
ability to deal with the many possible failure conditions.  This might change in
the future.

As part of this policy, there is currently no way to stop a component via the
command line.  One has to attach to the screen and quit it via the OpenSimulator
console.

# Installation #

1. Copy src/* to a location of your choice
2. If you are using a Python earlier than 2.7m you will need to copy argparse.py
[1] into the same location.

# Configuration #

Before use, you will need to edit the binaryPath at the top of both files to
point them at your OpenSimulator installation.

You can also change the screen name if you want.  In the case of simctrl.py 
you could 
 
1. Copy the script
2. Rename it
3. Change the script name

to run more than one simulator on the same machine.

# Files #

## robustctrl.py, simctrl.py ##
These files control a robust instance and a simulator respectively.

Available commands are attach, start and status.  See --help for more details.

# References #
[1] http://argparse.googlecode.com/hg/argparse.py
