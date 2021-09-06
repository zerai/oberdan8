#### Development

Avviare l'ambiente di sviluppo tramite docker-compose.

```shell
docker-compose -f docker-compose.linux.yml up

or

docker-compose -f docker-compose.linux.yml up
```

Preparare il livello di persistenza 

```shell
docker exec -it app bin/console doctrine:schema:update --force

docker exec -it app bin/console doctrine:schema:update --force -e test
```

Caricare le fixture di sviluppo

```shell
docker exec -it app bin/console doctrine:fixtures:load --group stage
```

Aprire il browser: [http://127.0.0.1/admin/dashboard](http://127.0.0.1/admin/dashboard)