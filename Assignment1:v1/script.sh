#!/bin/zsh 
rm -f local/*
## Enter the command
echo 'Instruction-> #cats cats #dogs dogs #cars cars #trucks trucks'

numcats="$1"
numdogs="$3"
numcars="$5"
numtrucks="$7"

for ((var=1;var<=$((numcats));var++))
do 
    query="get cats$var.jpeg\nexit"
    echo $query | ./ftpclient localhost 5555
done

for ((var=1;var<=$((numdogs));var++))
do 
    query="get dogs$var.jpeg\nexit"
    echo $query | ./ftpclient localhost 5555
done

for ((var=1;var<=$((numcars));var++))
do 
    query="get cars$var.jpeg\nexit"
    echo $query | ./ftpclient localhost 5555
done
for ((var=1;var<=$((numtrucks));var++))
do 
    query="get trucks$var.jpeg\nexit"
    echo $query | ./ftpclient localhost 5555
done

rm -f index.html

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

for i in local/*.jpeg
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

