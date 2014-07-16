#!/usr/bin/python

import pprint
import re
import sys

if len(sys.argv) <= 1:
    print "Usage: %s <stats-log-path>"
    sys.exit(1)

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
valueRe = re.compile("([^ %/]+)")

data = {}

with open(sys.argv[1]) as f:
    for line in f:    
        match = lineRe.match(line)
        
        if match != None:
            statFullName = match.group(2) 

            """            
            # If this is a single value or a percentage, then only first number group will match and
            # that's the value we want.
            # If this is a change over time stat, then the second number group will match and that's the one we want
            if not match.group("delta"):
                value = match.group("abs")
            else:
                value = match.group("delta")
            """          
            
            rawValue = match.group("abs")
            #print match.lastindex
            #print rawValue
            
            """
            if not match.group("delta"):
                rawValue = match.group("abs")
            else:
                rawValue = match.group("delta")
            """
                
            valueMatch = valueRe.match(rawValue)
            value = valueMatch.group(1)            
                        
                # print "%s: %s" % (statFullName, value)                
            
            if not statFullName in data:
                entry = { 'abs' : [], 'delta' : [] }
                data[statFullName] = entry                
                
            data[statFullName]['abs'].append(float(value))
            
            # Handle delta value if present
            if match.lastindex > 3 and match.group("delta"):
                rawValue = match.group("delta")
                
                valueMatch = valueRe.match(rawValue)
                value = valueMatch.group(1)               
                    
                data[statFullName]['delta'].append(float(value))                
                
        #else:
        #    print "Ignoring [%s]" % (line)
            
longestKey = max(data, key = len)
    
for statName, values in sorted(data.items()):
    
    absValues = values['abs']    
    sys.stdout.write("%-*s: %s to %s" % (len(longestKey), statName, min(absValues), max(absValues)))
    
    deltaValues = values['delta']
    
    if len(deltaValues) > 0:
        print ", %s to %s" % (min(deltaValues), max(deltaValues))
    else:
        print
         