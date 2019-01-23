#! /usr/bin/env python3

import pandas
import rdflib
from rdflib.namespace import XSD

# initialization
basedir = "/home/ivo/dataprojecten/SAA_CTA/"
g = rdflib.Graph()

# read data
data = pandas.read_csv(basedir + "data/cta.csv")

# create triples
for index, row in data.iterrows():
	if str(row['BESCHRIJVING']) != "nan":
		s = rdflib.URIRef("https://archief.amsterdam/archief/10057/" + str(row['INVNRS']))
		p = rdflib.URIRef("http://purl.org/dc/elements/1.1/description")
		o = rdflib.Literal(str(row['BESCHRIJVING']))
		g.add((s,p,o))
	if str(row['SCOPEID']) != "nan":
		s = rdflib.URIRef("https://archief.amsterdam/archief/10057/" + str(row['INVNRS']))
		p = rdflib.URIRef("http://purl.org/dc/elements/1.1/identifier")
		o = rdflib.Literal(str(row['SCOPEID']))
		g.add((s,p,o))
	if str(row['BEGINJAAR']) != "nan":
		s = rdflib.URIRef("https://archief.amsterdam/archief/10057/" + str(row['INVNRS']))
		p = rdflib.URIRef("http://purl.org/dc/elements/1.1/date")
		o = rdflib.Literal(str(row['BEGINJAAR']), datatype=XSD.date)
		g.add((s,p,o))


# write RDF turtle
outfile = basedir + "data/cta.ttl"
s = g.serialize(format='turtle')
f = open(outfile,"wb")
f.write(s)
f.close()
