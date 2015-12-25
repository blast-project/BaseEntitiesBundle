# SymfonyLibrinfoBaseEntitiesBundle

## Installation

### Prequiresites

* having a working Symfony2 environment
* having created a working Symfony2 app (including your DB and your DB link)
* having composer installed (here in ```/usr/local/bin/composer```, having ```/usr/local/bin``` in your path)

### Downloading

```
  $ composer require libre-informatique/base-entities-bundle dev-master
```

Going further :

* [Base Entities Management](Resources/doc/base_entities_management.md)
    * Explains how BaseEntities behaviors are implemented into Symfony 2.
=======
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

Learn how to use the ```libre-informatique/base-entities-bundle```
==================================================================

Doctrine Behaviors provided by the bundle
-----------------------------------------

Learn how to use them, how they work, and by extension learn how to create new behaviors shaped to your needs, [reading the specific documentation](Resources/doc/base_entities_management.md).

Specific Form Types
-------------------

The bundle provides some form types, learn more about this, [reading the specific documentation](Resources/doc/README-FormTypes.md).
