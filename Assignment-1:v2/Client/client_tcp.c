/* credit @Daniel Scocco */

/****************** CLIENT CODE ****************/

#include <stdio.h>
#include <stdlib.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>
#include <unistd.h>  

#include <errno.h>

void clearStr (char* str) {
    for (int i = 0; str[i] != 0; i++) str[i] = '\0';
}

int receive_image(int socket,char file_name[])
{ // Start function 

    int buffersize = 0, recv_size = 0,size = 0, read_size, write_size, packet_index =1,stat;

    char imagearray[10241],verify = '1';
    FILE *image;

    //Find the size of the image
    do{
    stat = read(socket, &size, sizeof(int));
    }while(stat<0);

    printf("Packet received.\n");
    printf("Packet size: %i\n",stat);
    printf("Image size: %i\n",size);
    printf(" \n");

    char buffer[] = "Got_it";
    char str[10];
    strcpy(str, file_name);
    //Send our verification signal
    do{
    stat = write(socket, &buffer, 6*sizeof(int));
    }while(stat<0);

    printf("Reply sent\n");
    printf(" \n");

    image = fopen(str, "w");

    if( image == NULL) {
    printf("Error has occurred. Image file could not be opened\n");
    return -1; }

    while(recv_size < size) {
        //while(packet_index < 2){
        do{
        read_size = read(socket,imagearray, 10241);
        }while(read_size <0);

        printf("Packet number received: %i\n",packet_index);
        printf("Packet size: %i\n",read_size);

        //Write the currently read data into our image file
        write_size = fwrite(imagearray,1,read_size, image);
        printf("Written image size: %i\n",write_size); 

        if(read_size !=write_size) {
            printf("error in read write\n");    
        }

        //Increment the total number of bytes read
        recv_size += read_size;
        packet_index++;
        printf("Total received image size: %i\n",recv_size);
        printf(" \n");
        printf(" \n");
    }

    fclose(image);
    printf("Image successfully Received!\n");
    return 1;
}

int is_num(char x) {
    if (x >= '1' && x <= '9') return 1;
    else return 0;
}

int letscallafunction (char* request) {
    int i = 0, count = 0;
    while (request[i] != '\0') {
        if (is_num(request[i]) == 1) count += request[i] - '0';
        i++;
    }
    return count;
}

int main(int argc, char** argv){
    int clientSocket;
    char buffer[10240];
    struct sockaddr_in serverAddr;
    socklen_t addr_size;

    /*---- Create the socket. The three arguments are: ----*/
    /* 1) Internet domain 2) Stream socket 3) Default protocol (TCP in this case) */
    clientSocket = socket(PF_INET, SOCK_STREAM, 0);

    /*---- Configure settings of the server address struct ----*/
    /* Address family = Internet */
    serverAddr.sin_family = AF_INET;
    /* Set port number, using htons function to use proper byte order */
    serverAddr.sin_port = htons(5432);
    /* Set IP address to localhost */
    serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
    /* Set all bits of the padding field to 0 */
    memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);


    /*---- Connect the socket to the server using the address struct ----*/
    addr_size = sizeof serverAddr;
    connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);
    /*---- Receive instruction ----*/

    /*---- Send request ----*/
    send(clientSocket, argv[1], strlen(argv[1])+1, 0);
    printf("Message sent: %s\n", argv[1]);

    /*---- Read the message from the server into the buffer ----*/
    //recv(clientSocket, buffer, 1024000, 0);
    int no_of_images = letscallafunction (argv[1]);
    printf("Going to receive %d images\n", no_of_images);
    char file_name[] = "newa.jpeg";
    for (int i = 0; i < no_of_images; i++) {
        //recvfrom(clientSocket, buffer, 1024, 0, (struct sockaddr *) &serverAddr, &addr_size);
        file_name[3] = 'a' + i;
        printf("%s\n", file_name);
        receive_image(clientSocket, file_name);
    }
    return 0;
}
