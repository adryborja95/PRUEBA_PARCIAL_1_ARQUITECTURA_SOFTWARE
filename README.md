<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# PRUEBA_PARCIAL_1_ARQUITECTURA_SOFTWARE
Implementación de un CRUD de Posts mediante Arquitectura de Microservicios con Laravel

## Autores:

- Borja Diaz Adriana Maribel (adryborja95)
- 

# Microservicios Laravel: Autenticación y Posts

Repositorio con 2 proyectos Laravel:

- `PRY_AUTENTICACION_MICROSERVICIO` → login, registro y validación de tokens (MySQL).
- `PRY_POST_MICROSERVICIO` → CRUD de posts protegido usando el token (PostgreSQL).

> En los ejemplos se usa la IP `192.168.100.31`, puerto **8000** para autenticación y **8001** para posts.  
> Cambia la IP/puertos según tu entorno.

## 1. Requisitos previos

- PHP 8.x
- Composer
- MySQL (para autenticación)
- PostgreSQL (para posts)
- Postman o similar
- Extensión PDO para MySQL y PostgreSQL habilitadas

## 2. Microservicio de Autenticación (PRY_AUTENTICACION_MICROSERVICIO)

### 2.1. Instalación y configuración

1. Entrar a la carpeta del proyecto:

   cd PRY_AUTENTICACION_MICROSERVICIO

2. Instalar dependencias:

    composer install

3. Crear archivo .env

    cp .env.example .env

4. Configurar la base de datos MySql en .env

    - DB_CONNECTION=mysql
    - DB_HOST=127.0.0.1
    - DB_PORT=3306
    - DB_DATABASE=nombre_base_de_datos
    - DB_USERNAME=tu_usuario
    - DB_PASSWORD=tu_password


5. Generar key y ejecutar migraciones

    - php artisan key:generate
    - php artisan migrate

6. Levantar el microservicio en el puerto 8000

    php artisan serve --host=0.0.0.0 --port=8000

    - En host= (ingresar su ip)


### 2.2. Pruebas en Postman (Autenticación)

2.2.1. Registrar usuario (opcional)

- Método: POST

- URL: http://192.168.100.31:8000/api/register (cambiar url segun su entorno)

- Headers:

    - Accept: application/json

    - Content-Type: application/json

- Body (JSON):

{
  "name": "Adriana Borja",

  "email": "adriana@example.com",

  "password": "123456"
}

2.2.2. Login (obtener token)

- Método: POST

- URL: http://192.168.100.31:8000/api/login (cambiar url segun su entorno)

- Headers:

    - Accept: application/json

    - Content-Type: application/json

- Body (JSON):

{
  "email": "adriana@example.com",

  "password": "123456"
}

En la respuesta copiar el valor de token.
Ese token se usará como:

- Authorization: Bearer TU_TOKEN

2.2.3. Validar token

- Método: GET

- URL: http://192.168.100.31:8000/api/validate-token (cambiar url segun su entorno)

- Headers:

    - Accept: application/json

    - Authorization: Bearer TU_TOKEN

Debe responder con un JSON indicando que el token es válido y devolver los datos del usuario.

2.2.4. Logout

- Método: POST

- URL: http://192.168.100.31:8000/api/logout

- Headers:

    - Accept: application/json

    - Authorization: Bearer TU_TOKEN

Revoca los tokens del usuario.

Se puede realizar CRUD en este microservicio, para ello ocupar los siguientes URL
- GET - http://192.168.100.31:8000/api/users (Visualziar todos los usuarios)
- GET - http://192.168.100.31:8000/api/users/1 (Visualizar usuario por ID)
- PUT - http://192.168.100.31:8000/api/users/1 (Actualizar información de usuario por ID)
- DEL - http://192.168.100.31:8000/api/users/1 (Eliminar usuario por ID)

NOTA: para realizar el CRUD debe colocar los headers antes mencionados de Accept y Authorization. En caso de realziar PUT tambipén debera colocar adicional el header Content-Type: application/json


## 3. MMicroservicio de Posts (PRY_POST_MICROSERVICIO)

### 3.1. Instalación y configuración

### 3.2. Pruebas en Postman (Posts)



## 4. Flujo recomendado de pruebas

1. Iniciar microservicio de autenticación (puerto 8000).

2. En Postman:

    - (Opcional) Registrar usuario.

    - Hacer login y copiar el token.

    - Probar /api/validate-token con ese token.

3. Iniciar microservicio de posts (puerto 8001).

4. En Postman, usando siempre Authorization: Bearer TOKEN:

    - Probar GET /api/posts.

    - Crear un post con POST /api/posts.

    - Ver, actualizar y eliminar posts por ID.

Con esto ya se pueden ejecutar y comprobar todos los requisitos usando Postman en ambos microservicios.