## Step 1: Run the docker
    $ docker build -t server ./   
    $ docker run -it -p 5432:5432 -t server bash

## Step 2: Run the server inside docker
    $ ./S 5432

## Step 3: Run the client
    $ gcc -std=gnu99 -Wall client_tcp.c -o C
    $ ./script.sh "4 cats 2 dogs 4 cars 4 trucks"
