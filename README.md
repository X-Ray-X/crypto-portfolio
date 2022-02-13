# Crypto Portfolio API

## Prerequisites:
* [Docker](https://docs.docker.com/get-docker/)

## Platform compatibility:
* macOS: ✅
* Linux: ❔
* Windows: ❌

## How to:

### Add the following entries to your /etc/hosts file:

> 127.0.0.1 crypto-portfolio.test \
> 127.0.0.1 phpma.test

### Run the command from the root catalog:

> // first installation with some additional steps: \
> make first-run \
> \
> // regular usage: \
> make up \
> make down

For more please check the contents of Makefile.

### Accessing Artisan Console from within the container:

> make artisan

### Generating API documentation:

> make build-docs

Once generated, the .html documentation file can be found in /docs/output folder.

### Testing:

> make test \
> make test-coverage

The .html test coverage report file can be found in /coverage/html folder.

You can also run static code analysis with [PHPStan](https://github.com/phpstan/phpstan):

> make phpstan

The default strictness level is set to 5. 

### PHPMyAdmin access:

The database admin panel should be accessible under the local domain [phpma.test](http://phpma.test/) with default credentials:

username: root \
password: P@ssw0rd!

### Using Xdebug with PHPStorm:

Make sure to match the project root folder path with an absolute path of the web server container which is '/var/www/html'. You can change this in <strong>Preferences > PHP > Debug > Servers</strong>.

After that, set a breakpoint in the code, click on the <strong>Start Listening to PHP Debug Connestions</strong> button and try sending a request to the API.

In case of any issues, please refer to the following sources:

* https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html
* https://matthewsetter.com/setup-step-debugging-php-xdebug3-docker/