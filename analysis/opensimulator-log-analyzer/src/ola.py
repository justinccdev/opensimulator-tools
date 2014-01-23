#!/usr/bin/python

import datetime
import re
import sys 

tsFormat = "%Y-%m-%d %H:%M:%S"

# Need to exclude milliseconds since this will only appear if explicitly configured in OpenSim.exe.config, etc.
tsRe = re.compile("^([\d-]+ [\d:]+)")
loginRe = re.compile("Login request for (\w+) (\S+)")
# 2014-01-16 00:28:54,961 INFO  - OpenSim.Services.LLLoginService.LLLoginService [LLOGIN SERVICE]: Login request for Joe Danger at last using viewer Singularity 1.8.2.4929, channel Singularity, IP 192.168.1.2, Mac f6504c2415f0282a3e4bd2cbef1ddf08, Id0 cf7b76bf4f26fd0700c483692312f14b
diagProcessMemoryRe = re.compile("Process memory.*:")

def getFormattedTs(ts):
  return ts.strftime("%Y-%m-%d %H:%M:%S")

def matchLogin(logline, ts):
  match = loginRe.search(logline)
  
  if match != None:
    # print "Found match for %s" % (logline)
    firstName = match.group(1)
    lastName = match.group(2)
    print "%s Login request %s %s" % (getFormattedTs(ts), firstName, lastName)

def matchDiag(logline, ts):
  match = diagProcessMemoryRe.search(logline)

  if match != None:
    print "%s %s" % (getFormattedTs(ts), logline),
        
"""Return timestamp matching a logline.  If there was no match, then None is returned"""
def matchTs(logline):
  match = tsRe.search(logline)

  # We'll just discard ValueError parse failures
  try:
    if match != None:
      ts = datetime.datetime.strptime(match.group(1), tsFormat)
      return ts
  except ValueError:
    pass

  return None

# Usage
if len(sys.argv) == 1:
  print "Usage: %s <path>+" % sys.argv[0]
  sys.exit(-1)
  
filenames = sys.argv[1:]

for filename in filenames:
  loglines = file(filename).readlines();

  loglinesIter = iter(loglines)

  # We must have some timestamp here in case we meet a file which manages to match an RE but with no stamp
  lastTs = datetime.datetime.min

  try:
    while True:
      logline = loglinesIter.next()
      
      ts = matchTs(logline)
      if ts != None:
        lastTs = ts

      matchLogin(logline, lastTs)
      matchDiag(logline, lastTs)
  except StopIteration:
    pass

print "Fin"
