#!/usr/bin/python

import pprint
import re
import sys

#################
### FUNCTIONS ###
#################
def parseValue(rawValue, valueRe):
    valueMatch = valueRe.match(rawValue)
    return float(valueMatch.group(1)), valueMatch.group(2)          

############
### MAIN ###
############
if len(sys.argv) <= 1:
    print "Usage: %s <stats-log-path>"
    sys.exit(1)

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
valueRe = re.compile("([^ %/]+)(.*)")

# Structure
# statName => { 
#    'abs'   : { 'values' : [], 'units' : "" },
#    'delta' : { 'values' : [], 'units' : "" }
# }  
# delta may not be present
data = {}

with open(sys.argv[1]) as f:
    for line in f:    
        match = lineRe.match(line)
        
        if match != None:
            statFullName = match.group(2)       
            
            rawValue = match.group("abs")
            #print match.lastindex
            #print rawValue                                                            
            
            value = parseValue(rawValue, valueRe)
            
            if not statFullName in data:
                entry = { 
                    'abs' : { 'values' : [], 'units' : value[1] }
                }
                data[statFullName] = entry
                
            stat = data[statFullName]                
                            
            stat['abs']['values'].append(value[0])
            
            # Handle delta value if present
            if match.group("delta"):                
                rawValue = match.group("delta")
                value = parseValue(rawValue, valueRe)
                
                if not 'delta' in stat:
                    stat['delta'] = { 'values' : [], 'units' : value[1] }
                    
                stat['delta']['values'].append(value[0])                
                
        #else:
        #    print "Ignoring [%s]" % (line)
            
longestKey = max(data, key = len)
    
for statName, stat in sorted(data.items()):
    
    absValues = stat['abs']['values']    
    sys.stdout.write(
        "%-*s: %s to %s%s" % (len(longestKey), statName, min(absValues), max(absValues), stat['abs']['units']))    
    
    if 'delta' in stat:
        deltaValues = stat['delta']['values']
        print ", %s to %s%s" % (min(deltaValues), max(deltaValues), stat['delta']['units'])
    else:
        print
         