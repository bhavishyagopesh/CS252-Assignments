FROM ubuntu
RUN apt-get update && apt-get install -y  build-essential
ADD *.jpeg /
EXPOSE 5432
ADD server_tcp.c /
RUN gcc -std=gnu99 -Wall server_tcp.c -o S 
