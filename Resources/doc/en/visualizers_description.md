Predefined visualizers [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](../ru/visualizers_description.md)
======================

To provide a flexible control over the display of data in the bundle, there is a system of visualizers, which organized
by tagged and prioritized services. It's provides easy way for adding new custom visualizer. More information about
registration own visualizer in the part [Add new visualizer](./visualizer_registration.md).

StringVisualizer
----------------

Responsible for displaying string data. Uses type casting `(string)`.

*Haven't options.*

IntegerVisualizer
-----------------

Responsible for displaying integer data. Uses type casting `(int)`.

*Haven't options.*

BooleanVisualizer
-----------------

Responsible for displaying boolean data. Uses type casting `(bool)`.

*Options:*

| Name            | Type               | By default               | Description                               |
|:----------------|:-------------------|:------------------------:|-------------------------------------------|
| **true_value**  | `string`           | `linkin_visualize_entity.labels.yes` | Display value for the `true` value  |
| **false_value** | `string`           | `linkin_visualize_entity.labels.no`  | Display value for the `false` value |
| **translate**   | `null` `bool` `string` | `null` | Use translation for displayed result or not. If value `false`, then translation will not using. If has been received some string, then it will be applies as translation domain. If you put `true` or `null`, then will be using default translation domain. |

DateTimeVisualizer
------------------

Responsible for displaying date data. Uses standard object PHP, which implements `\DateTimeInterface` interface.
To determine the exact date values uses the `Doctrine` platform.

*Options:*

| Name        | Type     | By default               | Description                                        |
|:------------|:---------|:------------------------:|----------------------------------------------------|
| **format**  | `string` | Relates on the doctrine type: `Y-m-d H:i:s` `Y-m-d` `H:i:s` | Date format     |

DecimalVisualizer
-----------------

Responsible for displaying floating-point values. Uses type casting `(float)` and standard PHP function - 
[round](http://php.net/manual/en/function.round.php). If extra parameters was not received, then function `round` will
not be applied.

*Options:*

| Name                | Type     | By default          | Description                                  |
|:--------------------|:---------|:-------------------:|----------------------------------------------|
| **round_mode**      | `string` | `PHP_ROUND_HALF_UP` | Rounding occurs mode of the `round` function |
| **round_precision** | `int`    | `0`                 | Number of decimal digits to round to         |

DoctrineCollectionVisualizer
----------------------------

Responsible for displaying `Doctrine` collections.
**Work with traversable data**.

*Options:*

| Name              | Type            | By default    | Description                                |
|:------------------|:----------------|:-------------:|--------------------------------------------|
| **delimiter**     | `string`        | `, `          | Collection items delimiter            |
| **limit**         | `int` `null`    | `30`          | Maximal collection items count, which will be displaying. If received `null` will be load all available items |
| **getters_array** | `array`         | `[]`   | List of the getters which will be calling for displaying data |
| **template**      | `string` `null` | `null` | Template for customization displaying data. Values will be replaced by appropriated getters names, f.e. `getName (getLogin)` will be convert into `myName (superLogin)` |

ArrayVisualizer
---------------

Responsible for displaying arrays. The main feature of this visualizer - walking across all array items and applying
corresponding type visualizer. Uses standard PHP function - [implode](http://php.net/manual/en/function.implode.php).
**Work with traversable data**.

*Options:*

| Name           | Type     | By default   | Description           |
|:---------------|:--------:|:------------:|-----------------------|
| **delimiter**  | `string` | `, `         | Array items delimiter |

TraversableVisualizer
---------------------

Responsible for displaying object, which implements `\Traversable` interface. Uses same functional as `ArrayVisualizer`.
**Work with traversable data**.

ObjectVisualizer
----------------

Responsible for displaying PHP objects. Uses received getters or magic method `__toString`.

*Options:*

| Name              | Type            | By default    | Description                                |
|:------------------|:----------------|:-------------:|--------------------------------------------|
| **getters_array** | `array`         | `[]`   | List of the getters which will be calling for displaying data |
| **template**      | `string` `null` | `null` | Template for customization displaying data. Values will be replaced by appropriated getters names, f.e. `getName (getLogin)` will be convert into `myName (superLogin)` |

NumberFormatVisualizer
----------------------

Displaying numeric data by using standard PHP function -
[number_format](http://php.net/manual/en/function.number-format.php).
**Manual usage**.

*Options:*

| Name                   | Type     | By default    | Description                      |
|:-----------------------|:---------|:-------------:|----------------------------------|
| **decimals**           | `int`    | `0`           | Number of decimal points         |
| **decimal_separator**  | `string` | `.`           | Separator for the decimal point  |
| **thousand_separator** | `string` | `,`           | Thousands separator              |

MoneyFormatVisualizer
---------------------

Displaying numeric data by using standard PHP function -
[money_format](http://php.net/manual/en/function.money-format.php).
**Manual usage**.

*Options:*

| Name       | Type     | By default    | Description            |
|:-----------|:---------|:-------------:|------------------------|
| **format** | `string` | `%i`          | Displaying data format |
| **locale** | `string` | `0`           | Locale value           |

DefaultVisualizer
-----------------

Responsible for displaying any data and applies only when no one other visualizer was not support received data.
Any scalar data will be casting to `(string)`. More complex data types will be ignored and instead actual value will be
display the name of received data type.

*Haven't options.*
