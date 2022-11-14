
# API REST with Laravel 8 + JWT + MongoDB + SWAGGER

## About Repository

A simple API REST with Laravel 8 + Laravel 8 + JWT + MongoDB.

## Tech Specification

- Laravel 8
- JWT ( https://jwt-auth.readthedocs.io/en/develop/laravel-installation)
- MongoDB
- SWAGGER

## Installation

- git clone https://github.com/alexandergallardo/api-laravel-mongodb-jwt.git
- cd api-laravel-mongodb-jwt/
- composer install
- cp .env.example .env
- Update `.env` and set your database credentials
- php artisan key:generate 
- composer require jenssegers/mongodb:*
- composer require tymon/jwt-auth
- composer require imanrjb/laravel-mongodb 
- composer require predis/predis
- php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
- php artisan jwt:secret
- php artisan migrate
- php artisan db:seed
- php artisan serve

## SWAGGER
- composer require "darkaonline/l5-swagger"
- php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
- Add `.env`:  L5_SWAGGER_GENERATE_ALWAYS=true
- php artisan l5-swagger:generate
- Url: http://localhost:8000/api/documentation
- 

## Unit Test
#### run PHPUnit
```bash
# run PHPUnit all test cases
vendor/bin/phpunit
# or Feature test only
vendor/bin/phpunit --testsuite Feature
```
 
ENDPOINTS
```bash
GET access token 
POST ->  http://127.0.0.1:8000/api/auth/auth
  Body
  {
    "username" : "tester1",
    "password" : "tester1"
  }
```
```bash
ADD
POST ->  http://127.0.0.1:8000/api/lead
  Body
  {
    "name": "",
    "source": "",
    "owner": 0
  }
```
```bash
GET by ID
GET  ->  http://127.0.0.1:8000/api/auth/lead/{id}
```
```bash
GET All
GET  ->  http://127.0.0.1:8000/api/leads
```
