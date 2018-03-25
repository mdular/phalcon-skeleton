## Requirements

php 7.2
- php7.2-pgsql
- php7.2-apcu
- php7.2-mbstring

phalcon 3.3

postgresql 9.6+

composer

### Install composer dependencies

    php composer.phar install

## Running the development server

    php vendor/bin/phalcon.php serve --basepath=.htrouter.php

    DEBUG=true php vendor/bin/phalcon.php serve --basepath=.htrouter.php

Now you can access the project via [http://localhost:8000/](http://localhost:8000/)
