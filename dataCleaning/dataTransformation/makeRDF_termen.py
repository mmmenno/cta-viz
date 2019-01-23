#! /usr/bin/env python3

import pandas
import rdflib

# initialization
basedir = "/home/ivo/dataprojecten/SAA_CTA/"
g = rdflib.Graph()

# read data
data = pandas.read_csv(basedir + "data/termen.csv")

# create triples
for index, row in data.iterrows():
	s = rdflib.URIRef(str(row['URI']))
	p = rdflib.URIRef("http://www.w3.org/2000/01/rdf-schema#label")
	o = rdflib.Literal(str(row['AATLABEL']))
	g.add((s,p,o))

# write RDF turtle
outfile = basedir + "data/termen.ttl"
s = g.serialize(format='turtle')
f = open(outfile,"wb")
f.write(s)
f.close()
