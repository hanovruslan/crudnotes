# crud notes #



```bash

( \
export NAME=crudnotes && docker run \
    -e MYSQL_ROOT_PASSWORD=root \
    -e MYSQL_DATABASE=${NAME} \
    -e MYSQL_USER=${NAME} \
    -e MYSQL_PASSWORD=${NAME} \
    --rm -d --name ${NAME} mysql:5.7.19 \
)

( \
export NAME=crudnotes && \
    echo $(docker inspect --format '{{ .NetworkSettings.IPAddress }}' ${NAME}) \
)

( \
export NAME=crudnotes && mysql \
    -h $(docker inspect --format '{{ .NetworkSettings.IPAddress }}' ${NAME}) \
    -u ${NAME} \
    -n ${NAME} \
    --password=${NAME} \
)

```


```

curl --header "Content-Type: application/json" \
  --request POST \
  --data '{"username":"username","fullname":"fullname"}' \
  http://admin:admin@127.0.0.1:8000/users

curl --header "Content-Type: application/json" \
  --request PUT \
  --data '{"fullname":"fullname"}' \
  http://admin:admin@127.0.0.1:8000/users/20

curl -X "DELETE" http://admin:admin@127.0.0.1:8000/users/20
curl -X "GET" http://admin:admin@127.0.0.1:8000/users/20
```

```
curl --header "Content-Type: application/json" \
  --request POST \
  --data '{"title":"title","body":"body","username":"username"}' \
  http://note:note@127.0.0.1:8000/notes
```

```sql
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE note;
TRUNCATE TABLE `user`;
SET FOREIGN_KEY_CHECKS = 1;
```

```bash
./bin/console doctrine:database:drop --if-exists -f \
&& ./bin/console doctrine:database:create --if-not-exists \
&& ./bin/console doctrine:migrations:migrate -n \
&& ./bin/console doctrine:fixtures:load -n
```
