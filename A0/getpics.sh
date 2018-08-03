#!/bin/bash
rm index* *jpg*
user=$(whoami)
wget http://dailywildlifephoto.nathab.com/
c=$(grep 'og:image' index.html  | awk '{print $2}' | sed -e s/content=//g | tr -d "'")
wget $c
imname=$(ls *.jpg)
feh --bg-scale $(pwd)/$imname
