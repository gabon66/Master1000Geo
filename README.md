Aquí te sugiero una estructura para tu README.md:# Proyecto de Simulación de Torneos

Este proyecto es una API en Symfony que permite simular torneos deportivos masculinos y femeninos, gestionando jugadores, partidos y resultados.

## Tabla de Contenidos

1.  [Requisitos del Sistema](#requisitos-del-sistema)
2.  [Instalación](#instalación)
3.  [Configuración](#configuración)
4.  [Ejecución de la Aplicación](#ejecución-de-la-aplicación)
5.  [Ejecución de Migraciones de Doctrine](#ejecución-de-migraciones-de-doctrine)
6.  [Endpoints de la API](#endpoints-de-la-api)
7.  [Uso de Swagger UI](#uso-de-swagger-ui)
8.  [Pruebas (Opcional)](#pruebas-opcional)
9.  [Contribución (Opcional)](#contribución-opcional)
10. [Notas Adicionales](#notas-adicionales)

## 1. Requisitos del Sistema

* PHP >= 8.1
* Composer
* Docker y Docker Compose (recomendado para un entorno consistente)
* Una base de datos compatible con Doctrine (ej: MySQL, PostgreSQL)

## 2. Instalación

Si estás utilizando Docker (recomendado):

1.  Clona el repositorio del proyecto:

    git clone <tu\_repositorio>
    cd <nombre\_del\_proyecto>

2. Construye y levanta los contenedores Docker:
    docker compose up -d --build

3.  Instala las dependencias de Composer dentro del contenedor PHP:

    docker compose exec app composer install
4. Correr migraciones
    





## 4. Ejecución de la Aplicación

Si estás utilizando Docker:

La aplicación estará disponible en  `http://localhost:8080`  (o la URL que hayas configurado en tu  `docker-compose.yml`).

Si no estás utilizando Docker:

Puedes utilizar el servidor web integrado de Symfony:

symfony server:start -d


La aplicación estará disponible en la URL proporcionada por el comando (normalmente  `http://127.0.0.1:8000`).

## 5. Ejecución de Migraciones de Doctrine

Si estás utilizando Docker:

docker compose exec app php bin/console doctrine:migrations:migrate -n


Si no estás utilizando Docker:

php bin/console doctrine:migrations:migrate -n


Este comando aplicará cualquier migración pendiente a tu base de datos, creando las tablas necesarias.

## 6. Endpoints de la API

Aquí tienes una lista de los endpoints principales de la API:

* `POST /api/tournaments/simulate/male`: Simula un torneo masculino.
    * Respuesta (JSON): Un mensaje de éxito y la información del ganador (objeto Player).
* `POST /api/tournaments/simulate/female`: Simula un torneo femenino.
    * Respuesta (JSON): Un mensaje de éxito y la información de la ganadora (objeto Player).
* `GET /api/tournaments/`: Obtiene todos los torneos, con opción de filtrar por género.
    * Parámetro de consulta opcional: `gender` (puede ser `male` o `female`).
    * Respuesta (JSON): Un array de objetos torneo, cada uno con su ID, nombre, fecha de inicio, fecha de fin, género e información del ganador (si existe).
* `GET /api/players/`: Obtiene todos los jugadores, con opción de filtrar, ordenar y limitar.
    * Parámetros de consulta opcionales:
        * `gender` (`male` o `female`) - Filtra por género.
        * `orderBy` (nombre de la propiedad, ej: `name`, `ability`) - Ordena por esta propiedad.
        * `orderDirection` (`asc` o `desc`) - Dirección de la ordenación.
        * `limit` (entero) - Limita el número de resultados.
    * Respuesta (JSON): Un array de objetos jugador.

## 7. Uso de Swagger UI

Para explorar y probar los endpoints de la API de forma interactiva, puedes utilizar Swagger UI. Está disponible en la ruta  `/api/doc`  de tu aplicación.

* Si usas Docker:  `http://localhost:8080/api/doc`
* Si no usas Docker (con  `symfony server:start`):  `http://127.0.0.1:8000/api/doc`

## 8. Pruebas (Opcional)

Si has implementado pruebas unitarias o funcionales, incluye instrucciones sobre cómo ejecutarlas aquí.

\# Ejemplo para ejecutar pruebas con PHPUnit (si lo utilizas)
./vendor/bin/phpunit


## 9. Contribución (Opcional)

Si deseas que otros contribuyan al proyecto, incluye pautas sobre cómo hacerlo.

## 10. Notas Adicionales

* Cualquier otra información relevante sobre la arquitectura, decisiones de diseño, o problemas conocidos.




`
docker compose up --build
`

Creamos migras

`
docker compose exec app php bin/console doctrine:migrations:diff
`

Corremos migras y creamos las tablas

`docker compose exec app php bin/console doctrine:migrations:migrate
`

Generamos algunos players:

`docker compose exec app php bin/console doctrine:fixtures:load
`