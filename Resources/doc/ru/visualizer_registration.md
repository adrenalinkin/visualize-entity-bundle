Добавление нового визуализатора [![In English](https://img.shields.io/badge/Switch_To-English-green.svg?style=flat-square)](../en/visualizer_registration.md)
===============================

Чтобы зарегистрировать свой визуализатор вам понадобится:

1. Создать класс, реализующий интерфейс [VisualizerInterface](../../../Visualizer/VisualizerInterface.php). 
    Именование визуализаторов происходит посредством добавления к названию класса суффикса `Visualizer`.
2. Зарегистрировать созданный класс как сервис с тегом `linkin_visualize_entity.visualizer`.
3. Определить способ использования. Автоматически - путем приоритезации и соответствию условию метода `supports` или
    исключительно в ручном режиме:
    * Автоматическое использование подразумевает последовательный вызов всех автоматических визуализаторов в
        соответствии с их приоритетностью (`priority`). Чтобы быть уверенным что всегда будет применен необходимый
        визуализатор следует обратить внимание на приоритет регистрируемого визуализатора или задать специальное
        условие активации в методе `support`.
    * Ручное использование подразумевает отсутствие визуализатора в едином массиве автоматически применяемых
        визуализаторов и возможность вызова только при помощи явного упоминания в конфигурации
        [visualize_entity.yml](./visualize_entity.md). Такой подход исключит непредсказуемые вызовы созданного
        визуализатора. Ручное использование активируется параметром `manual_usage_only: 1`.
        При этом параметр `priority` не учитывается.
4. Если визуализатор работает с перечисляемыми данными (`traversable`), то необходимо установить
    параметр `traversable: 1`. Это позволит избежать циклических ссылок. Помеченный `traversable: 1` визуализатор
    всегда принимает первым аргументом `@linkin_visualize_entity.handler.visualizer`. Переданный сервис является
    урезанной версией оригинального и содержит только глобально зарегистрированные визуализаторы с пометкой
    `traversable: 0`.
 
**Важно**: Применяется только первый подошедший визуализатор.

Пример конфигурации сервисов
----------------------------

Конфигурация ниже регистрирует визуализатор для автоматического использования. Высокий приоритет обеспечивает вызов
раньше большинства визуализаторов.

```yaml
    # автоматически применяемый визуализатор
    acme.visualizer.custom_integer:
        class:      '%acme.visualizer.custom_integer.class%'
        tags:
            - { name: linkin_visualize_entity.visualizer, priority: 500 }
```

Конфигурация ниже регистрирует визуализатор для ручного использования.

```yaml
    # визуализатор для применения в ручном режиме
    acme.visualizer.user_role:
        class:      '%acme.visualizer.user_role.class%'
        tags:
            - { name: linkin_visualize_entity.visualizer, manual_usage_only: 1 }
```

Конфигурация ниже регистрирует визуализатор для обработки перечисляемого типа данных.
**Примечание**: Если вам не нужен доступ к `@linkin_visualize_entity.handler.visualizer` внутри вашего визуализатора,
работающего с перечисляемым типом данных, то ставить `traversable: 1` не нужно.

```yaml
    # визуализатор для обработки перечисляемого типа данных
    acme.visualizer.some_collection:
        class:      '%acme.visualizer.some_collection.class%'
        arguments:
            - '@linkin_visualize_entity.handler.visualizer' # будет передан ограниченная копия оригинального сервиса
        tags:
            - { name: linkin_visualize_entity.visualizer, manual_usage_only: 1, traversable: 1 }
```