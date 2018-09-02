#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define N 1024
#define M 30
#define OBJ 4

char buffer[N];
int len[OBJ];
int count[OBJ];
char file_name[OBJ][M], names[OBJ][M];
int total_count;
char req_files[M][M];


// cat is code 0, dog 1, car 2 truck 3;

void init() {
    len[0] = len[1] = len[2] = 3; len[3] = 5;
    total_count = 0;
    strcpy(names[0], "cat");
    strcpy(file_name[0], "cat_0.jpg");
    strcpy(names[1], "dog");
    strcpy(file_name[1], "dog_0.jpg");
    strcpy(names[2], "car");
    strcpy(file_name[2], "car_0.jpg");
    strcpy(names[3], "truck");
    strcpy(file_name[3], "truck_0.jpg");

    memset(count, 0, sizeof(count));
}

int identify_type(int q) {
    for(int i = 0;i < 4;i++) {
        int valid = 1;
        for(int j = 0;j < len[i];j++) 
            if(buffer[q + j] != names[i][j])
                valid = 0;
        if(valid == 1)
            return i;
    }
    return -1;
}

void get_count() {
    int x, idx = 0;
    x = buffer[idx] - '0';
    idx += 2;
    int type = identify_type(idx);
    while(type != -1) {
        count[type] += x;
        idx += (len[type] + 1);
        x = buffer[idx] - '0';
        idx += 2;
        type = identify_type(idx);
    }
}

void get_file_names() {
    for(int i = 0;i < OBJ;i++) 
        for(int j = 1;j <= count[i];j++) {
            file_name[i][len[i] + 1] = j + '0';
            strcpy(req_files[total_count++], file_name[i]);
        }
}

int main() {
    init();
    scanf ("%[^\n]%*c", buffer);
    get_count();
    get_file_names();
    
    for(int i = 0;i < OBJ;i++) {
        printf("i = %d, count = %d\n", i, count[i]);
    }

    for(int i = 0;i < total_count;i++)
        printf("%s\n", req_files[i]);

    return 0;
}