# Master1000Geo



docker compose exec app php bin/console doctrine:migrations:diff

docker compose exec app php bin/console doctrine:migrations:migrate

docker compose exec app php bin/console doctrine:fixtures:load