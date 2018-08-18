#Instructions

```
docker run -dit --name tecmint-web -p 8080:80 -v /home/.../website/:/usr/local/apache2/htdocs/ httpd:2.4

For ssh -> https://github.com/rastasheep/ubuntu-sshd

Do this:
```
docker run -d -P --name test_sshd eg_sshd
docker port test_sshd 22
ssh root@localhost -p *port*
# password is `root`

```

For permission management -> https://docs.oracle.com/cd/E18752_01/html/816-4557/secfile-60.html



```
