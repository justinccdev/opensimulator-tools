# README #

## Introduction ##
This directory contains various programs in PHP for performing
intergration tasks with OpenSimulator running in grid mode.  These currently
work for standalone mode since they rely on the availability of the ROBUST
service interfaces over HTTP.

Please see the individual src/programs/*/README.txt for more details.  Each
program is self-contained within its own directory, apart from references to
common utility and config files in parent directories (e.g. utils.php).

## Prerequisites ##
1. PHP >= 5.1.0
2. PHP curl module
3. Console_CommandLine PEAR package (http://pear.php.net/package/Console_CommandLine)

## Use ##
1. Edit config.php so that the service URIs point to your ROBUST service host
or hosts.

2. Run/examine individual PHP scripts in programs/

## Directory Structure ##

* config.php - Configuration file
* utils.php - 

### attic/ ###
Code that I want to keep around but is not currently in use.

### connectors/ ###
Connectors that create and parse OpenSimulator service requests.  Provides
methods that callers can use to invoke service requests without needing to know
the service call formats (e.g. GetFolderContent() from
inventory-service-connector.php).  However, at this time, scripts still need to
directly parse XML responses.  In the future, classes may be returned instead of
raw XML to further insulate the calling script from low level details.

OpenSimulator services are called using PHP Curl.

### openmetaversetypes/ ###
A sliver of the C# OpenMetaverse library [1] in order to
support the PHP scripts, chiefly enumerations at this time.  Main repository for
this is at [2].

### programs/ ###
Example scripts for performing various tasks (e.g. adding attachments to
avatars).

## References ##
[1] https://github.com/openmetaversefoundation/libopenmetaverse
[2] https://github.com/justincc/libomv4php
