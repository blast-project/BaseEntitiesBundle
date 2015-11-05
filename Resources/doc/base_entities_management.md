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



## Extend or Customize a Base Entity

### Create Interface

### Create Trait

### Create EventSubscriber (Optionnal)

### Apply to your entities