#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <netdb.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <dirent.h>
#include <errno.h>
#include <sys/sendfile.h>
#include <fcntl.h>

void syserr(char* msg) { perror(msg); exit(-1); }

int main(int argc, char* argv[])
{
  int sockfd, portno, n, fileSize;
  struct hostent* server;
  struct sockaddr_in serv_addr;
  char buffer[256];
  char fileSizeBuffer[256];
  DIR *dir;
  struct dirent *directory;
  dir = opendir("./local");

  if(argc != 3) {
    fprintf(stderr, "Usage: %s <hostname> <port>\n", argv[0]);
    return 1;
  }
  server = gethostbyname(argv[1]);
  if(!server) {
    fprintf(stderr, "ERROR: no such host: %s\n", argv[1]);
    return 2;
  }
  portno = atoi(argv[2]);
  

  //socket file descriptor
  sockfd = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
  if(sockfd < 0) syserr("can't open socket");
  printf("create socket...\n");

  //once socket is created:
  memset(&serv_addr, 0, sizeof(serv_addr));
  serv_addr.sin_family = AF_INET; //IPV4
  serv_addr.sin_addr = *((struct in_addr*)server->h_addr);
  serv_addr.sin_port = htons(portno); //port

  if(connect(sockfd, (struct sockaddr*)&serv_addr, sizeof(serv_addr)) < 0)
    syserr("can't connect to server");
  printf("connect...\n");

  do
  {
	  fgets(buffer, 255, stdin);
	  n = strlen(buffer);
  
	  if(n > 0 && buffer[n-1] == '\n') //line break
		  buffer[n-1] = '\0';
	  
	  //send
	  n = send(sockfd, buffer, strlen(buffer), 0);
	  printf("user sent %s\n", buffer);

	  if(n < 0) //couldn't send
		  syserr("can't send to server");
	
	  //user calls download
	  if(buffer[0] == 'g' &&
		 buffer[1] == 'e' &&
		 buffer[2] == 't' &&
		 buffer[3] == ' ')
	  {
		  printf("User requested a download.\n");

		  //we catch the file name
		  char fileName[256];
		  memset(&fileName, 0, sizeof(fileName));
		  
		  //parse
		  int j = 0;
		  for(int i = 4; i <= strlen(buffer); i++)
		  {
			  fileName[j] = buffer[i];
			  j++;
		  }

		  //catch file size:
		  recv(sockfd, buffer, sizeof(buffer), 0);
		  fileSize = atoi(buffer);

		  //send size back as ACK:
		  send(sockfd, buffer, sizeof(buffer), 0);

		  //print file name and size:
		  printf("File: '%s' (%d bytes)\n",fileName, fileSize);
		  

		  //receive data:
		  memset(&buffer, 0, sizeof(buffer));
		  int remainingData = 0;
		  ssize_t len;
		  char path[256] = "./local/";
		  strcat(path, fileName);
		  printf("path: %s", path);
		  FILE* fp;
		  fp = fopen(path, "wb");
		  						
		  remainingData = fileSize;
		  //while(((len = recv(sockfd, buffer, 256, 0)) > 0) && (remainingData > 0))
		  printf("remainingData: %d", remainingData);
		  while(remainingData != 0)
		  {
			  if(remainingData < 256)
			  {
				  len = recv(sockfd, buffer, remainingData, 0);
				  fwrite(buffer, sizeof(char), len, fp);
				  remainingData -= len;
				  printf("Received %lu bytes, expecting %d bytes\n", len, remainingData);
				  break;
			  }
			  else
			  {
			  	len = recv(sockfd, buffer, 256, 0); //256
			  	fwrite(buffer, sizeof(char), len, fp);
		      	remainingData -= len;
			  	printf("Received %lu bytes, expecting: %d bytes\n", len, remainingData);
			  }
		  }
		  fclose(fp);
		  n = recv(sockfd, buffer, 256, 0); 

		  //clean buffer
		  memset(&buffer, 0, sizeof(buffer));
	  }
	  else if(strcmp(buffer, "exit") == 0)
	  {
		  break;
	  }
	  //clean buffer here maybe
	  memset(&buffer, 0, sizeof(buffer));
  } while(strcmp(buffer, "exit") != 0);
  
  close(sockfd);
  return 0;
}
