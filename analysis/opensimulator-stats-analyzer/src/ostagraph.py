#!/usr/bin/python

import argparse
import matplotlib.pyplot as plt
import sys
from pylab import *
from osta.osta import *

#################
### FUNCTIONS ###
#################
def plotNoneAction(stats, type):
    for stat in stats:
        plt.plot(stat[type]['values'], label=stat['container'])
        
def plotSumAction(stats, type):
    totalsStat = OSimStatsHelper.sumStats(stats)                
    plt.plot(totalsStat[type]['values'], label=totalsStat['container'])    

############
### MAIN ###
############
parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

parser.add_argument(
    '--select', 
    help = "Select the full name of a stat to graph (e.g. \"scene.Keynote 1.RootAgents\")",
    required = True)

parser.add_argument(
    '--type', 
    help = "Type of value to graph.  Either 'abs' or 'delta'.  Default is 'abs'",
    default = 'abs')

parser.add_argument(
    '--action',
    help = "Perform an action on the stat or stats.  Only current action is none or sum.  Default is none.",
    default = 'none')

parser.add_argument(
    '--out',
    help = "Path to output the graph rather the interactively display.  Filename extension determines graphics type (e.g. \"graph.jpg\")",
    default = argparse.SUPPRESS)

parser.add_argument(
    'statsLogPath', 
    help = "Path to the stats log file.", 
    metavar = "stats-log-path",
    nargs='*')

opt = parser.parse_args()

corpus = OSimStatsCorpus()

for path in opt.statsLogPath:
    corpus.parse(path)

stats = corpus.getStats(opt.select)

if len(stats) <= 0:
    print "No stats matching %s" % (opt.select)
    sys.exit(1)

# Used to fetch data that will be the same for all stats
oneStat = stats[stats.keys()[0]]

plt.title(opt.select)
plt.ylabel(oneStat[opt.type]['units'])
plt.xlabel("samples")

if opt.action == 'sum':    
    plotSumAction(stats.values(), opt.type)
else:
    plotNoneAction(stats.values(), opt.type)
    
plt.legend()        
    
if 'out' in opt:
    savefig(opt.out)
else:
    plt.show()      