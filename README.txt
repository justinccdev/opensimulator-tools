= README =

A bunch of miscellaneous tools that may (or may not) grow over time.

== analyze-import-xml ==

A very basic script to analyze an xml package that can be imported via a Singularity or Imprudence viewer's import xml facility.
From reports, the import package used by Firestorm is slightly different so this may not work on that.
Currently just checks whether required textures are present
XML package currently needs to be unzipped first

== create-user-line-generator ==

A very basic script to generate a bunch of create user lines for pasting into an OpenSimulator ROBUST
Console in order to generate many users simultaneously (e.g. for bot testing purposes

== opensimulator-script-analyzer ==

A very basic script that looks for OpenSimulator script functions (ll, os, mod, etc)
In a bunch of files and prints out the number of times each one is used.

== reset-user-password-line-generator ==

Another very basic script to generate a bunch of reset user password lines for pasting
Into an OpenSimulator ROBUST console.  One use is to reset bot scripts.
