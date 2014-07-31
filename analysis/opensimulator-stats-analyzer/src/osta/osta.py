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
            'abs' : { 'units' : stats[0]['abs']['units'] },
            'category' : stats[0]['category'],
            'container' : "Total",
            'name' : stats[0]['name'],
            'fullName' : ".".join((stats[0]['category'], "Total", stats[0]['name']))
        }
                                        
        totalStat['abs']['values'] = OSimStatsHelper.sumStatsToValues(stats, 'abs')
        
        #print "Summing %s" % (totalStat['name'])
        if 'delta' in stats[0]:
            totalStat['delta'] = { 'units' : stats[0]['delta']['units'] }
            totalStat['delta']['values'] = OSimStatsHelper.sumStatsToValues(stats, 'delta')
            
        return totalStat
    
    @staticmethod
    def sumStatsToValues(stats, type):
        totals = []
        for stat in stats:
            values = stat[type]['values']
            
            for i in range(0, len(values)):
                if i + 1 > len(totals):
                    totals.append(values[i])
                else:
                    totals[i] += values[i]
                    
        return totals     
    
    @staticmethod
    def splitStatsFullName(fullName):
        return statNamePartsRe.match(fullName).groups();                               
        

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
statsReportStartRe = re.compile(" - \*\*\* STATS REPORT AT")
statNamePartsRe = re.compile("^(.*?)\.(.*)\.(.*?)$");
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
        
    def getStat(self, statFullName):
        """Get a statistic given its full name."""
        if self._data == None:
            return None
        
        (category, container, name) = OSimStatsHelper.splitStatsFullName(statFullName);
        
        if category in self._data and container in self._data[category] and name in self._data[category][container]:
            return self._data[category][container][name]     
        else: 
            return None
           
    def getStats(self, glob = "*"):
        """
        Returns a dictionary of stats where fullName => stat.
        If glob is specified then this is used to match stats using their full name
        If no stats are found then an empty dictionary is returned.
        """         
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
            
    def clear(self):
        """Clear out any existing dataset."""
        self._data = {}
        self._samplesCount = 0
    
    def parse(self, path):
        """Parse OpenSimulator stats log data from the given path and merge into any existing data."""        
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
                    
                    #(category, container, name) = statFullName.split(".")       
                    (category, container, name) = OSimStatsHelper.splitStatsFullName(statFullName);
                    
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