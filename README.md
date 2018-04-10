Visualize Entity Bundle [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](./README.RU.md)
=======================

Introduction
------------

Bundle provides possibility for visualize entity data by yaml configuration.

Installation
------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable
version of this bundle:
```bash
    composer require adrenalinkin/visualize-entity-bundle
```
*This command requires you to have [Composer](https://getcomposer.org) install globally.*

### Step 2: Enable the Bundle

Then, enable the bundle by updating your `app/AppKernel.php` file to enable the bundle:

```php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = [
            // ...

            new Linkin\Bundle\EntityHelperBundle\LinkinEntityHelperBundle(),
            new Linkin\Bundle\VisualizeEntityBundle\LinkinVisualizeEntityBundle(),
        ];

        return $bundles;
    }

    // ...
}
```

Configuration
-------------

To start using bundle you need to create configuration file in the needed bundle of the your project.
Simple configuration for the user entity for displaying 3 fields:

```yaml
linkin_visualize_entity:
    acme_user_default:
        className:    AcmeBundle:User
        fields:
            username:   ~
            firstName:  ~
            email:      ~
```

More information about configuration file in the part
[Configuration file visualize_entity.yml](Resources/doc/en/visualize_entity.md).

Usage
-----

Usage example expect using simple configuration from the previous part.

### Controller

To apply simple user configuration, which has been created early, you just need to call
`linkin_visualize_entity.builder` service and call method `buildViewEntity` to create special object.
You need put name of the configuration as first parameter and entity object or entity identity as second.

```php
<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("acme_user")
 */
class AcmeController extends Controller
{
    /**
     * @Route("/user_view/{id}", name="acme_user_view")
     *
     * @Method("GET")
     *
     * @Template()
     *
     * @param int $id
     *
     * @return array
     */
    public function viewAction($id)
    {
        $viewEntity = $this->get('linkin_visualize_entity.builder')->buildViewEntity('acme_user_default', $id);

        return [
            'viewEntity' => $viewEntity,
        ];
    }
}
```

### TWIG Template

`ViewEntity` object allow several opportunities for work in twig-template.
Most simple and most popular method `notDisplayed` - will display all fields which was not displayed yet. 

```twig
    {# @var viewEntity \Linkin\Bundle\VisualizeEntityBundle\Entity\ViewEntity #}

    <table class="striped">
        <tbody>
        {% for fieldData in viewEntity.notDisplayed %}
            <tr>
                <td><b>{{ fieldData.label }}:</b></td>
                <td>{{ fieldData.value|raw }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
```

When you need display some fields in the specific place or just take specific field you should use `fieldData` method.
Next call of the `notDisplayed` method returns all data except already displayed by `fieldData` method call.

```twig
    {# @var viewEntity \Linkin\Bundle\VisualizeEntityBundle\Entity\ViewEntity #}

    <table class="striped">
        <thead>
        <th>
            <td>{{ viewEntity.fieldData('username').label }}</td>
            <td>{{ viewEntity.fieldData('username').value|raw }}</td>
        </th>
        </thead>
        <tbody>
        {% for fieldData in viewEntity.notDisplayed %}
            <tr>
                <td><b>{{ fieldData.label }}:</b></td>
                <td>{{ fieldData.value|raw }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
```

Also you can use other advanced methods for more specific situations:
 * `entity` - retrieve original entity object.
 * `entityId` - retrieve original entity identity.
 * `all` - returns all configuration-based fields regardless is field has been already displayed or not. Also you can
    set list of the fields which should be excluded from the result.
 * `alreadyDisplayed` - returns list of the all fields, which already has been displayed.
 * `resetDisplayed` - reset statistics of the already has been displayed fields.

Visualizers
-----------

Visualization of the different data types is handled by visualizers. You can get information about all predefined
visualizers in the part [Predefined visualizers](Resources/doc/en/visualizers_description.md).
More information about registration own visualizer in the part 
[Add new visualizer](Resources/doc/en/visualizer_registration.md).

Dependencies
------------

 * [EntityHelperBundle](https://github.com/adrenalinkin/entity-helper-bundle)

License
-------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
