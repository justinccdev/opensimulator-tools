#!/usr/bin/perl -w

### CONFIGURE THESE PATHS ###
my $binaryPath = "/home/opensim/opensim/opensim-current/bin";
my $pidPath = "/tmp/OpenSim.pid";
### END OF CONFIG ###

-f $pidPath and die "ERROR: OpenSim PID file $pidPath still present.  Assuming OpenSim has been started already.\n";

# If PID isn't set then we'll check the screen list.  
# However, this is a much less perfect mechanism since OpenSimulator may have been started outside screen
`screen -list` =~ /OpenSim/ and die "ERROR: Screen session for OpenSim already started.\n";

chdir($binaryPath) or die;
execCmd("screen -S OpenSim -d -m mono --debug OpenSim.exe");

# I would like to perform this check but it generates false positives and I would rather not put in a messy sleep
# unless (-f $pidPath) { warn "WARNING: OpenSimulator PID file $pidPath not found after startup.  Set this in [Startup] PIDFile in OpenSim.ini"; }

if (`screen -list` =~ /OpenSim/)
{
    print "OpenSim starting in screen instance..\n";
}
else
{
    die "ERROR: OpenSim did not start.\n";
}

sub execCmd
{
    my $cmd = $_[0];
    print "Excecuting command $cmd\n";
    system ($cmd) && die;
}
