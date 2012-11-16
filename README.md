ArmetizFormExtensionBundle
==========================

## Installation

Installation is a quick 2 step process:

1. Download ArmetizFormExtensionBundle using composer
2. Enable the Bundle
3. Configure your application's config.yml

### Step 1: Download ArmetizFormExtensionBundle using composer

Add ArmetizFormExtensionBundle in your composer.json:

```js
{
    "require": {
        "armetiz/form-extension-bundle": "1.x-dev"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update armetiz/form-extension-bundle
```

Composer will install the bundle to your project's `vendor/armetiz` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Armetiz\FormExtensionBundle\ArmetizFormExtensionBundle(),
    );
}


## Usage
You just have to use "entity_ajax" type instead of "entity". All [EntityType][1] options are still availabled and/or needed.

[1]: http://symfony.com/doc/2.0/reference/forms/types/entity.html