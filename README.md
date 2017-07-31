## Web-Scraper

This project is a simple application which takes a URL as input
and returns some information about it including:

* The amount of links on the page.
* The unique domains of those links.
* Whether Google Analytics was included on the page.
* Whether the request was deemed `secure`.

A website request was deemed secure if the request was carried
out over the HTTPS protocol.

#### Stack used
* PHP 7.0
* Slim Framework 3.8
* Twig 2.4
* PHPUnit 6.3

#### Running the project

I have provided two ways to run the project.

##### Docker:
The project can be containerised into a docker container which uses alpine image.

To run on docker, execute:
```
cd /path/to/repo
docker build -t webscraper:latest .
docker run -d -p 8080:80 --name scraper webscraper:latest
```

Then nagivate to http://localhost:8080

##### Vagrant
The project can also be provisioned using Vagrant.

This creates an ubuntu VM which internally runs docker to run the image.

To run using Vagrant, execute:
```
cd /path/to/repo
vagrant up
```

Then navigate to: http://10.55.101.10

Currently this setup only supports VirtualBox as its provider.

#### Testing

You can run unit tests by running the command:
```
cd /path/to/repo
vendor/bin/phpunit -c phpunit.xml --stderr
```

There are filter commands for each test in the test suite for running individual tests.

##### Code Coverage

The code has got 100% test coverage. 

You can run the code-coverage command with:
```
cd /path/to/repo
vendor/bin/phpunit -c phpunit.xml --stderr --coverage-html /path/to/coverage/files
```

To run code coverage tests a coverage driver has to be installed on your local machine.
The typical driver which is used is Xdebug.

Tests can either be ran locally, or you can run them on the currently executing
docker image, or on the vagrant image:

##### Docker

Tests can be ran on the current docker image with the command:
```
docker exec scraper php /data/web-scraper/vendor/bin/phpunit -c /data/web-scraper/phpunit.xml --stderr
```

##### Vagrant
Tests can also be ran on the vagrant VM box, with the command:
```
cd /path/to/repo
vagrant ssh
cd /data
vendor/bin/phpunit -c phpunit.xml --stderr
```
