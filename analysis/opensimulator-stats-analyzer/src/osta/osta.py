import argparse
import collections
import fnmatch
import pprint
import re
import sys

#######################
### OSimStatsHelper ###
#######################
class OSimStatsHelper:
    """Takes a list of stats and returns a stat containing their summation by each sample."""
    @staticmethod
    def sumStats(stats):
        totalStat = { 
            'abs' : { 'units' : stats[1]['abs']['units'] },
            'category' : stats[1]['category'],
            'container' : "Total",
            'name' : stats[1]['name'],
            'fullName' : ".".join((stats[1]['category'], "Total", stats[1]['name']))
        }
                                        
        totals = []
        for stat in stats:
            absValues = stat['abs']['values']
            
            for i in range(0, len(absValues)):
                if i + 1 > len(totals):
                    totals.append(absValues[i])
                else:
                    totals[i] += absValues[i]
                    
        totalStat['abs']['values'] = totals
        
        return totalStat

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
statsReportStartRe = re.compile(" - \*\*\* STATS REPORT AT")
valueRe = re.compile("([^ %/]+)(.*)")

#######################
### OSimStatsCorpus ###
#######################
class OSimStatsCorpus:
        
    _data = {}    
    _samplesCount = 0
    
    @property
    def data(self):
        return self._data        
    
    def __init__(self):
        self.clear()
    
    def __len__(self):
        return self._samplesCount
        
    @staticmethod
    def parseValue(rawValue, valueRe):
        valueMatch = valueRe.match(rawValue)
        return float(valueMatch.group(1)), valueMatch.group(2)
    
    """Get a statistic given its full name."""
    def getStat(self, statFullName):
        if self._data == None:
            return None
        
        (category, container, name) = statFullName.split(".")
        
        if category in self._data and container in self._data[category] and name in self._data[category][container]:
            return self._data[category][container][name]     
        else: 
            return None
    
    """
    Returns a dictionary of stats where fullName => stat.
    If glob is specified then this is used to match stats using their full name
    If no stats are found then an empty dictionary is returned.
    """        
    def getStats(self, glob = "*"):
        # FIXME: Doing far more work than necessary here if we simply want all stats without matching.
        if glob == None:
            glob = "*"
            
        matchingStats = collections.OrderedDict()
        
        for category, containers in self._data.items():
            for container, stats in containers.items():
                for statName, stat in stats.items():        
                    if fnmatch.fnmatch(stat['fullName'], glob):
                        matchingStats[stat['fullName']] = stat
                        
        return matchingStats
        
    """Clear out any existing dataset."""
    def clear(self):
        self._data = {}
        self._samplesCount = 0
    
    """Parse OpenSimulator stats log data from the given path and merge into any existing data."""    
    def parse(self, path):        
        # Structure
        # category : { 
        #    container : { 
        #        stat : {
        #            'abs'   : { 'values' : [], 'units' : "" },
        #            'delta' : { 'values' : [], 'units' : "" }
        #            'name' : string
        #            'fullName' : string
        #            'category' : string
        #            'container' : string
        # }  
        # delta may not be present
                         
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
                        self._data[category] = collections.OrderedDict()
                        
                    if not container in self._data[category]:
                        self._data[category][container] = collections.OrderedDict()
                    
                    if not name in self._data[category][container]:
                        entry = { 
                            'abs' : { 'values' : [], 'units' : value[1] },
                            'category' : category,
                            'container' : container,                            
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