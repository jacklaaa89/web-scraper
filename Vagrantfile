# -*- mode: ruby -*-
# vi: set ft=ruby :
ip = "10.55.101.10"
Vagrant.configure("2") do |config|
  config.vm.provider "virtualbox" do |v|
    v.gui = false #Run in headless mode, use vagrant ssh to login to the machine.
    v.name = "DevBox"
    v.memory = 2048
    v.cpus = 2
  end
  config.vm.hostname = "devbox"
  config.vm.box = "ubuntu/xenial64"
  config.vm.network "private_network", ip: ip
  config.vm.network "public_network"
  #TODO - Mount options need adjusting to be more realistic.
  config.vm.synced_folder ".", "/data", group: "www-data", mount_options: ["dmode=777,fmode=777"]
  #Provision the machine, NSQ runs in docker as its a lot easier to provision.
  config.vm.provision "shell", env: {"IP_ADDRESS" => ip}, privileged: true, inline: <<-SHELL
    apt-get install -y apt-transport-https ca-certificates software-properties-common curl
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | apt-key add -
    apt-key fingerprint 0EBFCD88
    add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
    apt-get update
    apt-get install -y php php7.0-zip unzip zip php7.0-intl php7.0-curl curl php7.0-mbstring docker-ce
    service docker restart
    usermod -aG docker ubuntu && newgrp docker && newgrp ubuntu && docker info
    curl -L https://github.com/docker/compose/releases/download/1.12.0/docker-compose-`uname -s`-`uname -m` > /usr/bin/docker-compose
    chmod +x /usr/bin/docker-compose
    cd /data
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer
    echo "Use 'vagrant ssh' to login to the machine."
  SHELL

  #Run composer install not at root.
  #This script also sets up ssh on a read-only key to bitbucket to pull the relevant
  #repositories for the outside sources.
  config.vm.provision "shell", privileged: false, run: "always", inline: <<-SHELL
      cd /data && rm -rf composer.lock && composer install
  SHELL

  #Builds the application in docker and runs using docker-compose
  config.vm.provision "shell", privileged: true, run: "always", inline: <<-SHELL
    cd /data && docker build -t web-scraper:latest --build-arg ENVIRONMENT=DEV .
    cd /data/vagrant && docker-compose down && docker-compose up -d
  SHELL
end
