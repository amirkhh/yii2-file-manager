Yii2 File Manager
=================
Upload and Manage Your Uploaded File in Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist amirkh/yii2-file-manager "*"
```

or add

```
"amirkh/yii2-file-manager": "*"
```

to the require section of your `composer.json` file.

### Configure actions

For using file manager you must create and configure actions in  SiteController or any Controller you like. See complete example with actions for list and upload:

```php
<?php

namespace app\controllers;

use amirkhh\filemanager\actions\ListAction;
use amirkhh\filemanager\actions\UploadAction;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'file-list' => [
                'class' => ListAction::class,
            ],
            'file-upload' => [
                'class' => UploadAction::class,
            ]
        ];
    }
}
```

### Update your database schema
you need to do is updating your database schema by applying the migration:

```
$ php yii migrate/up --migrationPath=@vendor/amirkh/yii2-file-manager/migrations
```

### Call widget

Once the extension is installed, simply use it in your code by  :

```php
<?= \amirkhh\filemanager\FileManager::widget(['form' => $form]) ?>
```

