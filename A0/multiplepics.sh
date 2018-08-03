#!/bin/bash
rm browse-photos* *jpg temp.txt
wget http://dailywildlifephoto.nathab.com/browse-photos
grep -o "https://s3.amazonaws.com.*jpg" browse-photos  > temp.txt
wget $(shuf -n 1 temp.txt)
imname=$(ls *.jpg)
DISPLAY=:0 feh --bg-scale $imname
