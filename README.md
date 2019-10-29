# magento-docker

### 0. Preconditions

- see install.sh
- cp magento-docker/bundles/typical.yml magento-docker/docker-compose.yml
- cp magento-docker/.env.dist magento-docker/.env 

## Scenarios

### 1. Run tests

0. docker-compose exec app magento prepare_tests
1. docker-compose exec app bin/magento dev:tests:run (unit, integration)
2. docker-compose exec app bash
3. cd dev/tests/acceptance/ and vendor/bin/codecept run (mftf)
4. cd dev/tests/functional/ and vendor/bin/phpunit run (mtf)
5. vnc://localhost:5900 pass:secret (mftf or mtf)

### 2. Xdebug

1. Uncomment the line in the docker-compose.yml which is the debugger

### 3. (Re)-Installation M2

1. docker-compose exec app magento reinstall (ee|ce|b2b)

### 4. Optimization host

1. Redis optimization 
    ```
    docker run -it --rm --privileged ubuntu /bin/bash
    echo never | tee /sys/kernel/mm/transparent_hugepage/enabled
    echo never | tee /sys/kernel/mm/transparent_hugepage/defrag
    ```
2. Optimization for MacOS https://gist.github.com/tombigel/d503800a282fcadbee14b537735d202c


### TODO list

1. To create a cross platform installer that will check for dependencies, create a folder with the project and 
download the Magento (see install.sh)
2. The default creation of the project for phpstorm (see etc/phpstorm)
3. A single point of running tests with the preparation (magento prepare_tests) of Magento