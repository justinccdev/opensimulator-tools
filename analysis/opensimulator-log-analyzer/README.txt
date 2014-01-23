README

A very primitive tool to do some analysis on OpenSimulator logs.

At the moment, only prints out 
  * Attempted logins.  This is the only info we are guaranteed to have at INFO logging level.
  * Process memory stats.

Should be run against OpenSim.log (standalone) or Robust.log (grid).

Example: ola.py *.log
