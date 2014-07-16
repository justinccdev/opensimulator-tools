#!/usr/bin/python

import pprint
import re
import sys

if len(sys.argv) <= 1:
    print "Usage: %s <stats-log-path>"
    sys.exit(1)

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
lineRe = re.compile("(.* .*) - (.*) : ([\d\.-]+)(?:\D+)?([\d\.-]+)?")
data = {}

with open(sys.argv[1]) as f:
    for line in f:    
        match = lineRe.match(line)
        
        if match != None:
            statFullName = match.group(2)
            
            # If this is a single value or a percentage, then only first number group will match and
            # that's the value we want.
            # If this is a change over time stat, then the second number group will match and that's the one we want
            value = match.group(match.lastindex)
            
                # print "%s: %s" % (statFullName, value)
            
            if not statFullName in data:
                data[statFullName] = []                
                
            data[statFullName].append(float(value))
        #else:
        #    print "Ignoring [%s]" % (line)
            
longestKey = max(data, key = len)
    
for statName, values in sorted(data.items()):
    # print "%s: %s" % (stat, ", ".join(values))
    print "%-*s: %s to %s" % (len(longestKey), statName, min(values), max(values))