# SymfonyLibrinfoBaseEntitiesBundle

This bundle provides some tools for a better integration of
[LibrinfoDoctrineBundle](https://github.com/libre-informatique/SymfonyLibrinfoDoctrineBundle)
behaviours in
[Sonata Admin](https://sonata-project.org/bundles/admin/master/doc/index.html)

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
            new Librinfo\BaseEntitiesBundle\LibrinfoCoreBundle(),
            new Librinfo\BaseEntitiesBundle\LibrinfoDoctrineBundle(),
            new Librinfo\BaseEntitiesBundle\LibrinfoBaseEntitiesBundle(),

            // your personal bundles
        );
    }
```

Learn how to use the ```libre-informatique/base-entities-bundle```
==================================================================

Specific Form Types
-------------------

The bundle provides some form types, learn more about this, [reading the specific documentation](Resources/doc/README-FormTypes.md).
