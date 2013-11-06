#!/usr/bin/python

from sys import argv

if len(argv) != 5:
  print "Usage: %s <first-name> <last-name-stub> <password> <no-of-bots>" % argv[0]
  exit(-1)

firstName = argv[1]
lastNameStub = argv[2]
password = argv[3]
botCount = int(argv[4])

email = "none@none.com"
idStub = "b0b0b0b0-0000-0000-0000-000000000"

for i in range(botCount):
  print "create user %s %s_%s %s %s %s%03i" % (firstName, lastNameStub, i, password, email, idStub, i)
