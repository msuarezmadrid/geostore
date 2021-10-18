#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install git -yqq
apt-get install wget -yqq
apt-get install zlib1g-dev -yqq
apt-get install unzip -yqq
#apt-get install curl -yqq

# Install phpunit, the tool that we will use for testing
#curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
#chmod +x /usr/local/bin/phpunit

# Install mysql driver
# Here you can install any other extension that you need
apt-get install -y libgmp-dev re2c libmhash-dev libmcrypt-dev file
ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/
docker-php-ext-configure gmp
docker-php-ext-install pdo_mysql
docker-php-ext-install gmp
#docker-php-ext-install zip