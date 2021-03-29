#!/usr/bin/env bash

set -ex

source ./.env
export $(cut -s -d= -f1 ./.env)

DOCKER_PROJECT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

if [ ! -f ./etc/php/auth.json ]; then
    echo "ERROR: create ./etc/php/auth.json first"
    exit
fi

cd $DOCKER_PROJECT_DIR



# composer based installation
docker-compose -f bundles/monolith.yml exec app magento reinstall_monolith ${INSTALLED_REPOS}
docker-compose -f bundles/monolith.yml exec app magento config_setup
docker-compose -f bundles/monolith.yml exec app bin/magento -f setup:static-content:deploy
docker-compose -f bundles/monolith.yml exec app bin/magento module:disable Magento_TwoFactorAuth
docker-compose -f bundles/monolith.yml exec app rm -rf generated/
