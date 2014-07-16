import argparse
import fnmatch
import pprint
import re
import sys

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
statsReportStartRe = re.compile(" - \*\*\* STATS REPORT AT")
valueRe = re.compile("([^ %/]+)(.*)")

############
### Osta ###
############
class OSimStatsCorpus:
        
    _data = {}    
    _samplesCount = 0
    
    @property
    def data(self):
        return self._data        
    
    def __init__(self):
        pass
    
    def __len__(self):
        return self._samplesCount
        
    @staticmethod
    def parseValue(rawValue, valueRe):
        valueMatch = valueRe.match(rawValue)
        return float(valueMatch.group(1)), valueMatch.group(2)
    
    """Get a statistic given its full name"""
    def getStat(self, statFullName):
        if self._data == None:
            return None
        
        (category, container, name) = statFullName.split(".")
        
        if category in self._data and container in self._data[category] and name in self._data[category][container]:
            return self._data[category][container][name]     
        else: 
            return None   
    
    """Parse OpenSimulator stats log data from the given path."""
    def parse(self, path):
        
        # Structure
        # category : { 
        #    container : { 
        #        stat : {
        #            'abs'   : { 'values' : [], 'units' : "" },
        #            'delta' : { 'values' : [], 'units' : "" }
        # }  
        # delta may not be present
                 
        self._data = {}
        self._samplesCount = 0
        
        with open(path) as f:
            for line in f:    
                match = lineRe.match(line)
                
                if match != None:
                    statFullName = match.group(2)
                    (category, container, name) = statFullName.split(".")       
                    
                    rawValue = match.group("abs")
                    #print match.lastindex
                    #print rawValue                                                            
                    
                    value = OSimStatsCorpus.parseValue(rawValue, valueRe)
                    
                    if not category in self._data:
                        self._data[category] = {}
                        
                    if not container in self._data[category]:
                        self._data[category][container] = {}
                    
                    if not name in self._data[category][container]:
                        entry = { 
                            'abs' : { 'values' : [], 'units' : value[1] },
                            'fullName' : statFullName,
                            'name' : name
                        }
                        self._data[category][container][name] = entry
                        
                    stat = self._data[category][container][name]           
                                    
                    stat['abs']['values'].append(value[0])
                    
                    # Handle delta value if present
                    if match.group("delta"):                
                        rawValue = match.group("delta")
                        value = OSimStatsCorpus.parseValue(rawValue, valueRe)
                        
                        if not 'delta' in stat:
                            stat['delta'] = { 'values' : [], 'units' : value[1] }
                            
                        stat['delta']['values'].append(value[0])
                                                                
                else:
                    match = statsReportStartRe.search(line)
                    
                    if (match != None):
                        self._samplesCount += 1
                    else:
                        print "Ignoring [%s]" % (line)                