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
### Configuring the bundle

Activate the listeners you need in your application  (add those lines to ```app/config/config.yml```) :

```yml
# Enable LibrinfoDocrineBundle event listeners
librinfo_doctrine:
    orm:
        default:
            naming: true
            guidable: true
            traceable: true
            addressable: true
            treeable: true
            nameable: true
            labelable: true
            emailable: true
            descriptible: true
            searchable: true
            loggable: true
```

Add/remove the needed behaviours for each orm connection used by your application.

## Learn how to use the bundle

### Doctrine Behaviors provided by the bundle

- naming: provides a database table naming based on your vendor / bundle / entity
- guidable: provides GUID primary keys to your entities
- traceable: provides createdAt / updatedAt fields and doctrine listeners to your entities
- addressable: provides address fields to your entities (address, city, zip, country...)
- treeable: provides tree structure to your entities
- nameable: provides a name field to your entities
- labelable: provides a label field to your entities
- emailable: provides email related fields to your entities
- descriptible: provides a description field to your entities
- searchable: provides a search index based on a selection of fields
- loggable:  tracks your entities changes and is able to manage versions

Learn how to use them, how they work, and by extension learn how to create new behaviors shaped to your needs, [reading the specific documentation](Resources/doc/base_entities_management.md).

Learn how to use the ```libre-informatique/base-entities-bundle```
==================================================================

Specific Form Types
-------------------

The bundle provides some form types, learn more about this, [reading the specific documentation](Resources/doc/README-FormTypes.md).
