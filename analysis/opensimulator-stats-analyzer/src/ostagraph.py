#!/usr/bin/python

import argparse
import matplotlib.pyplot as plt
import sys
from pylab import *
from osta.osta import *

#################
### FUNCTIONS ###
#################
def plotNoneAction(stats):
    for stat in stats:
        plt.plot(stat['abs']['values'], label=stat['container'])
        
def plotSumAction(stats):
    totalsStat = OSimStatsHelper.sumStats(stats)                
    plt.plot(totalsStat['abs']['values'], label=totalsStat['container'])    

############
### MAIN ###
############
parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

parser.add_argument(
    '--select', 
    help = "Select the full name of a stat to graph (e.g. \"scene.Keynote 1.RootAgents\")")

parser.add_argument(
    '--action',
    help = "Perform an action on the stat or stats.  Only current action is none or sum.  Default is none.",
    default = "none")

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

# Used to fetch data that will be the same for all stats
oneStat = stats[stats.keys()[0]]

plt.title(opts.select)
plt.ylabel(oneStat['name'])
plt.xlabel("samples")

if opts.action == 'sum':    
    plotSumAction(stats.values())
else:
    plotNoneAction(stats.values())
    
plt.legend()        
    
if 'out' in opts:
    savefig(opts.out)
else:
    plt.show()      