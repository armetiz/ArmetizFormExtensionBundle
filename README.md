ArmetizFormExtensionBundle
==========================

This bundle add a new Form type named "entity_ajax". 

It looks like the default "entity" type, but it loads only associated entities.

#### Example
A Book is link to User throught "owner" property.

If you are using the "entity" type on the BookType to display the "owner" property.
The form will load all the "users" to render the page.

With the "entity_ajax" type, the form will load only the current "owner". In this case, you are free 
to load further "users" via AJAX or something else.

## Installation

Installation is a quick 2 step process:

1. Download ArmetizFormExtensionBundle using composer
2. Enable the Bundle

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
```


## Usage
You just have to use "entity_ajax" type instead of "entity". All [EntityType][1] options are still availabled and/or needed.

I'm using it with [Chosen][2] & [Ajax Chosen][3].

``` php
// src/Acme/TaskBundle/Controller/DefaultController.php
namespace Acme\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\TaskBundle\Entity\Task;

class DefaultController extends Controller
{
    public function newAction(Request $request)
    {
        $task = new Task();

        $form = $this->createFormBuilder($task)
            ->add('task', 'text')
            ->add('dueDate', 'date')
            ->add('owner', 'entity_ajax')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();

                return $this->redirect($this->generateUrl('task_success'));
            }
        }

        return $this->render('AcmeTaskBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
```

``` js
// part of edit.html.twig file
var ajaxChosenSimplifier = function(selector, url, label) {
    var options = {
        method: 'GET',
        url: url,
        data: {
            method: "search"
        },
        jsonTermKey: "value",
        dataType: 'xml'
    };

    var success = function(data, textStatus, jqXHR) {
        var jSearched = $(data);
        var result = {};

        jQuery.each(jSearched.find("item"), function(indexInArray, item) {
            var jItem = $(item);
            var id = jItem.find("id").text();
            var text = jItem.find(label).text();

            result[id] = text;
        });

        return result;
    };

    return $(selector).ajaxChosen(options, success);
};

ajaxChosenSimplifier("#task_owner", "http://api.domain.tld/user, "username");
```

``` xml
<!-- data content example -->
<response status="success" message="user.search">
    <item key="0" id="3" type="user">
        <id>3</id>
        <username><![CDATA[ john ]]></username>
    </item>
    <item key="0" id="4" type="user">
        <id>4</id>
        <username><![CDATA[ iron man ]]></username>
    </item>
</response>
```

[1]: http://symfony.com/doc/2.1/reference/forms/types/entity.html
[2]: http://harvesthq.github.com/chosen/
[3]: https://github.com/meltingice/ajax-chosen 