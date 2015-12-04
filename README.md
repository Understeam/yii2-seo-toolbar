# Yii2 Seo Toolbar

This toolbar allows to easily configure title of page, meta tags or Open Graph tags on your website.
It doesn't require any administration panel, it works right in the place (as Yii2 Debug Toolbar).

## Installation

```
$ composer require understeam/yii2-seo-toolbar:0.2 --prefer-dist
```

## Usage

At first, add toolbar to modules configuration and add it into application bootstrap:

```php
...
'bootstrap' => ['seoToolbar'],
'modules' => [
    'seoToolbar' => [
        'class' => 'understeam\seotoolbar\Module',
        'permission' => 'seo', // permission to check
        'allowedIPs' => ['*'],
    ],
],
...
```

It is strongly recommended to use name `seoToobar` because it is hardcoded (yet).

## Access control

At this step toolbar should appear if you are logged in and you have permission `seo`. Basically, module
checks permissions with this method:

```php
Yii::$app->user->can('seo');
```

So, if you are not using RBAC and access checks you can make toolbar visible for any logged in user:

```php
...
'bootstrap' => ['seoToolbar'],
'modules' => [
    'seoToolbar' => [
        'class' => 'understeam\seotoolbar\Module',
        'permission' => '@', // allow to any logged in user
        'allowedIPs' => ['*'],
    ],
],
...
```

Also, you may want to show toolbar ALL users and check permissions via IP address:

```php
...
'bootstrap' => ['seoToolbar'],
'modules' => [
    'seoToolbar' => [
        'class' => 'understeam\seotoolbar\Module',
        'permission' => null, // allow to any logged in user
        'allowedIPs' => ['127.0.0.1', '192.168.0.1'],
    ],
],
...
```

## Url patterns and model attributes

There is possibility to use a star (`*`) symbol to transform URL to regular expression:

![Screenshot](http://dn.imagy.me/201512/04/ca661bdc08c248902c217a9feaa9effd.png)

## Using SeoEntity behavior

Use SeoEntity class as a behavior for your models which attributes you want to use as a parameters in
seo toolbar:

```php
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'seo' => [
                'class' => 'understeam\seotoolbar\behaviors\SeoEntity',
                'attributes' => [
                    'name',
                    'slug',
                    'description'
                ],
            ],
        ]);
    }
```

![Screenshot](http://dn.imagy.me/201512/04/1a6effdf4220b1e4347fe986172f500e.png)
