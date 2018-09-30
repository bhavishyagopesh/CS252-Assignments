# Assignment 2

## PHP_MYSQL 

# Pull code from github and Change directory to Assignment2

```
docker pull bhavishya/mysql
cd PHP_MYSQL
docker run -it -p 8080:80 -v  bhavishya/mysql  bash

#inside container 
service mysql start
service apache2 start

# Go to localhost:8080 to see the running website
```


## PHP_MONGODB

```

# Pull code from github and Change directory to Assignment2

docker pull bhavishya/mongodb
cd PHP_MONGODB
docker run -it -p 8000:8000 -v $(pwd):/web bhavishya/mongodb

# Now exec it to the running container and start mongod 
docker exec -it <container_id> bash
#inside container 
mongod 

# Go to localhost:8000 to see the running website

``` 
