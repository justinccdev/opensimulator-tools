#!/usr/bin/python

from io import StringIO
import BaseHTTPServer
import ConfigParser
import time

HOST_NAME = "0.0.0.0"
DEFAULT_PORT = 9000
IniNetworkSection = "Network"
IniHttpListenerPort = "http_listener_port"

class MyHandler(BaseHTTPServer.BaseHTTPRequestHandler):
  def do_HEAD(s):
    s.send_response(200)
    s.send_header("Content-type", "text/plain")
    s.end_headers()
  def do_GET(s):
    """Respond to a GET request."""
    s.send_response(200)
    s.send_header("Content-type", "text/plain")
    s.end_headers()
    s.wfile.write("OK")

if __name__ == '__main__':
  # Need to strip leading whitespace from config file - ConfigParser hates this!
  output = StringIO()

  with open("config/OpenSim.ini", "r") as fh:
    lines = fh.readlines()

  for line in lines:
    line = line.lstrip()
    output.write(unicode(line))

  output.seek(0)

  opensimIniConfig = ConfigParser.RawConfigParser()
  #opensimIniConfig.read('config/OpenSim.ini')

  opensimIniConfig.readfp(output)

  # print opensimIniConfig.sections()

  if opensimIniConfig.has_section(IniNetworkSection):
    if opensimIniConfig.has_option(IniNetworkSection, IniHttpListenerPort):
      portNumber = opensimIniConfig.getint(IniNetworkSection, IniHttpListenerPort)
      print "Probing HTTP port number %s" % portNumber
    else:
      portNumber = DEFAULT_PORT
      print "No %s found in [%s], using default port %s" % (IniHttpListenerPort, IniNetworkSection, portNumber)
  else:
    portNumber = DEFAULT_PORT
    print "No [%s] section found, using default port %s" % (IniNetworkSection, portNumber)
  
  server = BaseHTTPServer.HTTPServer
  httpd = BaseHTTPServer.HTTPServer((HOST_NAME, portNumber), MyHandler)
  print time.asctime(), "Server Starts - %s:%s" % (HOST_NAME, portNumber)
  try:
      httpd.serve_forever()
  except KeyboardInterrupt:
      pass
  httpd.server_close()
  print time.asctime(), "Server Stops - %s:%s" % (HOST_NAME, portNumber)
