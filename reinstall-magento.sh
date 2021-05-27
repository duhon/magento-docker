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
docker-compose exec app magento reinstall_monolith
docker-compose exec app magento config_setup
