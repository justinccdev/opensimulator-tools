#!/usr/bin/python
# vim: ts=4:sw=4:et

import os.path
import re
import sys

""" Taken from http://stackoverflow.com/questions/2669059/how-to-sort-alpha-numeric-set-in-python"""
def sorted_nicely(l):
    """ Sort the given iterable in the way that humans expect."""
    convert = lambda text: int(text) if text.isdigit() else text
    alphanum_key = lambda key: [ convert(c) for c in re.split('([0-9]+)', key) ]
    return sorted(l, key = alphanum_key)

print "Hello World"

# Usage
if len(sys.argv) == 1:
    print "Usage: %s <path>+" % sys.argv[0]
    sys.exit(-1)

functionsFound = set()
paths = sys.argv[1:]
files = []

for path in paths:
    if os.path.isdir(path):
        entries = os.listdir(path)
        for entry in entries:
            fullPath = os.path.join(path, entry)
            if os.path.isfile(fullPath):
                files.append(fullPath)
    else:
        files.append(path)

for f in files:
    lsl = file(f).readlines()
    scriptFuncRe = re.compile("\s+((?:(?:ll)|(?:os)|(?:mod)|(?:Json)|(?:ls))\w+)\(");

    for line in lsl:
    # print "Analyzing %s" % line
        match = scriptFuncRe.search(line)
        if match != None:
#      print "Found match %s: %s" % (fn, match.group(1))
            functionsFound.add(match.group(1))

for fn in sorted_nicely(functionsFound):
    print "Found %s" % fn

print "%s functions used" % len(functionsFound)

print "Fin"
