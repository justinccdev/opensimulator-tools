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
    '--action',
    help = "Perform an action on the stat or stats.  Only current action is none or sum.  Default is none.",
    default = "none")

parser.add_argument(
    'statsLogPath', 
    help = "Path to the stats log file.", 
    metavar = "stats-log-path",
    nargs='*')

opts = parser.parse_args()

corpus = OSimStatsCorpus()

for path in opts.statsLogPath:
    corpus.parse(path)            
            
stats = corpus.getStats()
                   
longestKey = max(stats, key = len)

for stat in stats.values():                            
    absValues = stat['abs']['values']    
    sys.stdout.write(
        "%-*s: %s to %s%s" % (
            len(longestKey), stat['fullName'], min(absValues), max(absValues), stat['abs']['units']))    
    
    if 'delta' in stat:
        deltaValues = stat['delta']['values']
        print ", %s to %s%s" % (min(deltaValues), max(deltaValues), stat['delta']['units'])
    else:
        print

print "\nFrom %s samples" % (len(corpus))