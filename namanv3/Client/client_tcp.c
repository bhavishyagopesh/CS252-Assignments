/* credit @Daniel Scocco */

/****************** CLIENT CODE ****************/

#include <stdio.h>
#include <stdlib.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>

void clearStr (char* str) {
    for (int i = 0; str[i] != 0; i++) str[i] = 0;
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
    char file_name[] = "new1.jpeg";
    for (int i = 0; i < no_of_images; i++) {
        //recvfrom(clientSocket, buffer, 1024, 0, (struct sockaddr *) &serverAddr, &addr_size);
        file_name[3] = '1' + i;
        printf("%s\n", file_name);
        recv(clientSocket, buffer, 10240, 0);
        int size = atoi(buffer);
        clearStr(buffer);
        printf("%d\n", size);
        send(clientSocket, "aa", 3, 0);
        printf("Ready to receive\n");
        recv(clientSocket, buffer, 10240, 0);
        send(clientSocket, "aa", 3, 0);
        FILE* ptr = fopen(file_name,"w");
        printf("opened\n");
        fwrite(buffer, 1, size, ptr);
        fclose(ptr);
        clearStr(buffer);
    }
    return 0;
}
