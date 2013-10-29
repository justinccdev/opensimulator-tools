#!/usr/bin/python

import BaseHTTPServer
import time

HOST_NAME = "0.0.0.0"
PORT_NUMBER = 9000

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
  server = BaseHTTPServer.HTTPServer
  httpd = BaseHTTPServer.HTTPServer((HOST_NAME, PORT_NUMBER), MyHandler)
  print time.asctime(), "Server Starts - %s:%s" % (HOST_NAME, PORT_NUMBER)
  try:
      httpd.serve_forever()
  except KeyboardInterrupt:
      pass
  httpd.server_close()
  print time.asctime(), "Server Stops - %s:%s" % (HOST_NAME, PORT_NUMBER)
