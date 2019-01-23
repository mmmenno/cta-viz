#! /usr/bin/env python3

import pandas

# initialization
basedir = "/home/ivo/dataprojecten/SAA_CTA/"

# read data
data = pandas.read_csv(basedir + "data/cta.csv")

# merge
data2 = pandas.read_csv(basedir + "data/cta_x_straat.csv", sep=";")
data  = pandas.merge(data2, data, how='left', left_on='SCOPEID', right_on='SCOPEID')

# write CSV
outfile = basedir + "data/cta_x_straatPLUS.csv"
data.to_csv(outfile, encoding='utf-8', index=False)
