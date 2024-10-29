[![en](https://img.shields.io/badge/lang-en-red.svg)](https://github.com/softspring/symfonic/blob/5.4/README.md)
[![es](https://img.shields.io/badge/lang-es-yellow.svg)](https://github.com/softspring/symfonic/blob/5.4/README-ES.md)

# Symfonic
A Powerful Symfony CMS.

## Index

1. Why Symfonic
2. How to Install and Use Symfonic
   1. Using Symfonic in a Clean Symfony Project
   2. Using Symfonic with a Sylius Project
3. Adding more components to it

## 1. Why Symfonic

- Design layouts with simple or complex block structures and reuse them on different pages or sections. 
- Create pages by combining modules you create yourself or using the ones we provide. 
- For common sections on different pages, simplify creation with our reusable block concept. 
- Manage multiple versions of the same page and preview the changes without fear of making mistakes, publish the final version when you are happy with it! 
- All this with a semantic website from the first block, responsive, multi-site, multi-language and SEO ready.

## 2. How to Install and Use Symfonic

### 2.1 Using Symfonic in a Clean Symfony Project

#### Installing Symfony

Install Symfony following current instructions in https://symfony.com/doc/current/setup.html

```bash
symfony new my-symfonic-project --version="6.2.*" --webapp
```

#### Previous configuration

To work with the CMS we need a MySQL database (PostgreSQL should also be compatible).

##### Configuring the Database in Docker

We will use the file *docker-compose.yaml* that comes with the project:

> *Note* This are development values, not ready for Production.

```yaml
version: '3'

services:
###> doctrine/doctrine-bundle ###
  database:
    image: "mysql:8.0"
    environment:
      MYSQL_ROOT_PASSWORD: nopassword
      MYSQL_DATABASE: cms
      MYSQL_USER: test
      MYSQL_PASSWORD: nopassword
    ports:
      - 3306:3306
    volumes:
      - database_data:/var/lib/mysql
###< doctrine/doctrine-bundle ###

volumes:
###> doctrine/doctrine-bundle ###
  database_data:
###< doctrine/doctrine-bundle ###
```

and we can already start the container:

```bash
docker-compose up -d
```

##### Configuring the environment value

In .env change the values of the MySQL database:

```yaml
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://test:nopassword@127.0.0.1:3306/cms?serverVersion=8.0&charset=utf8mb4"
###< doctrine/doctrine-bundle ###
```

### Configuring Symfony Flex de Softspring (for Development)
> *Note* This step will not be needed when Softspring' Symfony Flex recipes are integrated in the contrib repository

In composer.json, we add the endpoints and we establish allow-contrib to true:

```yaml
{
    "extra": {
        "symfony": {
            "allow-contrib": true,
            "require": "6.2.*",
            "endpoint": ["https://api.github.com/repos/softspring/recipes/contents/index.json", "flex://defaults"]
        }
    }
}
```

### TEMPORARY
Until we release version 5.4 of the bundles we have to include (in composer.json):

```yaml
{
    "minimum-stability": "dev"
}
```

#### Configure webpack
> *Note* If we don't have yarn installed, we need to install it before. In Ubuntu < 18.04 there are some issues between the packages 
> yarnpkg and cmdtest

```yaml
composer require webpack
yarn install
yarn add @popperjs/core bootstrap bootstrap-icons underscore.string sass-loader@^13.0.0 sass --dev
```

##### Configure admin in webpack

In the file webpack.config.js

```yaml
Encore
    // ...

    // add admin.js
    .addEntry('admin', './assets/admin.js')

    // ...
    
    // uncomment sass loader
    .enableSassLoader()
    
    // ...
```

### Install symfonic

```yaml
composer require softspring/symfonic:^5.4@dev
bin/console doctrine:migrations:migrate -n
```

> *Note* If we have a Driver error, we will need to install php-mysql in the php version we are using.

We install additional modules: 

```yaml
composer require softspring/cms-module-collection:^5.4
```

And we compile the assets

```yaml
yarn build
```

Symfony flex should have done all the work and we will have the project ready so we can visualize it.

#### Some adjustments (by now)
For the app to work properly we have to include framework.enabled_locales:

```yaml
# config/packages/translation.yaml
framework:
    enabled_locales: ['en', 'es']
``` 

We have been unable to overwrite templates/base.html.twig with Symfony Flex recipes, so you have to change it by hand (until we fin another solution). By now:

```yaml
{# templates/base.html.twig #}
{% extends '@SfsComponents/base.html.twig' %}
``` 

### We can start the application
We are going to use the Symfony CLI command to serve the app:

```yaml
symfony server:start
``` 

Once we have done this, we will have a Not Found in https://127.0.0.1:8000/ .

If we go to https://127.0.0.1:8000/admin/cms/pages we will be able to configure our first page.