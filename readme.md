## Installation

Via Composer

``` bash
composer config repositories.eval4victorycto/laravel-image-uploader vcs https://github.com/anconcsio/laravel-image-uploader
composer require eval4victorycto/laravel-image-uploader
```

Put credentials in `.env` variables:

``` 
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1
```

Run migrations:

``` bash
php artisan migrate
```

Publish it to make changes (if required) to the package config at `config/image-uploader.php`

``` bash
php artisan vendor:publish --provider="Eval4VictoryCTO\LaravelImageUploader\ServiceProvider"
```

## Using

Run database worker:

``` bash
php artisan queue:work database --queue=image-uploader 
```

Or change the value of the config key `image-uploader.queue-connection` to `sync`

Visit `/image-uploader` url