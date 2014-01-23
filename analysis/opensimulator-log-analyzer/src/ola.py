#!/usr/bin/python

import datetime
import re
import sys 

# Usage
if len(sys.argv) == 1:
  print "Usage: %s <path>+" % sys.argv[0]
  sys.exit(-1)

# Need to exclude milliseconds since this will only appear if explicitly configured in OpenSim.exe.config, etc.
timestampReFrag = "\S+ [^\s,]+"
loginRe = re.compile("(%s).+Login request for (\w+) (\S+)" % timestampReFrag)
# diagnosticsBeginRe = re.compile("(%s).+DIAGNOSTICS")
# loginRe = re.compile("(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}).+Login request for (\w+) (\S+)")
# 2014-01-16 00:28:54,961 INFO  - OpenSim.Services.LLLoginService.LLLoginService [LLOGIN SERVICE]: Login request for Joe Danger at last using viewer Singularity 1.8.2.4929, channel Singularity, IP 192.168.1.2, Mac f6504c2415f0282a3e4bd2cbef1ddf08, Id0 cf7b76bf4f26fd0700c483692312f14b
  
filenames = sys.argv[1:]

for filename in filenames:
  loglines = file(filename).readlines();

  loglinesIter = iter(loglines)

  try:
    while True:
      logline = loglinesIter.next()
      # for logline in loglines:
      # print "logline:%s" % logline
      match = loginRe.search(logline)
      
      if match != None:
        # print "Found match for %s" % (logline)
        ts = datetime.datetime.strptime(match.group(1), "%Y-%m-%d %H:%M:%S")
        firstName = match.group(2)
        lastName = match.group(3)
        print "%s Login request %s %s" % (ts.strftime("%Y-%m-%d %H:%M:%S"), firstName, lastName)
  except StopIteration:
    pass

print "Fin"

# def matchLogin(
