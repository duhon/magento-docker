# magento-docker

`magento-docker` is Docker environment for easy to setup, configure, debug Magento2 + Storefront instance with varnish, elasticsearch, redis, rabbit and mftf tests.

### Requirements

* [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
* [Docker](https://docs.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/install/)

### How to install
#### Requirements
0. Docker and docker-compose is [installed](https://docs.docker.com/compose/install/)
1. Setup SSH-keys on your github account. (see [docs](https://help.github.com/en/github/authenticating-to-github/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent)  for [help](https://help.github.com/en/github/authenticating-to-github/adding-a-new-ssh-key-to-your-github-account))

2. Install Mutagen [docs](https://mutagen.io/documentation/introduction/installation)

#### Steps
1. Create directory where all repositories will be cloned (used in your IDE)

2. Update MAGENTO_PATH in .env with recently created directory path

3. RUN `mutagen project start`

#### Configuration

    MAGENTO_PATH=/magento/magento-docker-install    # local directory to clone repos into
    RECLONE=no                                      # flag indicate whether do re-clon of all repos or no
    MAGENTO_EDITION=EE                              # EE|B2B
    MSI_INSTALL=no                                  # yes|no
    STOREFRONT_INSTALL=no                           # yes|no
    Notices:

#### Troubleshooting
   1. Add MAGENTO_PATH path to Docker sharing folders (Docker preferences) in case docker-error


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
