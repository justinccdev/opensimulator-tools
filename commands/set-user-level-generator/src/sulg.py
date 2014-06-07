#!/usr/bin/python

from sys import argv

if len(argv) != 5:
  print "Usage: %s <first-name> <last-name-stub> <new-level> <no-of-bots>" % argv[0]
  exit(-1)

firstName = argv[1]
lastNameStub = argv[2]
newLevel = argv[3]
botCount = int(argv[4])

for i in range(botCount):
  print "set user level %s %s_%s %s" % (firstName, lastNameStub, i, newLevel)
