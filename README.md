Copyright (c) 2016, chali5124@gmail.com. All rights reserved.

# H5P Plugin in Laravel Framework 

## Installation

```bash
composer require chali5124/laravel-h5p
```

```bash
php artisan migrate
```


```bash
php artisan vendor:publish
```

```php
'classmap': [
    "vendor/h5p/h5p-core/h5p-default-storage.class.php",
    "vendor/h5p/h5p-core/h5p-development.class.php",
    "vendor/h5p/h5p-core/h5p-event-base.class.php",
    "vendor/h5p/h5p-core/h5p-file-storage.interface.php",
    "vendor/h5p/h5p-core/h5p.classes.php",
    "vendor/h5p/h5p-editor/h5peditor-ajax.class.php",
    "vendor/h5p/h5p-editor/h5peditor-ajax.interface.php",
    "vendor/h5p/h5p-editor/h5peditor-file.class.php",
    "vendor/h5p/h5p-editor/h5peditor-storage.interface.php",
    "vendor/h5p/h5p-editor/h5peditor.class.php"
],
```

```php
'providers' => [
    Chali5124\LaravelH5p\LaravelH5pServiceProvider::class,
];
```

```bash
cd public/vendor;
ln -s ../../storage/h5p/libraries 
```

On Development...