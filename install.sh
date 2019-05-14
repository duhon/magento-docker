#!/usr/bin/env bash

## LINUX ##
#requare
sudo apt update
sudo apt install htop
sudo apt install git
sudo apt install mc
sudo apt-get install apt-transport-https ca-certificates curl software-properties-common
#install docker
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
sudo apt-get update
sudo apt-get install docker-ce
sudo groupadd docker
sudo usermod -aG docker $USER
sudo curl -L https://github.com/docker/compose/releases/download/1.22.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

## MAC OS ##
#requare
/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
#install docker
brew cask install docker
#optimization for mac
#https://gist.github.com/tombigel/d503800a282fcadbee14b537735d202c

## WINDOWS ## PowerShell
Invoke-WebRequest "https://master.dockerproject.org/windows/x86_64/docker.zip" -OutFile "$env:TEMP\docker.zip" -UseBasicParsing
Expand-Archive -Path "$env:TEMP\docker.zip" -DestinationPath $env:ProgramFiles
# Add path to this PowerShell session immediately
$env:path += ";$env:ProgramFiles\Docker"
# For persistent use after a reboot
$Path = [Environment]::GetEnvironmentVariable("Path",[System.EnvironmentVariableTarget]::Machine)
[Environment]::SetEnvironmentVariable("Path", $Path + ";$env:ProgramFiles\Docker", [EnvironmentVariableTarget]::Machine)

#prepare data
mkdir ~/www
cd ~/www/
chown -R $USER:root .
chmod g+s .
git clone https://github.com/duhon/magento-docker.git
cp magento-docker/bundles/typical.yml magento-docker/docker-compose.yml
cp magento-docker/.env.dist magento-docker/.env
git clone https://github.com/magento/magento2ce.git
git clone https://github.com/magento/magento2ee.git
git clone https://github.com/magento/magento2b2b.git
