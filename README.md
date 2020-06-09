# magento-docker

`magento-docker` is Docker environment for easy to setup, configure, debug Magento2 + Storefront instance with varnish, elasticsearch, redis, rabbit and mftf tests.

### Requirements

* [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
* [Docker](https://docs.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/install/)

### How to install
0. Setup SSH-keys on your github account.
2. Add `magento.test` to `/etc/hosts`: `127.0.0.1 magento.test`.
3. cp .env.dist .env
4. Fill REPO-related variables in .env file
                                                    
        GIT_REPO_CE=magento
        GIT_BRANCH_CE=2.4-develop
        GIT_REPO_EE=magento-architects
        GIT_BRANCH_EE=2.4-develop
        GIT_REPO_B2B=magento
        GIT_BRANCH_B2B=1.2-develop
        GIT_REPO_MSI=magento
        GIT_BRANCH_MSI=1.2-develop
        GIT_REPO_CATALOG_SF=magento
        GIT_BRANCH_CATALOG_SF=develop
        GIT_REPO_CATALOG_SF_EE=magento
        GIT_BRANCH_CATALOG_SF_EE=develop
        GIT_REPO_SAAS_EXPORT=magento
        GIT_BRANCH_SAAS_EXPORT=develop
        GIT_REPO_DSSI=magento
        GIT_BRANCH_DSSI=develop
        GIT_REPO_MSC=magento-architects
        GIT_BRANCH_MSC=master
5. Fill EVN-specific variables in .env file:
        
        MAGENTO_PATH=/magento/magento-docker-install    # local directory to clone repos into
        RECLONE=no                                      # flag indicate whether do re-clon of all repos or no
        MAGENTO_EDITION=EE                              # EE|B2B
        MSI_INSTALL=no                                  # yes|no
        STOREFRONT_INSTALL=no                           # yes|no
6. Update Mutagen config with appropriate local project path: `alpha: /magento/magento-docker-install`  # local directory to clone repos into
7. Add MAGENTO_PATH path to Docker sharing folders (Docker preferences)
7. RUN `mutagen project start`

Notices:
1. Before push your code, please fetch particular repo before by the next command: `git fetch --unshallow` 

## Scenarios

### 1. Enter container
* Run `docker-compose exec app bash`.

### 2. Relaunch container
* Run `docker-compose scale <container_name>=0 && docker-compose scale <container_name>=1`. For example: `docker-compose scale app=0 && docker-compose scale app=1`.

### 3. Run tests

1. `docker-compose exec app magento prepare_tests`
2. `docker-compose exec app bin/magento dev:tests:run (unit, integration)`
3. `docker-compose exec app bash`
4. `cd dev/tests/acceptance/ and vendor/bin/codecept run (mftf)`
5. `cd dev/tests/functional/ and vendor/bin/phpunit run (mtf)`
6. `vnc://localhost:5900 pass:secret (mftf or mtf)`

### 4. Enable/disable Xdebug

* To enable xdebug, uncomment `xdebug.ini` line of `app` container in `docker-compose.yml` and run `docker-compose scale app=0 && docker-compose scale app=1`.
:warning: Enabled Xdebug may slow your environment. 

### 5. Magento (Re)-Installation

* `docker-compose exec app magento reinstall (ee|b2b)`

### 6. Optimization host

1. Redis optimization 
    ```
    docker run -it --rm --privileged ubuntu /bin/bash
    echo never | tee /sys/kernel/mm/transparent_hugepage/enabled
    echo never | tee /sys/kernel/mm/transparent_hugepage/defrag
    ```
2. [Optimization for MacOS](https://gist.github.com/tombigel/d503800a282fcadbee14b537735d202c)

### FAQ
1. If docker containers do not go up, check errors in console, run `docker-compose down`, fix issue and run `docker-compose up` again.
2. If `Overwrite the existing configuration for db-ssl-verify?[Y/n]` prompts in console, type `Y`.
3. If magento installation fails, run `docker-compose exec app magento reinstall`

### TODO list
https://github.com/duhon/magento-docker/projects
