#!/bin/bash
rm -f new*

gcc "client_tcp.c" -o "client"
./client "$1"

rm -f "index.html"

cat >> ./index.html << HTML
    <!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
img {
    display: block;
    margin-left: auto;
    margin-right: auto;
}
</style>
</head>
<body>
HTML

for i in *.jpeg
do
cat >> ./index.html << HTML
    <img src="$i" style="width:50%;"/>
    <br>
HTML

cat >> ./index.html << HTML
</body>
</html>
HTML
done

firefox index.html
