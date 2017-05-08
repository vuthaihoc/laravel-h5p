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
On Development...