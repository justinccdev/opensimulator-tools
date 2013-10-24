#!/usr/bin/python

import collections
import os
import sys
import xml.etree.ElementTree as ET

class OrderedSet(collections.MutableSet):

    def __init__(self, iterable=None):
        self.end = end = [] 
        end += [None, end, end]         # sentinel node for doubly linked list
        self.map = {}                   # key --> [key, prev, next]
        if iterable is not None:
            self |= iterable

    def __len__(self):
        return len(self.map)

    def __contains__(self, key):
        return key in self.map

    def add(self, key):
        if key not in self.map:
            end = self.end
            curr = end[1]
            curr[2] = end[1] = self.map[key] = [key, curr, end]

    def discard(self, key):
        if key in self.map:        
            key, prev, next = self.map.pop(key)
            prev[2] = next
            next[1] = prev

    def __iter__(self):
        end = self.end
        curr = end[2]
        while curr is not end:
            yield curr[0]
            curr = curr[2]

    def __reversed__(self):
        end = self.end
        curr = end[1]
        while curr is not end:
            yield curr[0]
            curr = curr[1]

    def pop(self, last=True):
        if not self:
            raise KeyError('set is empty')
        key = self.end[1][0] if last else self.end[2][0]
        self.discard(key)
        return key

    def __repr__(self):
        if not self:
            return '%s()' % (self.__class__.__name__,)
        return '%s(%r)' % (self.__class__.__name__, list(self))

    def __eq__(self, other):
        if isinstance(other, OrderedSet):
            return len(self) == len(other) and list(self) == list(other)
        return set(self) == set(other)

okayToMissUuids = { "5748decc-f629-461c-9a36-a35a221fe21f" : "Blank Texture" }

if len(sys.argv) <= 1:
  print "Usage: %s <path-to-xml>" % sys.argv[0]
  sys.exit(-1)

xmlPath = sys.argv[1]

tree = ET.parse(xmlPath)
root = tree.getroot()

uuids = OrderedSet()

for uuid in root.findall('.//uuid'):
  uuids.add(uuid.text)

uuids = sorted(uuids)

missingUuids = OrderedSet()
xmlDirname = os.path.dirname(xmlPath)

for uuid in uuids:
  if not os.path.exists(os.path.join(xmlDirname, uuid)):
    missingUuids.add(uuid)

print "Missing %s textures from %s referenced" % (len(missingUuids), len(uuids))

if len(missingUuids) > 0:
  print "Missing UUIDs:"

for uuid in missingUuids:
  print uuid,
  if uuid in okayToMissUuids:
    print " (%s)" % (okayToMissUuids[uuid])
  else:
    print
