#! /usr/bin/env python3

import pandas
import rdflib

# initialization
basedir = "/home/ivo/dataprojecten/SAA_CTA/"
g = rdflib.Graph()

# read data
data = pandas.read_csv(basedir + "data/cta_x_straatPLUS.csv")

# create triples
for index, row in data.iterrows():
   s = rdflib.URIRef("https://archief.amsterdam/archief/10057/" + str(row['INVNRS']))
   p = rdflib.URIRef("http://purl.org/dc/terms/spatial")
   o = rdflib.URIRef(str(row['URI']))
   g.add((s,p,o))

# write RDF turtle
outfile = basedir + "data/cta_x_straat.ttl"
s = g.serialize(format='turtle')
f = open(outfile,"wb")
f.write(s)
f.close()
