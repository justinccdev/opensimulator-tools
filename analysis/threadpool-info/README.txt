README

A very simple program to print out the current maximum and minimum worker and IOCP thread settings.

Since OpenSimulator uses threads extensively, a low number of maximum threads can cause performance issues.

At startup, OpenSimulator attempts to raise these numbers itself.

See http://opensimulator.org/wiki/Configuration#Note_About_Mono for more information.
