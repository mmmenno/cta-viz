#! /usr/bin/env python3

import pandas

# initialization
basedir = "/home/ivo/dataprojecten/SAA_CTA/"

# read data
data = pandas.read_csv(basedir + "data/termen.csv")

# merge
data2 = pandas.read_csv(basedir + "data/cta_x_term.csv", sep=";")
data  = pandas.merge(data2, data, how='left', left_on='ONDERWERP', right_on='TERM')

# write CSV
outfile = basedir + "data/cta_x_termPLUS.csv"
data.to_csv(outfile, encoding='utf-8', index=False)
