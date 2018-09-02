#!/bin/bash
folder=""
header="null"
out_file="index.html"

param=$1
port=$2

`gcc "client_tcp.c" -o "client"`
`./client $param $port`

if [ -f $out_file ]; then
	rm $out_file
    rm -f *.jpeg
fi
# <tr><td><img src="$folder/dogs1.jpeg" style="width: 100%"></td></tr>

echo `cat "header"` >> $out_file

#echo "<tr><th></th></tr> " >> $out_file
#for file_name in *.jpeg; do
#	echo $file_name
#    # echo '<tr><td><img src="'$file_name'" style="width: 100%"></td></tr>' >> $out_file
#done

echo `cat "footer"` >> $out_file

#chromium "index.html"
