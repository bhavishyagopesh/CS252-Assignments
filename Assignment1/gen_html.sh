#!/bin/bash
folder=remote
header="null"
out_file="index.html"

if [ -f $out_file ]; then
	rm $out_file
fi
# <tr><th>Dogs</th></tr>
# <tr><td><img src="$folder/dogs1.jpeg" style="width: 100%"></td></tr>


echo `cat "header"` >> $out_file

for file_name in $( ls $folder ); do

	# extract type of image
	new_name=`echo $file_name | cut -f1 -d"."`
	new_name=`echo $new_name | sed 's/.$//'`
	
	echo $new_name
	if [ "$header" != "$new_name" ]; then
		echo $header $new_name
		echo "<tr><th>"$new_name"</th></tr>" >> $out_file
		header="$new_name"
	fi

	echo '<tr><td><img src="'$folder'/'$file_name'" style="width: 100%"></td></tr>' >> $out_file
done

echo `cat "footer"` >> $out_file
