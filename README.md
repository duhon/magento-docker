# magento-docker

### 0. Preconditions with default .env

```
mkdir ~/www
cd ~/www
git clone git@github.com:magento/magento2.git
git clone git@github.com:duhon/magento-docker.git
cd magento-docker
docker-compose -f docker-compose(-apple?).yml up
```


## Scenarios

### 1. Run sshd
 
1. docker-compose exec app /usr/sbin/sshd
2. ssh root@magento.test -p 222
3. Password - root

Done.

### 2. Run MFTF

1. docker-compose -f bundles/test.yml up
2. docker-compose exec app magento reinstall (ee|ce|b2b) - need installed M2 (see Instalation M2).
3. docker-compose exec app magento prepare_tests
4. docker-compose exec app bash
5. cd dev/tests/acceptance/
6. vendor/bin/codecept run

Done.

### 3. (Re)-Installation M2

1. docker-compose exec app magento reinstall (ee|ce|b2b)

### TODO list

1. It is impossible to run multiple instances of the same service, problem in the ports
