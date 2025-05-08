# Master1000Geo

# Instalacion



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