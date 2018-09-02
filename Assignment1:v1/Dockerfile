FROM ubuntu
RUN apt-get update && apt-get install -y  build-essential
ADD ./remote/*.jpeg /remote/
ADD ftpserver.c /
RUN gcc -std=gnu99 -Wall ftpserver.c -o ftpserver 
