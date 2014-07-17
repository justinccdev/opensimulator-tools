#!/usr/bin/python

import argparse
import matplotlib.pyplot as plt
import sys
from pylab import *
from osta.osta import *

############
### MAIN ###
############
parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

parser.add_argument(
    '--select', 
    help = "Select the full name of a stat to graph (e.g. \"scene.Keynote 1.RootAgents\")")

parser.add_argument(
    '--out',
    help = "Path to output the graph rather the interactively display.  Filename extension determines graphics type (e.g. \"graph.jpg\")",
    default = argparse.SUPPRESS)

parser.add_argument(
    'statsLogPath', 
    help = "Path to the stats log file.", 
    metavar = "stats-log-path",
    nargs='*')

opts = parser.parse_args()

corpus = OSimStatsCorpus()

for path in opts.statsLogPath:
    corpus.parse(path)

stats = corpus.getStats(opts.select)

if len(stats) <= 0:
    print "No stats matching %s" % (opts.select)
    sys.exit(1)

plt.title(opts.select)
plt.ylabel(stats[0]['name'])

for stat in stats: 
    plt.plot(stat['abs']['values'])    
    plt.xlabel("samples")        
    
if 'out' in opts:
    savefig(opts.out)
else:
    plt.show()      