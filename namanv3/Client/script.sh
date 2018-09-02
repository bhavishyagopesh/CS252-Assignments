#!/bin/bash
rm new*
./C "$1"
rm -f index.html

for i in *.jpeg
do
cat >> ./index.html << HTML

    <img src="$i"/>

HTML
done

open index.html
