/* credit @Daniel Scocco */

/****************** SERVER CODE ****************/

#include <stdio.h>
#include <netinet/in.h>
#include <string.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <errno.h>

int welcomeSocket, newSocket;
char buffer[1024];
struct sockaddr_in serverAddr;
struct sockaddr_storage serverStorage;
socklen_t addr_size;

void clearStr (char* str) {
    for (int i = 0; str[i] != 0; i++) str[i] = 0;
}

void send_image(int socket, char file_name[]){

  FILE *picture;
  int size, read_size, stat, packet_index;
  char send_buffer[10240], read_buffer[256];
  packet_index = 1;

  picture = fopen(file_name, "r");
  printf("Getting Picture Size\n");

  if(picture == NULL) {
      printf("Error Opening Image File"); }

  fseek(picture, 0, SEEK_END);
  size = ftell(picture);
  fseek(picture, 0, SEEK_SET);
  printf("Total Picture size: %i\n",size);

  //Send Picture Size
  printf("Sending Picture Size\n");
  write(socket, (void *)&size, sizeof(int));

  //Send Picture as Byte Array
  printf("Sending Picture as Byte Array\n");

  do { //Read while we get errors that are due to signals.
    stat=read(socket, &read_buffer , 255);
    printf("Bytes read: %i\n",stat);
  } while (stat < 0);

  printf("Received data in socket\n");
  printf("Socket data: %s\n", read_buffer);


  while(!feof(picture)) {
    //Read from the file into our send buffer
    read_size = fread(send_buffer, 1, sizeof(send_buffer)-1, picture);

    //Send data through our socket
    do{
      stat = write(socket, send_buffer, read_size);
    }while (stat < 0);

    printf("Packet Number: %i\n",packet_index);
    printf("Packet Size Sent: %i\n",read_size);
    printf(" \n");
    printf(" \n");


    packet_index++;

    //Zero out our send buffer
    clearStr(send_buffer);
  }
}

void tostring(char str[], int num) {
    int i, rem, len = 0, n;

    n = num;
    while (n != 0) {
        len++;
        n /= 10;
    }
    for (i = 0; i < len; i++) {
        rem = num % 10;
        num = num / 10;
        str[len - (i + 1)] = rem + '0';
    }
    str[len] = '\0';
}

int is_num(char x) {
    if (x >= '1' && x <= '9'){
        printf("Got %c\n", x);
        return 1;
    }
    else return 0;
}

void sendstuff (int count, char* item) {
    printf("Time to send %d %s\n", count, item);
    char file_name[10];

    if (item[0] == 'd') strcpy(file_name, "dog1.jpeg");
    else if (item[0] == 't') strcpy(file_name, "trc1.jpeg");
    else if (item[2] == 't') strcpy(file_name, "cat1.jpeg");
    else strcpy(file_name, "car1.jpeg");

    for (int i = 1; i <= count ; i++) {
        file_name[3] = i + '0';
        printf("file_name to be opened: %s\n", file_name);
        send_image(newSocket, file_name);
    }
    //in the end
    clearStr(item);
    count = 0;
}

void letscallafunction (char* request) {
    int i = 0;
    int curr_count = 0; char item[20]; int j = 0;
    while (request[i] != 0) {
        if (is_num(request[i]) == 1) curr_count = request[i] - '0';
        else if(request[i] != ' ') item[j++] = request[i];
        else if (j != 0) {
            item[j] = '\0';
            printf("Going to send %d %s\n", curr_count, item);
            sendstuff(curr_count, item);
            j = 0;
        }
        i++;
    }
    item[j] = '\0';
    printf("Finally, Going to send %d %s\n", curr_count, item);
    sendstuff(curr_count, item);
    return;
}


int main(){
    /*---- Create the socket. The three arguments are: ----*/
    /* 1) Internet domain 2) Stream socket 3) Default protocol (TCP in this case) */
    welcomeSocket = socket(PF_INET, SOCK_STREAM, 0);

    /*---- Configure settings of the server address struct ----*/
    /* Address family = Internet */
    serverAddr.sin_family = AF_INET;
    /* Set port number, using htons function to use proper byte order */
    serverAddr.sin_port = htons(5432);
    /* Set IP address to localhost */
    serverAddr.sin_addr.s_addr = inet_addr("0.0.0.0");
    /* Set all bits of the padding field to 0 */
    memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

    /*---- Bind the address struct to the socket ----*/
    bind(welcomeSocket, (struct sockaddr *) &serverAddr, sizeof(serverAddr));

    /*---- Listen on the socket, with 5 max connection requests queued ----*/
    if (listen (welcomeSocket,5) == 0) printf("I'm listening\n");
    else printf("Error\n");

    while (1) {
        /*---- Accept call creates a new socket for the incoming connection ----*/
        addr_size = sizeof serverStorage;
        newSocket = accept(welcomeSocket, (struct sockaddr *) &serverStorage, &addr_size);

        /*---- Receive Request ----*/
        recv(newSocket, buffer, 1024, 0);
        printf("The request received is: %s\n", buffer);

        letscallafunction(buffer);

        /*---- Clear the buffer for the next input ----*/
        clearStr(buffer);
    }
    return 0;
}
