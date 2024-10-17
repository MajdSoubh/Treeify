# Treeify

The Treeify package allows you to easily convert flat collections of Laravel models into hierarchical tree structures based on self-parent-child relationships.

## Installation

You can install the package via composer:

```shell
composer require maso/treeify
```

## Usage

To use the Treeify package, simply apply the HasTree trait to your Eloquent models and utilize the scopeTreeify method to generate a hierarchical tree structure:

```php
use Maso\Treeify\Traits\HasTree;

class Category extends Model
{
    use HasTree;

    /**
     * The name of the field that identifies the parent ID.
     *
     * This field is optional. If not specified, the default field name 'parent_id' will be used
     *
     */
    protected $parentFieldName = 'parent_id';
}

```

Assuming you have the following categories in your database:

| id  | name           | parent_id |
| --- | -------------- | --------- |
| 1   | Electronics    | NULL      |
| 2   | Laptops        | 1         |
| 3   | Desktops       | 1         |
| 4   | Smartphones    | 1         |
| 5   | Gaming Laptops | 2         |

#### Generating a Full Tree for All Categories

```php
$tree = Category::treeify();
```

Expected Output

```php
[
    {
        "id": 1,
        "name": "Electronics",
        "children": [
            {
                "id": 2,
                "name": "Laptops",
                "children": [
                    {
                        "id": 5,
                        "name": "Gaming Laptops"
                    }
                ]
            },
            {
                "id": 3,
                "name": "Desktops"
            },
            {
                "id": 4,
                "name": "Smartphones"
            }
        ]
    }
]
```

#### Generating a Tree Starting from a Specific Category

```php
$category = Category::find(2);
$categoryTree = $category->treeify();

```

Expected Output

```php
[
    {
        "id": 2,
        "name": "Laptops",
        "children": [
            {
                "id": 5,
                "name": "Gaming Laptops"
            }
        ]
    }
]
```

#### Generating Trees Including All Sub-Parents

```php
$tree = Category::treeify(false);


```

Expected Output

This method includes all categories in the hierarchy, including sub-parents

```php
[
    {
        "id": 1,
        "name": "Electronics",
        "children": [
            {
                "id": 2,
                "name": "Laptops",
                "children": [
                    {
                        "id": 5,
                        "name": "Gaming Laptops"
                    }
                ]
            },
            {
                "id": 3,
                "name": "Desktops"
            },
            {
                "id": 4,
                "name": "Smartphones"
            }
        ]
    },
    {
        "id": 2,
        "name": "Laptops",
        "children": [
            {
                "id": 5,
                "name": "Gaming Laptops"
            }
        ]
    },
    {
        "id": 3,
        "name": "Desktops"
    },
    {
        "id": 4,
        "name": "Smartphones"
    }
]

```

## Changelog

Please see the [CHANGELOG](https://github.com/MajdSoubh/Treeify/CHANGELOG.md) for more information about what has changed or updated or added recently.

## Security

If you discover any security related issues, please email them first to majdsoubh53@gmail.com,
if we do not fix it within a short period of time please open a new issue describing your problem.

## Credits

-   [Majd Soubh](https://www.linkedin.com/in/majd-soubh/)
