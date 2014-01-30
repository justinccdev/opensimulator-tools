#!/usr/bin/python

import os.path
import sys

### CONFIGURE THESE PATHS ###
binaryPath = "/home/opensim/opensim/opensim-current/bin"
pidPath = "/tmp/OpenSim.pid"
### END OF CONFIG ###

if not os.path.exists(pidPath):
  print >> sys.stderr, "ERROR: OpenSim PID file %s still present.  Assuming OpenSim has been started already." % pidPath
  sys.exit(1)
