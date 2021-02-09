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

# reinit docker container state
if [ ${MONOLITHIC_INSTALLATION} == "YES" ]; then
    docker-compose -f bundles/monolith.yml up -d
else
    docker-compose \
    -f bundles/monolith.yml \
    -f bundles/message-broker.yml \
    -f bundles/catalog.yml \
    -f bundles/search.yml \
    -f bundles/product-review.yml \
    -f bundles/pricing.yml  \
    up -d
fi


# monolith installation with Commerce Data export
docker-compose -f bundles/monolith.yml exec app magento prepare_monolith_installation
docker-compose -f bundles/monolith.yml exec app magento reinstall_monolith ${INSTALLED_REPOS}
docker-compose -f bundles/monolith.yml exec app magento config_setup
docker-compose -f bundles/monolith.yml exec app magento storefront

if [ ${MONOLITHIC_INSTALLATION} == "YES" ]; then
   docker-compose -f bundles/monolith.yml exec app magento grpc &
fi

# standalone installations
if [ ${MONOLITHIC_INSTALLATION} == "NO" ]; then

    # force copy auth.json to each service
    for edition in $INSTALLED_REPOS; do
        rm -rf $MAGENTO_PATH/$edition/auth.json
        cp ./etc/php/auth.json $MAGENTO_PATH/$edition/auth.json
    done


    if [[ $INSTALLED_REPOS == *"storefront-message-broker"* ]]; then
        docker-compose -f bundles/message-broker.yml -f bundles/monolith.yml exec app-message-broker magento reinstall_message_broker
    fi

    if [[ $INSTALLED_REPOS == *"catalog-storefront"* ]]; then
        docker-compose -f bundles/catalog.yml -f bundles/monolith.yml exec app-catalog-storefront magento reinstall_catalog_storefront
        # start gRPC server on standalone application
        docker-compose -f bundles/catalog.yml -f bundles/monolith.yml exec app-catalog-storefront magento grpc &
    fi

    if [[ $INSTALLED_REPOS == *"storefront-product-reviews"* ]]; then
        docker-compose -f bundles/product-review.yml -f bundles/monolith.yml exec app-product-reviews magento reinstall_storefront_review
        docker-compose -f bundles/product-review.yml -f bundles/monolith.yml exec app-product-reviews magento grpc &
    fi

    if [[ $INSTALLED_REPOS == *"storefront-pricing-ce"* ]]; then
        docker-compose -f bundles/pricing.yml -f bundles/monolith.yml exec app-pricing magento reinstall_storefront_pricing
        docker-compose -f bundles/pricing.yml -f bundles/monolith.yml exec app-pricing magento grpc &
    fi

    if [[ $INSTALLED_REPOS == *"storefront-search-ce"* ]]; then
        docker-compose -f bundles/search.yml -f bundles/monolith.yml exec app-search magento reinstall_storefront_search
        docker-compose -f bundles/search.yml -f bundles/monolith.yml exec app-search magento grpc &
    fi
fi

docker-compose stop redis
echo y | docker-compose rm redis
docker-compose up -d redis

 docker-compose \
    -f bundles/monolith.yml\
    -f bundles/message-broker.yml \
    -f bundles/catalog.yml \
    -f bundles/search.yml \
    -f bundles/product-review.yml \
    -f bundles/pricing.yml  \
 logs -f
