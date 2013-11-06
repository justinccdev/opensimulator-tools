#!/usr/bin/python

from sys import argv

if len(argv) != 5:
  print "Usage: %s <first-name> <last-name-stub> <password> <no-of-bots>" % argv[0]
  exit(-1)

firstName = argv[1]
lastNameStub = argv[2]
password = argv[3]
botCount = int(argv[4])

for i in range(botCount):
  print "reset user password %s %s_%s %s" % (firstName, lastNameStub, i, password)
