#! /bin/sh
### BEGIN INIT INFO
# Provides:          OpenSimulator
# Required-Start:    $local_fs $network 
# Required-Stop:     $local_fs
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Tedds OpenSimulator init.d-script, with further changes by Justin Clark-Casey (http://justincc.org)
### END INIT INFO

# Version 0.2.1
# Put script in /etc/init.d/
# Then execute /etc/init.d/opensim <start>|<stop>|<restart>|<status>
#
# You must configure Robust and OpenSim to create Robust.exe.pid and OpenSim.exe.pid PID files
# by configuring PIDFile in the [Startup] section of OpenSim.ini and Robust.ini
 
set -e
 
# Location of OpenSimulator binaries
DIR=/opt/opensim/bin

# The directory where OpenSimulator and Robust are placing their pid files.  These must be of the form <service-name>.pid
# e.g. OpenSim.exe.pid
PIDDIR=/tmp/
 
# The user name which will execute the services
USER=opensim

SERVICES="Robust.exe OpenSim.exe"
#SERVICES="Robust.exe"
#SERVICES="OpenSim.exe"
 
#
# Kill values (in seconds)
#
# How long between each service being started
DELAY_STARTUP=10
# How long between each service is sent shutdown command
DELAY_KILL=20
# After shutdown has been sent to all we do another loop with "kill", then "kill -9". How long between "kill" and "kill -9".
DELAY_FORCEKILL=10

#
# Info on service handled by this script
 
# Name of service
NAME=opensim
# Description of service
DESC="OpenSimulator Server"
 
# Binaries
SCREEN=/usr/bin/screen
MONO=/usr/bin/mono
 
###########################
##### START OF SCRIPT #####
###########################
 
export PATH="${PATH:+$PATH:}/usr/sbin:/sbin"
 
# Load LSB log functions
_lsbFile=""
if [ -e /etc/debian_version ]; then
    _lsbFile="/lib/lsb/init-functions"
    if [ -f $_lsbFile ]; then
        . $_lsbFile
    else
        echo "This script requires LSB init-functions file which does not exist: $_lsbFile"
        exit 1
    fi
else
# [ -e /etc/init.d/functions ] ; then
    _lsbFile="/etc/init.d/functions"
    if [ -e $_lsbFile ]; then
        . $_lsbFile
    else
        echo "This script requires LSB init-functions file which does not exist: $_lsbFile"
        exit 1
    fi
fi
 
# Lets use fancy output
log_use_fancy_output
 
# Check that target directory exists
if test ! -d "$DIR"; then
    log_failure_msg "$NAME" "Target directory \"$DIR\" does not exist. Can not continue."
    exit 1
fi
 
# Create a reverse order for shutdown
SERVICES_REVERSE=""
reverse() { SERVICES_REVERSE="$9 $8 $7 $6 $5 $4 $3 $2 $1"; }
reverse $SERVICES
 
# Check if a service is running
isrunning() { 
    ISRUNNING="0"
    # Do we have PID-file?
    if [ -f "$PIDDIR/$1.pid" ]; then
        # Check if proc is running
        pid=`cat "$PIDDIR/$1.pid" 2> /dev/null`
        if [ "$pid" != "" ]; then
            if [ -d /proc/$pid ]; then
                # Process is running
                ISRUNNING="1"
            fi
        fi
    fi
    #ISRUNNING="0"
}
 
#
# Process commands
#
case "$1" in
start)
    # Start all services one by one
    for server in $SERVICES; do
        log_daemon_msg "Starting $NAME" "$server"

        isrunning $server
        case "$ISRUNNING" in
            1) log_daemon_msg "Process already started. Please stop first"; log_end_msg 1 ;;
            0) 
                # Process is not running
                # Start process and sleep...
                exefile="/tmp/exe.OpenSim.$server.sh"

                # Need to make external file, had big problems with screen params
                echo \#\!/bin/bash > $exefile
                echo cd $DIR >> $exefile
                echo $MONO --debug $server >> $exefile
                chmod +x $exefile

                cmd_screen="-S $server -d -m $exefile"
                start-stop-daemon --start --pidfile $PIDDIR/$server.pid -u $USER --chdir $DIR --chuid $USER -x /usr/bin/screen -- $cmd_screen

                # Delay between services that are started
                sleep $DELAY_STARTUP

                rm $exefile 2> /dev/null
 
                isrunning $server
                case "$ISRUNNING" in
                    1) 
                        # Process started ok
                        #log_daemon_msg "Success"; 
                        log_end_msg 0 
                        ;;
                    0) 
                        #log_daemon_msg "Failure"; 
                        log_end_msg 1 
                        ;;
                esac
                ;;
        esac
    done
    ;;
 
