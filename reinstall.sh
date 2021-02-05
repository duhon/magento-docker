#!/usr/bin/env bash

set -ex

source ./.env
export $(cut -s -d= -f1 ./.env)

DOCKER_PROJECT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

if [ ! -f ./etc/php/auth.json ]; then
    echo "ERROR: create ./etc/php/auth.json first"
    exit
fi

cd $MAGENTO_PATH/magento2ce

if [ ${MUTAGEN_INSTALLATION} == "YES" ] && [[ -d .git ]]; then
    git clean -dxf -e '.idea' -e 'vendor' -e 'magento2*'
    git reset --hard
    git apply $DOCKER_PROJECT_DIR/etc/php/path-validator.patch
fi

cd $DOCKER_PROJECT_DIR

# monolith installation
docker-compose exec app magento prepare_monolith_installation
docker-compose exec app magento reinstall_monolith ${INSTALLED_REPOS}
docker-compose exec app magento config_setup
docker-compose exec app magento storefront

if [ ${MONOLITHIC_INSTALLATION} == "YES" ]; then
   docker-compose exec app magento grpc &
fi

# standalone installations
if [ ${MONOLITHIC_INSTALLATION} == "NO" ]; then

    # force copy auth.json to each service
    for edition in $INSTALLED_REPOS; do
        rm -rf $MAGENTO_PATH/$edition/auth.json
        cp ./etc/php/auth.json $MAGENTO_PATH/$edition/auth.json
    done


    if [[ $INSTALLED_REPOS == *"storefront-message-broker"* ]]; then
        docker-compose exec app-message-broker magento reinstall_message_broker
    fi

    if [[ $INSTALLED_REPOS == *"catalog-storefront"* ]]; then
        docker-compose -f bundles/catalog.yml -d
        docker-compose exec app-catalog-storefront magento reinstall_catalog_storefront
        # start gRPC server on standalone application
        docker-compose exec app-catalog-storefront magento grpc
    fi

    if [[ $INSTALLED_REPOS == *"storefront-product-reviews"* ]]; then
        docker-compose -f bundles/product-review.yml -d
        docker-compose exec app-product-reviews magento reinstall_storefront_review
    fi

    if [[ $INSTALLED_REPOS == *"storefront-pricing-ce"* ]]; then
        docker-compose -f bundles/pricing -d
        docker-compose exec app-pricing magento reinstall_storefront_pricing
    fi

    if [[ $INSTALLED_REPOS == *"storefront-search-ce"* ]]; then
        docker-compose -f bundles/search -d
        docker-compose exec app-search magento reinstall_storefront_search
    fi
fi

docker-compose stop redis
echo y | docker-compose rm redis
docker-compose up -d redis

 docker-compose \
    -f bundles/storefront.yml\
    -f bundles/message-broker.yml \
    -f bundles/catalog.yml \
    -f bundles/search.yml \
    -f bundles/product-review.yml \
    -f bundles/pricing.yml  \
 logs -f
