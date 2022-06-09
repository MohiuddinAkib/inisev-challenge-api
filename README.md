to run the project. First go to the project directory and then on your terminal run:

```
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
```

After installing all the dependecies run:

```
    ./vendor/bin/sail up
```

To run seeder:

```
    ./vendor/bin/sail artisan db:seed --class WebsiteSeeder
```

To list available endpoints:

```
    ./vendor/bin/sail artisan r:l --path=websites
```

To subscribe to a website the payload should contain:

```
{
    email: string;
}
```

To create a post for a website the payload should contain:

```
{
    title: string;
    op_email: string;
    description: string;
}
```

After creating a post to get the email notification on terminal run:

```
./vendor/bin/sail queue:work
```

Then visit http://localhost:8025 to see the email.

To run the feature tests:

```
./vendor/bin/sail test
```
