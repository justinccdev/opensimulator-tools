#!/usr/bin/python

password = "changeme"
email = "none@none.com"
idStub = "b0b0b0b0-0000-0000-0000-000000000"

for i in range(220):
  print "create user ima bot_%s %s %s %s%03i" % (i, password, email, idStub, i)
