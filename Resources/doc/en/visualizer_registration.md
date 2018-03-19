Add new visualizer [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](../ru/visualizer_registration.md)
==================

For register new visualizer you need:

1. Create class, which implements interface [VisualizerInterface](../../../Visualizer/VisualizerInterface.php).
    Visualizers naming occurs by adding to the name of the class of the suffix `Visualizer`.
2. Register created class as service with tag `linkin_visualize_entity.visualizer`.
3. Determine how to use. Automatically - by prioritization and conformity to `supports` predicate or manually only:
    * Automatic usage means consistent calling all visualizers registered as automatic according to they priority.
        To be sure, that the necessary visualizer has been used, you should put your attention to the priority of the
        new visualizer and define appropriated condition for the `support` method.
    * Manual usage means that visualizer will not be contains in the common list of the automatically called
        visualizers and can be called only by directly configuration [visualize_entity.yml](./visualize_entity.md).
        This approach exclude unexpected call of the new visualizer. Manual usage activated by additional parameter
        `manual_usage_only: 1`. Herewith `priority` param will be ignored.
4. If new visualizer should work with traversable data, then you should use `traversable: 1` parameter. It's allows to
    avoid circular references. Marked visualizer as `traversable: 1` always receive
    `@linkin_visualize_entity.handler.visualizer` as first argument into constructor. Received service
    `@linkin_visualize_entity.handler.visualizer` is a cut version of the original and contains only globally
     registered visualizers with parameter `traversable: 0`.
 
**Important**: Will be applied only first suitable visualizer.

Service configuration example
-----------------------------

Configuration below provides registration of the visualizer for the automatic usage. A high priority provides a call
before most visualizers.

```yaml
    # visualizer for automatic usage
    acme.visualizer.custom_integer:
        class:      '%acme.visualizer.custom_integer.class%'
        tags:
            - { name: linkin_visualize_entity.visualizer, priority: 500 }
```

Configuration below provides registration of the visualizer for the manual usage only.

```yaml
    # visualizer for manual usage
    acme.visualizer.user_role:
        class:      '%acme.visualizer.user_role.class%'
        tags:
            - { name: linkin_visualize_entity.visualizer, manual_usage_only: 1 }
```

Configuration below provides registration of the visualizer for process traversable data.
**Note**: If you don't need have access for the `@linkin_visualize_entity.handler.visualizer` inside your visualizer,
which will work with traversable data, then you can leave `traversable: 0`.

```yaml
    # visualizer for process traversable data
    acme.visualizer.some_collection:
        class:      '%acme.visualizer.some_collection.class%'
        arguments:
            - '@linkin_visualize_entity.handler.visualizer' # will be received cut version of the original service
        tags:
            - { name: linkin_visualize_entity.visualizer, manual_usage_only: 1, traversable: 1 }
```
