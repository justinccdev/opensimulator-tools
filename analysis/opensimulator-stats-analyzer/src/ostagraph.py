#!/usr/bin/python

import argparse
import matplotlib.pyplot as plt
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
    metavar = "stats-log-path")

opts = parser.parse_args()

osta = Osta()
osta.parse(opts.statsLogPath)
data = osta.data

# TODO: We will move this kind of check inside Osta shortly
(category, container, name) = opts.select.split(".")

if (category in data and container in data[category] and name in data[category][container]):
    plt.plot(data[category][container][name]['abs']['values'])
    plt.ylabel(opts.select)
    
    if 'out' in opts:
        savefig(opts.out)
    else:
        plt.show()
else:
    print "No such stat as %s" % (opts.select)  