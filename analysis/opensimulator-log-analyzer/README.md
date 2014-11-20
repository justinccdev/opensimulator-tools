# README #

A very primitive tool to do some analysis on OpenSimulator logs.

It will likely only be correct for the very latest OpenSimulator code (and then sometimes only dev code) as
it relies on picking out changeable log lines.

At the moment, only prints out 
  * Unique hypergrid, direct and total logins
  * Process memory stats if available.

Should be run against OpenSim.log (standalone) or Robust.log (grid).

Example: ola.py *.log
