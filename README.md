Copyright (c) 2016, chali5124@gmail.com. All rights reserved.

# H5P Plugin in Laravel Framework 

## Installation

Begin by pulling in the package through Composer.

```bash
composer require chali5124/laravel-h5p
```

Next, include the service provider within your `config/app.php` file.

```php
'providers' => [
    Chali5124\LaravelH5p\LaravelH5pServiceProvider::class,
];
```


On Development...