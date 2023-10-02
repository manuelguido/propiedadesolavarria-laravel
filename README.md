# Propiedades Olavarría

## Descargar

Para descargar deste un terminal:

```bash
  git clone https://github.com/manuelguido/propiedadesolavarria-laravel.git
```

## Setup del proyecto 

Entrar al directorio del proyecto

```bash
cd propiedadesolavarria-laravel
```

Instalar dependencias de composer

```bash
composer install
```

Crear copia local del archivo .env

```bash
cp .env.example .env
```

Generar clave de encriptación

```bash
php artisan key:generate
```

Generar enlace entre storage y directorio púbico 

```bash
php artisan storage:link
```

Mover en interior de la carpeta "/public/images" a "/storage/app"


Configurar base de datos nueva en el archivo .env

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=propiedades-olavarria
DB_USERNAME=root
DB_PASSWORD=
```

Correr migraciones

```bash
php artisan migrate
```

Correr semillas

```bash
php artisan db:seed
```

## Correr proyecto

Desde un terminal ejecutar

```bash
php artisan serve
```

## Tests

Para correr los tests de PHPUnit ejecutar desde el terminal:

```bash
php artisan test
```
