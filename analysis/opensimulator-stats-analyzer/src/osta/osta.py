import argparse
import fnmatch
import pprint
import re
import sys

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
valueRe = re.compile("([^ %/]+)(.*)")

############
### Osta ###
############
class Osta:
    @staticmethod
    def parseValue(rawValue, valueRe):
        valueMatch = valueRe.match(rawValue)
        return float(valueMatch.group(1)), valueMatch.group(2)
    
    @staticmethod
    def parse(path):          
        data = {}
        
        with open(path) as f:
            for line in f:    
                match = lineRe.match(line)
                
                if match != None:
                    statFullName = match.group(2)
                    (category, container, name) = statFullName.split(".")       
                    
                    rawValue = match.group("abs")
                    #print match.lastindex
                    #print rawValue                                                            
                    
                    value = Osta.parseValue(rawValue, valueRe)
                    
                    if not category in data:
                        data[category] = {}
                        
                    if not container in data[category]:
                        data[category][container] = {}
                    
                    if not name in data[category][container]:
                        entry = { 
                            'abs' : { 'values' : [], 'units' : value[1] },
                            'fullName' : statFullName
                        }
                        data[category][container][name] = entry
                        
                    stat = data[category][container][name]           
                                    
                    stat['abs']['values'].append(value[0])
                    
                    # Handle delta value if present
                    if match.group("delta"):                
                        rawValue = match.group("delta")
                        value = Osta.parseValue(rawValue, valueRe)
                        
                        if not 'delta' in stat:
                            stat['delta'] = { 'values' : [], 'units' : value[1] }
                            
                        stat['delta']['values'].append(value[0])                
                        
                #else:
                #    print "Ignoring [%s]" % (line)
                
        return data