## Step 1: Run the docker
    $ docker build -t server ./   
    $ docker run -it -p 5555:5555 -t server bash

## Step 2: Run the server inside docker
    $ ./ftpserver 5555 

## Step 3: Run the client
    $ ./script.sh 4 cats 2 dogs 4 cars 4 trucks



