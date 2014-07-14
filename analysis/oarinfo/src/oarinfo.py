#!/usr/bin/python

import sys
import tarfile

if len(sys.argv) <= 1:
    print "Usage: %s <oar-path>" % (sys.argv[0])
    sys.exit(-1);
  
print "\nAnalysis of %s" % sys.argv[1]

oar = tarfile.open(sys.argv[1], 'r:gz')

generalContents = { 
    'Assets' : 0, 
    'Scene objects' : 0 
}

assetFileExtToKey = {
    'animation.bvh' : 'Animations',
    'bodypart.txt' : 'Bodyparts',
    'callingcard.txt' : 'Calling cards',
    'clothing.txt' : 'Clothing',
    'gesture.txt' : 'Gestures',
    'image.jpg' : 'Images JPEG',
    'image.tga' : 'Images TGA',
    'landmark.txt' : 'Landmarks',
    'material.xml' : 'Materials',
    'mesh.llmesh' : 'Mesh',
    'notecard.txt' : 'Notecards',
    'script.lsl' : 'Scripts',
    'object.xml' : 'Serialized objects',
    'sound.ogg' : 'Sounds OGG',
    'sound.wav' : 'Sounds WAV',
    'texture.jp2' : 'Textures JP2',
    'texture.tga' : 'Textures TGA'
}

assetContents = { 'Unknown' : 0 }

for value in assetFileExtToKey.values():
    assetContents[value] = 0

for name in oar.getnames():
    if name.startswith("objects/"):
        generalContents['Scene objects'] += 1
    elif name.startswith("assets/"):
        generalContents['Assets'] += 1
        
        assetExt = name.split("_")[-1]
        
        if assetExt in assetFileExtToKey:
            assetContentsKey = assetFileExtToKey[assetExt]
            assetContents[assetContentsKey] += 1

# Print results of analysis
longestKey = max(generalContents.keys() + assetContents.keys(), key = len)
       
for type, count in generalContents.iteritems():
    print "%-*s: %s" % (len(longestKey), type, count)
    
print "\nAssets Composition"

for type, count in assetContents.iteritems():
    print "%-*s: %s" % (len(longestKey), type, count)