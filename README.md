to run the project. First go to the project directory and then on your terminal run:

```
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs

    ./vendor/bin/sail up
```
