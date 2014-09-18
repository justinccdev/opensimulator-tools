#!/usr/bin/python

import argparse
import json
import os
import os.path
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
    
def produceGraph(sets, select, statType, action, show, save, outPath):        
    stats = corpus.getStats(sets, select)
    
    if len(stats) <= 0:
        print "No stats matching %s" % (select)
        return
    
    # Used to fetch data that will be the same for all stats
    oneStat = stats[stats.keys()[0]]
    
    clf()
    plt.title(select)
    plt.ylabel(oneStat[statType]['units'])
    plt.xlabel("samples")
    
    if action == 'sum':    
        plotSumAction(stats.values(), statType)
    else:
        plotNoneAction(stats.values(), statType)
        
    plt.legend()        
       
    if save: 
        savefig(outPath)
        
    if show:
        plt.show()           

############
### MAIN ###
############
parser = argparse.ArgumentParser(formatter_class = argparse.RawTextHelpFormatter)

parser.add_argument(
    '--batch',
    help = "Path to a json file containing batch instructions for producing graphs.  If this is set then any options are ignored except for --outpath",
    default = argparse.SUPPRESS)

parser.add_argument(
    '--select', 
    help = "Select the full name of a stat to graph (e.g. \"scene.Keynote 1.RootAgents\")")

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
    '--outdir',
    help = "Directory to output graphs if the --batch option is used",
    default = argparse.SUPPRESS)

parser.add_argument(
    'statsLogPath', 
    help = "Path to the stats log file.", 
    metavar = "stats-log-path",
    nargs='*')

opt = parser.parse_args()

corpus = OSimStatsCorpus()

for path in opt.statsLogPath:
    corpus.load(path)
    
if "batch" in opt:
    batchCommands = json.load(open(opt.batch))

    if not os.path.exists(opt.outdir):
      os.mkdir(opt.outdir)
    
    for graph in batchCommands["graphs"]:
        select = graph["select"]
        
        if "sets" in graph:
            sets = graph["sets"]
        else:
            sets = "*"
        
        if "type" in graph:
            type = graph["type"]
        else:
            type = "abs"
            
        if "action" in graph:
            action = graph["action"]
        else:
            action = "none"
            
        if "out" in graph:
            outPath = os.path.join(opt.outdir, graph["out"])
            save = True
            show = False
        else:
            outPath = None
            save = False
            show = True
            
        produceGraph(sets, select, type, action, show, save, outPath)            
                    
else:
    save = "out" in opt
    show = not save
    
    if save:
        outPath = opt.out
    else:
        outPath = None
    
    produceGraph("*", opt.select, opt.type, opt.action, show, save, outPath)   
