# magento-docker

`magento-docker` is Docker environment for easy to setup, configure, debug Magento2 + Storefront instance with varnish, elasticsearch, redis, rabbit and mftf tests.

### Requirements

* [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
* [Docker](https://docs.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* Setup SSH-keys on your github account. (see [docs](https://help.github.com/en/github/authenticating-to-github/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent)  for [help](https://help.github.com/en/github/authenticating-to-github/adding-a-new-ssh-key-to-your-github-account))

* Install Mutagen [docs](https://mutagen.io/documentation/introduction/installation)
* Ensure you do not have `dnsmasq` installed/enabled locally (will be auto-installed if you've use Valet+ to install Magento)


### How to install

#### Steps
1. Create directory where all repositories will be cloned (used in your IDE)
 
    Proposed structure:
```
    ~/projects/storefront/magento-docker    # this repo
    ~/projects/storefront/repos             # directory with repositories
```

2. Update MAGENTO_PATH in .env with recently created directory path

3. Add $MAGENTO_DOMAIN from .env to hosts, e.g.:

```
    sudo -- sh -c "echo '127.0.0.1 magento.test >> /etc/hosts"
```

4. RUN `mutagen project start`

Note, for the first installation (when you don't have cloned repositories yes) please change settings "RECLONE" to "yes" in ".env" file
Please, aware that with "RECLONE=yes" options all data from "$MAGENTO_PATH" will be deleted
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

* Enable: `mutagen project run xdebug-enable`
* Disable: `mutagen project run xdebug-disable`


:warning: Enabled Xdebug may slow your environment. 

### 5. Magento (Re)-Installation

* `mutagen project run reinstall`

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

### gRPC set up:
As currently we don't have ability to generate Storefront API on fly - we put magento.proto file to the root of catalog-storefront:develop branch.
It's needed to run gRPC server and client.
#### Steps for manual setup
1. PHP should be built with "grpc" extension
   - `pecl install grpc`: see `./build/php/fpm-grpc`
2. the following packages should be installed (see `./etc/php/tools/grpc`)
   - gRPC server *rr-grpc* (https://github.com/spiral/php-grpc/releases/download/v1.4.0/rr-grpc-1.4.0-linux-amd64.tar.gz)
3. run magento CLI command `bin/magento storefront:grpc:init \\Magento\\CatalogStorefrontApi\\Api\\CatalogProxyServer` 
4. gRPC server can be executed now: `./vendor/bin/grpc-server`
 
#### Automated setup
1. Navigate to `magento-docker/init_project` and change `mutagen.yml` to `mutagen-grpc.yml` in the following string:
`sed -ie "s|alpha: .*|alpha: \"$MAGENTO_PATH\"|" $DOCKER_PROJECT_DIR/mutagen.yml`
--->
`sed -ie "s|alpha: .*|alpha: \"$MAGENTO_PATH\"|" $DOCKER_PROJECT_DIR/mutagen-grpc.yml`

2. Run `mutagen project start --project-file mutagen-grpc.yml` command to build and set up docker containers, link code and install Magento.

3. Run `mutagen project run grpc-server-start --project-file mutagen-grpc.yml` command to execute `etc/php/tools/grpc` script which does the following:
   - Downloads gRPC server (`rr-grpc` binary file) and puts it to the `/usr/bin` directory (if it not installed yet)
   - Run magento CLI command `bin/magento storefront:grpc:init \\Magento\\CatalogStorefrontApi\\Api\\CatalogProxyServer` that does the following: 
        - copies certain files to `vendor/bin` (if they don't exist)
        - creates list of gRPC services and puts it to `./generated/code/grpc_services_map.php` file (if not created yet)
   - Runs gRPC server via executing of ./vendor/bin/grpc-server
   - Please NOTE: Port **9001** should be opened to allow external connections to the server.

4. Run gRPC client (can be executed from any instance which has access to **app:9001**):
   - Uncomment following code in docker-compose.yml:
     ```yaml
        grpcui:
          image: wongnai/grpcui
          ports:
            - "8080:8080"
          volumes:
            - code:/var/www
          entrypoint: ["grpcui", "-plaintext", "-proto", "/var/www/magento2ce/magento.proto", "-port", "8080", "-bind", "0.0.0.0", "-import-path", "/var/www/magento2ce", "app:9001"]
     ```
   - Make sure that paths to Magento root in entry point are correct (**/var/www/magento2ce**) - if no, replace them by correct paths.
   - Port **8080** should be opened to allow external connections to the client.
   - Run grpcui container: `docker-compose up grpcui`

### Q/A
 1. Unable to start `grpcui`.
 
 If project uses symlinks then `magento.proto` in root folder will not work correctly with `grpcui`. The following errors can occur:
    ```
   Attaching to storefront_grpcui_1
   grpcui_1   | Failed to process proto source files.: /var/www/magento2ce/magento.proto does not reside in any import path
   storefront_grpcui_1 exited with code 1
    ```
    Connect to project, unlink proto and copy from `catalog-storefront`, then start container again:
     - `unlink magento.proto`
     - `cp ../catalog-storefront/magento.proto magento.proto`
