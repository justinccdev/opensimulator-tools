#!/usr/bin/python

import argparse
import fnmatch
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
parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

parser.add_argument(
    '--select', 
    help = "Select a subset of stats by their fullname using a glob pattern.  E.g. \"*Threads\" will only select stats ending in \"Threads\"", 
    default = argparse.SUPPRESS)

parser.add_argument(
    'statsLogPath', 
    help = "Path to the stats log file.", 
    metavar = "stats-log-path")

opts = parser.parse_args()

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
valueRe = re.compile("([^ %/]+)(.*)")

# Structure
# category : { 
#    container : { 
#        stat : {
#            'abs'   : { 'values' : [], 'units' : "" },
#            'delta' : { 'values' : [], 'units' : "" }
# }  
# delta may not be present
data = {}

with open(opts.statsLogPath) as f:
    for line in f:    
        match = lineRe.match(line)
        
        if match != None:
            statFullName = match.group(2)
            (category, container, name) = statFullName.split(".")       
            
            rawValue = match.group("abs")
            #print match.lastindex
            #print rawValue                                                            
            
            value = parseValue(rawValue, valueRe)
            
            if not category in data:
                data[category] = {}
                
            if not container in data[category]:
                data[category][container] = {}
            
            if not name in data[category][container]:
                entry = { 
                    'abs' : { 'values' : [], 'units' : value[1] },
                    'fullName' : statFullName
                }
                data[category][container][name] = entry
                
            stat = data[category][container][name]           
                            
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
          
fullNames = []
for category, containers in data.items():
    for container, stats in containers.items():
        for statName, stat in stats.items():
            fullNames.append(stat['fullName'])
                   
longestKey = max(fullNames, key = len)
    
for category, containers in sorted(data.items()):
    for container, stats in sorted(containers.items()):
        for statName, stat in sorted(stats.items()):
            if 'select' in opts and not fnmatch.fnmatch(stat['fullName'], opts.select):
                continue    
            
            absValues = stat['abs']['values']    
            sys.stdout.write(
                "%-*s: %s to %s%s" % (
                    len(longestKey), stat['fullName'], min(absValues), max(absValues), stat['abs']['units']))    
            
            if 'delta' in stat:
                deltaValues = stat['delta']['values']
                print ", %s to %s%s" % (min(deltaValues), max(deltaValues), stat['delta']['units'])
            else:
                print