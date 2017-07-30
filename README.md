##Web-Scraper

This project is a simple application which takes a URL as input
and returns some information about it including:

* The amount of links on the page.
* The unique domains of those links.
* Whether Google Analytics was included on the page.
* Whether the request was deemed `secure`.

A website request was deemed secure if the request was carried
out over the HTTPS protocol.

####Stack used
* PHP 7.0
* Slim Framework 3.8
* Twig 2.4
* PHPUnit 6.3

####Running the project

I have provided two ways to run the project.

#####Docker:
The project can be containerised into a docker container which uses alpine image.

To run on docker, execute:
```
cd /path/to/repo
docker build -t webscraper:latest .
docker run -d -p 8080:80 webscraper:latest
```

Then nagivate to http://localhost:8080

#####Vagrant
The project can also be provisioned using Vagrant.

This creates an ubuntu VM which internally runs docker to run the image.

To run using Vagrant, execute:
```
cd /path/to/repo
vagrant up
```

Then navigate to: http://10.55.101.10

Currently this setup only supports VirtualBox as its provider.
