# README #

A very simple program to print out the current maximum and minimum worker and
IOCP thread settings for the built-in threadpool on Mono and Windows [1].

Since OpenSimulator uses threads extensively, a low number of maximum threads
can cause performance issues.

At startup, OpenSimulator attempts to raise these numbers itself.

See http://opensimulator.org/wiki/Configuration#Note_About_Mono for more
information.

The min thread figures are the number of threads that Windows or Mono will
always allocate on demand.

If the program requests more threads from the pool, it is the virtual machine
that decides whether to allocate those threads or wait for other tasks to be
completed.

The max thread figures are the maxmimum number of threads that the pool can
ever create.

If the program requests an additional thread from the pool, the request has to
wait until an existing thread is available for a new task.

# COMPILATION #

$ ./compile-linux.sh

# REFERENCES #

[1] http://msdn.microsoft.com/en-us/library/system.threading.threadpool%28v=vs.100%29.aspx
