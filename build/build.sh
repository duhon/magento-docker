#!/bin/bash
set -ex
type=${1:-'fpm'}
ver=${2:-'7.4'}
mage=${3:-'2.4.2'}
docker build -f ./php/${type} -t duhon/php:${ver}-${type}-${mage} --compress --force-rm --pull .
docker run --rm --name test -it duhon/php:${ver}-${type} bash -c 'git clone -b '${mage}' --depth=1 --single-branch https://github.com/magento/magento2.git /var/www/magento2ce && composer update && php -v && php -m && php -d memory_limit=-1 /var/www/magento2ce/vendor/bin/phpunit -c /var/www/magento2ce/dev/tests/unit/phpunit.xml.dist'
echo "docker push duhon/php:${ver}-${type}-${mage}"

#todo: check hash for files nginx.conf.sample, php/tests/*

#cd ~/www
#shopt -s dotglob
#zip -r0 ../data.zip *
#docker run --rm -v ~/www/data.zip:/src/data.zip -v www:/data busybox unzip /src/data.zip -d /data
#docker run --rm  -v www:/var/www/magento2ce busybox ls -la /var/www/magento2ce/
