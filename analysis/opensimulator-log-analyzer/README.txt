README

A very primitive tool to do some analysis on OpenSimulator logs.

At the moment, only prints out attempted logins.  This is the only info we are guaranteed to have at INFO logging level.

Should be run against OpenSim.log (standalone) or Robust.log (grid).

Example: ola.py *.log
