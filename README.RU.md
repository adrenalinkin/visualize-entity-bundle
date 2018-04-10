Visualize Entity Bundle [![In English](https://img.shields.io/badge/Switch_To-English-green.svg?style=flat-square)](./README.md)
=======================

Введение
--------

Бандл предоставляет возможность выводить данные сущности для просмотра при помощи YAML конфига.

Установка
---------

### Шаг 1: Загрузка бандла

Откройте консоль и, перейдя в директорию проекта, выполните следующую команду для загрузки наиболее подходящей
стабильной версии этого бандла:
```bash
    composer require adrenalinkin/visualize-entity-bundle
```
*Эта команда подразумевает что [Composer](https://getcomposer.org) установлен и доступен глобально.*

### Шаг 2: Подключение бандла

После включите бандл добавив его в список зарегистрированных бандлов в `app/AppKernel.php` файл вашего проекта:

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

Конфигурация
------------

Чтобы начать использовать бандл вам необходимо создать конфигурационный файл в необходимом бандле вашего проекта.
Простейшая конфигурация сущности пользователя для вывода трех полей:

```yaml
linkin_visualize_entity:
    acme_user_default:
        className:    AcmeBundle:User
        fields:
            username:   ~
            firstName:  ~
            email:      ~
```

Подробнее с файлом конфигурации можно ознакомиться в разделе
[Конфигурационный файл visualize_entity.yml](Resources/doc/ru/visualize_entity.md).

Использование
-------------

Пример использования предполагает использование простейшей конфигурации, составленной в предыдущем разделе.

### Контроллер

Чтобы применить созданную ранее конфигурацию вывода данных сущности достаточно вызвать сервис
`linkin_visualize_entity.builder` и вызвать построение объекта при помощи метода `buildViewEntity`,
передав название конфигурации и идентификатор сущности или саму сущность непосредственно.

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

### Шаблон TWIG

Объект класса `ViewEntity` открывает ряд возможностей для работы в twig-шаблоне. 
Самый простой способ - это вывод всех полей, которые еще не отображались. Метод `notDisplayed`.

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

При необходимости вывода некоторых полей в специальном форматировании стоит воспользоваться методом `fieldData`.
Последующий вызов метода `notDisplayed` вернет данные которые не будут содержать ранее показанные.

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

Также можно воспользоваться рядом других методов для более специфичных случаев:
 * `entity` - для получения объекта вывода.
 * `entityId` - для получения идентификатора объекта.
 * `all` - для получения всех полей вывода не учитывая, были ли они уже отображены или нет. При этом можно
    передать названия полей, которые следует исключить.
 * `alreadyDisplayed` - список всех уже отображенных полей.
 * `resetDisplayed` - сбросить статистику отображенных полей.

Визуализаторы
-------------

Визуализация различных типов данных регулируется при помощи визуализаторов. Ознакомиться со всеми предустановленными
визуализаторами можно в разделе 
[Описание зарегистрированных визуализаторов](Resources/doc/ru/visualizers_description.md).
Подробнее о создании собственного визуализатора в разделе 
[Добавление нового визуализатора](Resources/doc/ru/visualizer_registration.md).

Зависимости
-----------

 * [EntityHelperBundle](https://github.com/adrenalinkin/entity-helper-bundle)

Лицензия
--------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
