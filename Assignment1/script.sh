#!/bin/zsh 

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

rm index.html

for i in local/*.jpeg
do 
cat >> ./index.html << HTML

    <img src="$i"/>

HTML
done

chromium index.html
