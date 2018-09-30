# Assignment 2

## PHP_MYSQL 




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
