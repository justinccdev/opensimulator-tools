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
    for stat in stats.values():
        plt.plot(stat['abs']['values'], label=stat['container'])
        
def plotSumAction(stats):
    totals = []
    for stat in stats.values():
        absValues = stat['abs']['values']
        
        for i in range(0, len(absValues)):
            if i + 1 > len(totals):
                totals.append(absValues[i])
            else:
                totals[i] += absValues[i]
                
    plt.plot(totals, label="Total")    

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
    plotSumAction(stats)
else:
    plotNoneAction(stats)
    
plt.legend()        
    
if 'out' in opts:
    savefig(opts.out)
else:
    plt.show()      