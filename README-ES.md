[![en](https://img.shields.io/badge/lang-en-red.svg)](https://github.com/softspring/symfonic/blob/5.1/README.md)
[![es](https://img.shields.io/badge/lang-es-yellow.svg)](https://github.com/softspring/symfonic/blob/5.1/README-ES.md)

# Symfonic
Un CMS potente para Symfony.

## Índice

1. Por qué Symfonic
2. Cómo instalar y usar Symfonic
   1. Uso de Symfonic en un proyecto Symfony limpio
   2. Uso de Symfonic con un proyecto Sylius
3. Añadiéndole más componentes

## 1. Por qué Symfonic

- Diseña maquetaciones con estructuras de bloques simples o complejas y reutilízalas en diferentes páginas o secciones. 
- Crea páginas combinando módulos creados por ti mismo o utilizando los que le proporcionamos. 
- Para secciones comunes en diferentes páginas, simplifica la creación con nuestro concepto de bloques reutilizables.
- Gestiona múltiples versiones de la misma página y previsualiza los cambios sin miedo a cometer errores, ¡publica la versión final cuando estés contento con ella!
- Todo esto con una web semántica desde el primer bloque, responsive, multisite, multiidioma y preparada para SEO.

## 2. Cómo instalar y utilizar Symfonic

### 2.1 Usar Symfonic en un proyecto Symfony limpio

#### Instalación de Symfony

Instala Symfony siguiendo las instrucciones actuales en https://symfony.com/doc/current/setup.html

```bash
symfony new mi-proyecto-symfonic --version="6.2.*" --webapp
```

#### Configuración previa

Para trabajar con el CMS necesitamos una base de datos MySQL (PostgreSQL también debería ser compatible).

##### Configuración de la base de datos en Docker

Utilizaremos el fichero *docker-compose.yaml* que viene con el proyecto:

> *Note* Estos son valores de desarrollo, no están listos para Producción.

```yaml
version: '3

services:
###> doctrine/doctrine-bundle
  base de datos:
    image: "mysql:8.0"
    entorno:
      MYSQL_ROOT_PASSWORD: nopassword
      MYSQL_DATABASE: cms
      MYSQL_USER: test
      MYSQL_PASSWORD: nopassword
    puertos:
      - 3306:3306
    volúmenes:
      - database_data:/var/lib/mysql
###< doctrine/doctrine-bundle ###

volúmenes:
###> doctrine/doctrine-bundle ###
  database_data:
###< doctrine/doctrine-bundle ###
```

y ya podemos arrancar el contenedor

```bash
docker-compose up -d
```

##### Configurando los valores del entorno

En .env cambiamos los valores de la base de datos MySQL:

```yaml
###> doctrine/doctrine-bundle
DATABASE_URL="mysql://test:nopassword@127.0.0.1:3306/cms?serverVersion=8.0&charset=utf8mb4"
###< doctrine/doctrine-bundle ###
```

### Configuring Symfony Flex de Softspring (for Development)
> *Nota* Este paso no será necesario cuando las recetas de Symfony Flex de Softspring estén integradas en el repositorio contrib.

En composer.json, añadimos los endpoints y establecemos allow-contrib a true:

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

### TEMPORAL
Hasta que publiquemos la versión 5.1 de los bundles tenemos que incluir (en composer.json):

```yaml
{
    "minimum-stability": "dev"
}
```

#### Configurar webpack
> *Nota* Si no tenemos yarn instalado, necesitamos instalarlo antes. En Ubuntu < 18.04 hay algunos problemas entre los paquetes 
> yarnpkg y cmdtest

```yaml
composer require webpack
yarn install
yarn add @popperjs/core bootstrap bootstrap-icons underscore.string sass-loader@^13.0.0 sass --dev
```

##### Configurar admin en webpack

En el archivo webpack.config.js

```yaml
Encore
    // ...

    // añadir admin.js
    .addEntry('admin', './assets/admin.js')

    // ...
    
    // descomentar el cargador sass
    .enableSassLoader()
    
    // ...
```

### Instalar symfonic

```yaml
composer require softspring/symfonic:^5.1@dev
bin/consola doctrina:migraciones:migrar -n
```

> *Nota* Si tenemos un error de Driver, necesitaremos instalar php-mysql en la versión de php que estemos usando.

Instalamos módulos adicionales: 

```yaml
composer require softspring/cms-module-collection:^5.1
```

Y compilamos los assets

``yaml
yarn build
```

Symfony flex debería haber hecho todo el trabajo y tendremos el proyecto listo para que podamos visualizarlo.

#### Algunos ajustes (por ahora)
Para que la app funcione correctamente tenemos que incluir framework.enabled_locales:

```yaml
# config/paquetes/traduccion.yaml
framework:
    enabled_locales: ['en', 'es']
``` 

No hemos podido sobrescribir templates/base.html.twig con las recetas de Symfony Flex, así que tienes que cambiarlo a mano (hasta que encontremos otra solución). Por ahora:

```yaml
{# templates/base.html.twig #}
{% extends '@SfsComponents/base.html.twig' %}
``` 

### Podemos iniciar la aplicación
Vamos a utilizar el comando CLI de Symfony para servir la aplicación:

```yaml
symfony server:start
``` 

Una vez hecho esto, tendremos un Not Found en https://127.0.0.1:8000/ .

Si vamos a https://127.0.0.1:8000/admin/cms/pages podremos configurar nuestra primera página.