#!/usr/bin/python

import xml.etree.ElementTree as ET

tree = ET.parse("retro trailer.xml")
root = tree.getroot()

for uuid in root.findall('.//uuid'):
  print uuid.text
