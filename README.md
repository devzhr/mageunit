# MageUnit

MageUnit aims to be a simple unit testing framework for Magento 1.x.

## Build status

**Latest Release**

[![Build Status](https://travis-ci.org/zouhair2015/mageunit1.9.4.svg?branch=master)](https://travis-ci.org/zouhair2015/mageunit1.9.4)

**Development branch**

[![Build Status](https://travis-ci.org/zouhair2015/mageunit1.9.4.svg?branch=devel)](https://travis-ci.org/zouhair2015/mageunit1.9.4)

## Requirements

- PHP 5.3, 5.4, 5.5
- Magento 1.4.x to 1.9.x
- PHPUnit 4.x

## Usage

The simplest way to install MageUnit is to use Composer.
Create a composer.json file in your project's root directory having at least following content :

```js
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/zouhair2015/mageunit1.9.4"
        }
    ],
    "require": {
        "zouhair2015/mageunit1.9.4": "dev-master"
    }
}
```

By default, this will install MageUnit in Magento's lib folder.

The "src" folder contains all files required by MageUnit. 
The "tests" folder contains tests for MageUnit which will tell you if it is working as expected.
Launch the tests with PHPUnit.

All tests should pass. If not, there is a problem with your setup (or a bug in MageUnit).

## Write your own tests

You can create a new directory containing your tests anywhere you like. 
Make sure your PHPUnit configuration calls MageUnit's test listener and that your include path is correct, so should be your path to Mage.php.
To do so, copy content of phpunit.xml.dist to a phpunit.xml file and edit include paths and path to Mage.php to match your directory tree.
You can create your own bootstrap file, but keep in mind that you need to include Mage.php first, then MageUnit_Autoload.php and call `MageUnit_Autoload::enable()`.
All your test classes should inherit from **MageUnit_Framework_TestCase** in order to have access to MageUnit's API.

By default, the directory structure looks like the following, but you can totally separate your tests from those of the framework :

    Root
    |--app
    |--...
    |--lib
       |--mageunit
          |--src
          |--tests
             |--MageUnit
             |--bootstrap.php
             |--phpunit.xml

## API

### Factory methods

Magento makes use of factory methods (Mage::getModel(), Mage::helper()...) to build new instances of a class.
Using MageUnit's methods it is possible to easily configure objects that should be returned by such calls.

#### Models

```php
$this->setModel('core/store', new stdClass());
Mage::getModel('core/store');//returns given instance of stdClass
```

```php
$this->unsetModel('core/store');
Mage::getModel('core/store');//returns an instance of Mage_Core_Model_Store
```

Replacing a resource model works exactly the same way but you need to pass the identifier of the resource :

```php
$this->setModel('core/resource_store', new stdClass());
Mage::getResourceModel('core/store');//returns an instance of stdClass
```

When passing an object to setModel() as second argument, this object will work like a singleton, which means that
each call to getModel() with the same class alias will return the same class instance. When the second argument
is a string containing a class name, then each call to that method will return a new instance of this class.

```php
$this->setModel('core/store', 'stdClass');
Mage::getModel('core/store');//returns an instance of stdClass
Mage::getModel('core/store');//returns another instance of stdClass
```

#### Helpers

```php
$this->setHelper('core', new stdClass());
Mage::helper('core');//returns an instance of stdClass
```

```php
$this->unsetHelper('core');
Mage::helper('core');//returns an instance of Mage_Core_Helper_Data
```
 
#### Singletons

```php
$this->setSingleton('core/store', new stdClass());
Mage::getSingleton('core/store');//returns an instance of stdClass
```

```php
$this->unsetSingleton('core/store');
Mage::getSingleton('core/store');//returns an instance of Mage_Core_Model_Store
```
    
#### Blocks

```php
$this->setBlock('core/template', new Varien_Object());
Mage::app()->getLayout()->createBlock('core/template');//returns an instance of Varien_Object and not Mage_Core_Block_Template
```

```php
$this->unsetBlock('core/template');
Mage::app()->getLayout()->createBlock('core/template');//returns an instance of Mage_Core_Block_Template
```

When passing an object to setBlock() as second argument, this object will work like a singleton, which means that
each call to createBlock() with the same class alias will return the same class instance. When the second argument
is a string containing a class name, then each call to that method will return a new instance of this class.

```php
$this->setBlock('core/template', 'Varien_Object');
Mage::app()->getLayout()->createBlock('core/template');//returns an instance of Varien_Object
Mage::app()->getLayout()->createBlock('core/template');//returns another instance of Varien_Object
```

### Store Configuration

You can set and unset and reset any store configuration like this :

```php
$this->setConfig('general/store_information/name', 'my value');
Mage::getStoreConfig('general/store_information/name');//Will return 'my value'
```

```php
$this->unsetConfig('general/store_information/name');
Mage::getStoreConfig('general/store_information/name');//Will return real value
```

### Reset all replacements

You can easily reset all object or configuration replacements at once using following methods :

```php
$this->resetModels();
$this->resetSingletons();
$this->resetHelpers();
$this->resetBlocks();
$this->resetConfig();
```

This is done automatically at the end of each test class to avoid any side effects.
This means you will be able to share replacements between test methods of a same test class but not between
test methods of different test classes.
