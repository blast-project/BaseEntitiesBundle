# Base Entities Management

This documentation explain how base entities works.

BaseEntities adds doctrine behaviors to manage entity common data structure and behaviors.

## Architecture

### Interfaces

The aim of Interfaces is to notify the Base Entities Management system
that the Entity (the one which implement the Interface) will have additional attributes (database fields), relations, methods.

We define specific methods signature in these Interfaces in order to force the Entity to implement these methods.

Here's an example of a basic Interface :

```php
namespace Librinfo\BaseEntitiesBundle\Entity\Interfaces;

interface BaseEntityInterface
{
    public function __toString();
}
```

This Interface force the ```__toString``` method implementation.

This Entity will implement this Interface :

```php
namespace Librinfo\CRMBundle\Entity;

use Librinfo\BaseEntitiesBundle\Entity\Interfaces\BaseEntityInterface;

/**
 * Category
 */
class Category implements BaseEntityInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    private $name;

    public function __toString()
    {
        return $this->name;
    }
}
```

At this point, it's just a simple OOP concept. This entity must implement interface's methods

But, we will not implement these methods in each Entities, we use Traits to factorize these implementations.

### Traits

Traits have been introduced in PHP since version 5.4.0.
[see official PHP Traits documentation](http://php.net/manual/fr/language.oop5.traits.php)

In a Traits, we will define the default implementation of methods and/or attributes.

```php
namespace Librinfo\BaseEntitiesBundle\Entity\Traits;

trait BaseEntity
{
    public function __toString()
    {
        if (method_exists(get_class($this), 'getName'))
        {
            return (string)$this->getName();
        }
        if (method_exists(get_class($this), 'getId'))
            return (string)$this->getId();
        return '';
    }
}
```

This Traits act as a macro, allowing code factoring in single element.

See the previous Entity using this Trait :

```php
namespace Librinfo\CRMBundle\Entity;

use Librinfo\BaseEntitiesBundle\Entity\Interfaces\BaseEntityInterface;
use Librinfo\BaseEntitiesBundle\Entity\Traits\BaseEntity;

/**
 * Category
 */
class Category implements BaseEntityInterface
{
    // Here we « include » the Trait
    use BaseEntity;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    private $name;

}
```

We don't have to write the « __toString() » method because the trait already holds it.

#### Note

Traits don't support standard OOP « inheritance » concepts. If you want to « override » a trait's method,
you just have to declare a local Trait before your Entity and use it insteadof the original one.

```php
namespace Librinfo\CRMBundle\Entity;

use Librinfo\BaseEntitiesBundle\Entity\Interfaces\BaseEntityInterface;
use Librinfo\BaseEntitiesBundle\Entity\Traits\BaseEntity;

trait BaseEntitySelf
{
    public function __toString()
    {
        return 'overrided_method';
    }
}

/**
 * Category
 */
class Category implements BaseEntityInterface
{
    use BaseEntitySelf, BaseEntity
    {
        BaseEntitySelf::__toString insteadof BaseEntity; // Here is the « overriding » instruction
    }
}

```

When executing this instruction : ```echo new Category();``` it ouputs ```overrided_method```.

### EventSubscribers

We're using standard Doctrine EventSubscriber to manage BaseEntities behaviors.
* [see official Symfony documentation](http://symfony.com/doc/current/cookbook/doctrine/event_listeners_subscribers.html#creating-the-subscriber-class)
* [see official Doctrine documentation](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/events.html#the-event-system)

Here's a simplified example of Traceable EventSubscriber :

```php
namespace Librinfo\BaseEntitiesBundle\EventListener;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Librinfo\BaseEntitiesBundle\Entity\Interfaces\TraceableInterface;

class TraceableListener implements EventSubscriber
{
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata', // event when doctrine build Entities mapping
            'prePersist', // event when doctrine creates new entity
            'preUpdate' // event when doctrine update existing entity
        ];
    }

    /**
     * define Traceable mapping at runtime
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if (!array_key_exists(TraceableInterface::class, $metadata->getReflectionClass()->getInterfaces()))
            return; // return if current entity doesn't implement TraceableInterface

        // [...]

        // setting default mapping configuration for Traceable

        // createdDate
        $metadata->mapField([
            'fieldName' => 'createdDate',
            'type'      => 'datetime',
            'nullable'  => true
        ]);

        // [...]

        // createdBy
        $metadata->mapManyToOne([
            'targetEntity' => $this->userClass,
            'fieldName'    => 'createdBy',
            'joinColumn'   => [
                'name'                 => 'createdBy_id',
                'referencedColumnName' => 'id',
                'onDelete'             => 'SET NULL',
                'nullable'             => true
            ]
        ]);

        // [...]
    }
```

This EventSubscriber declares which events it will manage with the method ```getSubscribedEvents()```.
* [see official Doctrine documentation](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/events.html#lifecycle-events)

For each subscribed events, this class has to implement corresponding method :
```Event : loadClassMetadata``` => ```Method : loadClassMetadata()```

Let's take a usefull example :
* The need : automaticaly inserting creation date of an entity and storing the User that created that entity.
* Expected : simplify entity lifecycle logging management.

```php

class TraceableListener implements EventSubscriber
{
    // [...]

    /**
     * sets Traceable dateTime and user information when persisting entity
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();
        if (!$entity instanceof TraceableInterface)
            return;

        $user = $this->tokenStorage->getToken()->getUser(); // Using SF 2.6 TokenStorage service to retreive current user
        $now = new DateTime('NOW');

        $entity->setCreatedBy($user);
        $entity->setCreatedDate($now);
    }

    // [...]
}
```

This is quite trivial, this event listener appends data before persisting entities that implements TraceableInterface.