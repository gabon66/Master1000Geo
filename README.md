# Proyecto de Simulación de Torneos

Este proyecto es una API en Symfony que permite simular torneos deportivos masculinos y femeninos, gestionando jugadores, partidos y resultados.

## Tabla de Contenidos

1.  [Requisitos del Sistema](#requisitos-del-sistema)
2.  [Instalación](#instalación)
3.  [Configuración](#configuración)
4.  [Ejecución de la Aplicación](#ejecución-de-la-aplicación)
5.  [Ejecución de Migraciones de Doctrine](#ejecución-de-migraciones-de-doctrine)
6.  [Carga de Datos de Prueba (Jugadores)](#carga-de-datos-de-prueba-jugadores)
7.  [Endpoints de la API](#endpoints-de-la-api)
8.  [Uso de Swagger UI](#uso-de-swagger-ui)
9.  [Pruebas](#pruebas)
10. [Lógica Adicional](#lógica-adicional)
11. [Contribución (Opcional)](#contribución-opcional)
12. [Notas Adicionales](#notas-adicionales)

## 1. Requisitos del Sistema

* PHP >= 8.1
* Composer
* Docker y Docker Compose

## 2. Instalación

1.  Clona el repositorio del proyecto:

    ```bash
    git clone <repositorio>
    cd <nombre_del_proyecto>
    ```

2.  Construye y levanta los contenedores Docker:

    ```bash
    docker compose up -d --build
    ```

3.  Instala las dependencias de Composer dentro del contenedor PHP:

    ```bash
    docker compose exec app composer install
    ```

## 3. Configuración

La configuración se maneja principalmente a través del archivo `docker-compose.yml` y las variables de entorno definidas allí.

## 4. Ejecución de la Aplicación

La aplicación estará disponible en `http://localhost:8080` (o la URL que hayas configurado en el `compose.yml`).

## 5. Ejecución de Migraciones de Doctrine

```bash
docker compose exec app php bin/console doctrine:migrations:migrate -n
```
Este comando aplicará cualquier migración pendiente a la base de datos, creando las tablas necesarias.

6. Carga de Datos de Prueba (Jugadores)
   Para cargar datos de prueba (20 jugadores) en la base de datos, puedes ejecutar el siguiente comando:

```bash
docker compose exec app php bin/console doctrine:fixtures:load
```

## 7. Endpoints de la API

* `GET /api/players`: Obtiene todos los jugadores, con opción de filtrar, ordenar y limitar.
    * Parámetros de consulta opcionales:
        * `gender` (`male` o `female`) - Filtra por género.
        * `orderBy` (nombre de la propiedad, ej: `name`, `ability`) - Ordena por esta propiedad.
        * `orderDirection` (`asc` o `desc`) - Dirección de la ordenación.
        * `limit` (entero) - Limita el número de resultados.
    * Respuesta (JSON): Un array de objetos jugador.
* `POST /api/players`: Crea un nuevo jugador.
    * Request Body (JSON): Debe contener los datos del jugador (nombre, género, habilidades, edad).
    * Respuesta (JSON): El objeto del jugador recién creado.
* `GET /api/players/top/{gender}`: Obtiene los jugadores mejor rankeados por género.
    * Parámetro de ruta: `gender` (`male` o `female`).
    * Respuesta (JSON): Un array de objetos jugador ordenados por puntos.
* `DELETE /api/players/{id}`: Elimina un jugador por su ID.
    * Parámetro de ruta: `id` (el ID del jugador a eliminar).
    * Respuesta (JSON): Un mensaje de éxito o un error si el jugador no se encuentra.
* `PUT /api/players/{id}/skills`: Actualiza las habilidades de un jugador por su ID.
    * Parámetro de ruta: `id` (el ID del jugador a actualizar).
    * Request Body (JSON): Debe contener las habilidades a actualizar (strength, velocity, reaction, ability). Los campos no proporcionados no se modifican.
    * Respuesta (JSON): El objeto del jugador actualizado.
* `GET /api/tournaments/`: Obtiene todos los torneos, con opción de filtrar por género.
    * Parámetro de consulta opcional: `gender` (`male` o `female`).
    * Respuesta (JSON): Un array de objetos torneo, cada uno con su ID, nombre, fecha de inicio, fecha de fin, género e información del ganador (si existe).
* `POST /api/tournaments/simulate/male`: Simula un torneo masculino y devuelve el ganador.
    * Respuesta (JSON): Un mensaje de éxito y la información del ganador (objeto Player).
* `POST /api/tournaments/simulate/female`: Simula un torneo femenino y devuelve la ganadora.
    * Respuesta (JSON): Un mensaje de éxito y la información de la ganadora (objeto Player).

8. Uso de Swagger UI
   Para explorar y probar los endpoints de la API de forma interactiva, puedes utilizar Swagger UI. Está disponible en la ruta /api/doc de la aplicación: http://localhost:8080/api/doc.

9. Pruebas
   Si has implementado pruebas unitarias o funcionales, puedes ejecutarlas dentro del contenedor PHP:

```bash
docker compose exec app ./vendor/bin/phpunit
```

## 10. Lógica Adicional

* **Simulación de Torneos:** La simulación de torneos determina los ganadores de los partidos de la siguiente manera:
    * **Hombres:** Se consideran principalmente las habilidades de **fuerza (`strength`)** y **velocidad (`velocity`)** para determinar el ganador.
    * **Mujeres:** Se considera principalmente la habilidad de **reacción (`reaction`)** para determinar la ganadora.
      La habilidad general (`ability`) también influye en la probabilidad de victoria.
* **Doping:** Se ha implementado una lógica para simular pruebas de doping. Los jugadores tienen una probabilidad de ser seleccionados para una prueba, y si el resultado es positivo, son descalificados del torneo y sus puntos acumulados quedan en 0.
* **Puntos de Jugador:** Los jugadores ganan puntos en función de su desempeño en los torneos. Estos puntos se pueden utilizar para ordenar a los jugadores.