stop)
    _killCount=0

    for server in $SERVICES_REVERSE; do
        log_daemon_msg "Stopping $NAME" "$server"

        isrunning $server
        case "$ISRUNNING" in
        1) 
            _killCount=`expr $_killCount + 1`
            log_daemon_msg "Sending shutdown command:"; 

            cmd_screen="$SCREEN -S $server -p 0 -X stuff quit$(printf \\r)"

            # We can't use start-stop-daemon here currently because it will only send a signal to OpenSim
            # --stop doesn't execute a command
            # start-stop-daemon --stop --pidfile $PIDDIR/$server.pid -u $USER --chuid $USER -- $cmd_screen

            su $USER -c "$cmd_screen"

            # Wait for it to shut down...
            sleep $DELAY_KILL

            isrunning $server
            case "$ISRUNNING" in
                1) log_daemon_msg "$server is still running."; log_end_msg 0 ;;
                0) log_daemon_msg "$server has been shutdown"; log_end_msg 0 ;;
            esac

            ;;
        0) 
            log_daemon_msg "$server is not running"; log_end_msg 0
            ;;
        esac

    done

    # Check if any procs are still running
    for server in $SERVICES; do
        isrunning $server
        case "$ISRUNNING" in
        1) 
            log_warning_msg "Stopping $NAME" "$server is still running: Forcing kill"
            echo `kill $pid 2> /dev/null`;
            sleep $DELAY_FORCEKILL
            echo `kill -9 $pid 2> /dev/null`;
            sleep 1

            # Now check again if it is still running...
            isrunning $server
            case "$ISRUNNING" in
                0) log_daemon_msg "Success"; log_end_msg 0 ;;
                1) log_daemon_msg "Process is still running... Even after \"kill -9 $pid\". WOW..."; log_end_msg 0 ;;
            esac
            ;;
        0) 
            #log_daemon_msg ""; 
            ;;
        esac
    done

    log_daemon_msg "$NAME: All done (stopped $_killCount processes)"; log_end_msg 0

    ;;

status)
    # Count how many processes we need
    PROCCOUNT=0
    for i in $SERVICES; do
        PROCCOUNT=`expr $PROCCOUNT + 1`
    done

    # Go through server PID files and count how many are running
    log_daemon_msg "$NAME: Running processes:"
    _pidCount=0
    for server in $SERVICES; do

        isrunning $server
        case "$ISRUNNING" in
            1) 
                # This server is running
                _pidCount=`expr $_pidCount + 1`
                log_daemon_msg "$server"
                ;;
            0) 
                ;;
        esac
    done

    log_daemon_msg " ($_pidCount of $PROCCOUNT processes are running)"

    # Check if running proc count matches requires proc count
    if [ $_pidCount -eq $PROCCOUNT ]; then
        log_daemon_msg "$NAME is running"
        exit 0
    else
        log_daemon_msg "$NAME is NOT running"
        exit 1
    fi
    ;;

restart)
    $0 stop
    $0 start
    ;;
*)
    echo "Usage: /etc/init.d/$NAME {start|stop|restart|status}" >&2
    exit 1
    ;;
esac

exit 0
