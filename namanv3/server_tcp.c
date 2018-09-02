/* credit @Daniel Scocco */

/****************** SERVER CODE ****************/

#include <stdio.h>
#include <netinet/in.h>
#include <string.h>
#include <sys/socket.h>
#include <arpa/inet.h>

int welcomeSocket, newSocket;
char buffer[1024];
struct sockaddr_in serverAddr;
struct sockaddr_storage serverStorage;
socklen_t addr_size;

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

void clearStr (char* str) {
    for (int i = 0; str[i] != 0; i++) str[i] = 0;
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
        FILE* ptr = fopen(file_name,"r");
        fseek(ptr, 0L, SEEK_END);
        int num_chars = ftell(ptr);
        char contents[num_chars];
        clearStr(contents);
        fseek(ptr, 0L, SEEK_SET);
        fread(contents, sizeof(char), num_chars, ptr);
        fclose(ptr);
        int s = printf("%d\n", num_chars+1);
        char size[s]; tostring(size,num_chars+1);
        send(newSocket, size, s, 0);
        recv(newSocket, size, s, 0);
        send(newSocket, contents, num_chars + 1, 0);
        clearStr(size);
        recv(newSocket, size, s, 0);
        printf("\n");
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
    serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
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
