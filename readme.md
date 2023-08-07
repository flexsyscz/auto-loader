Flexsys AutoLoader
==================

This library helps you with autoload of classes in your project.

Requirements
------------

- Requires PHP 8.1

Using
-----

The config file uses NEON (https://ne-on.org). The example of a configuration is below:

    parameters:
        resourcesDir: ../Resources/
    
    extensions:
    autoloader: Flexsyscz\AutoLoader\AutoLoaderExtension
    
    autoloader:
        forms:
            path: %resourcesDir%/Forms/
            allow: .+FormFactory$
            ignore: ^HelloFormFactory$
    
        controls:
            path: %resourcesDir%/Controls/
            allow: .+Control$
    
    services:
        - Tests\Resources\TestClass

Example above shows you how to define autoload of forms and controls in your app. Let's take a look at the parameters by which 
we can control the autoloader.

| Parameter | Description                                      |
|-----------|--------------------------------------------------|
| path      | defines where the autoloader will seek a classes |
| allow     | defines a regex mask of allowed classes          |
| ignore    | defines a regex mask of ignored classes          |

