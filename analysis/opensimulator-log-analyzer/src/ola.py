#!/usr/bin/python

import re
import sys 

""" Taken from http://stackoverflow.com/questions/2669059/how-to-sort-alpha-numeric-set-in-python"""
def sorted_nicely(l): 
    """ Sort the given iterable in the way that humans expect.""" 
    convert = lambda text: int(text) if text.isdigit() else text 
    alphanum_key = lambda key: [ convert(c) for c in re.split('([0-9]+)', key) ] 
    return sorted(l, key = alphanum_key)

print "Hello World"

# Usage
if len(sys.argv) == 1:
  print "Usage: %s <path>+" % sys.argv[0]
  sys.exit(-1)

# TODO: Should use datetime.strptime to parse date part
loginRe = re.compile("(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2}).+Login request for (\w+) (\S+)")
# 2014-01-16 00:28:54,961 INFO  - OpenSim.Services.LLLoginService.LLLoginService [LLOGIN SERVICE]: Login request for Joe Danger at last using viewer Singularity 1.8.2.4929, channel Singularity, IP 192.168.1.2, Mac f6504c2415f0282a3e4bd2cbef1ddf08, Id0 cf7b76bf4f26fd0700c483692312f14b
  
filenames = sys.argv[1:]

for filename in filenames:
  loglines = file(filename).readlines();

  for logline in loglines:
    match = loginRe.search(logline)
    
    if match != None:
      # print "Found match for %s" % (logline)
      year = match.group(1)
      month = match.group(2)
      day = match.group(3)
      hour = match.group(4)
      minute = match.group(5)
      second = match.group(6)
      firstName = match.group(7)
      lastName = match.group(8)
      print "%s-%s-%s %s:%s:%s Login request %s %s" % (year, month, day, hour, minute, second, firstName, lastName)

print "Fin"
