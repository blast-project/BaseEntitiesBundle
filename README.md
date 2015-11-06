# SymfonyLibrinfoBaseEntitiesBundle

This bundle provides base behaviors for Doctrine Entities in Libre Informatique Symfony2 projects.

Installation
============

Prequiresites
-------------

- having a working Symfony2 environment
- having created a working Symfony2 app (including your DB and your DB link)
- having composer installed (here in /usr/local/bin/composer, with /usr/local/bin in the path)

Downloading
-----------

  $ composer require libre-informatique/base-entities-bundle dev-master

Adding the bundle in your app
-----------------------------

Edit your app/AppKernel.php file and add your "libre-informatique/base-entities-bundle" :

```php
    // app/AppKernel.php
    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...

            // The libre-informatique bundles
            new Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new Librinfo\BaseEntitiesBundle\LibrinfoBaseEntitiesBundle(),

            // your personal bundles
        );
    }
```
