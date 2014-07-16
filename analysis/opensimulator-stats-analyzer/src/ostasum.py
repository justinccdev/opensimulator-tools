#!/usr/bin/python

import argparse
import fnmatch
from osta.osta import *
import pprint
import sys

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

osta = Osta()
data = osta.parse(opts.statsLogPath)

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