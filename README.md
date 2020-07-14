# crud notes #

## mysql/docker helpers ##

### create ###

```bash
( \
export NAME=crudnotes && docker run \
    -e MYSQL_ROOT_PASSWORD=root \
    -e MYSQL_DATABASE=${NAME} \
    -e MYSQL_USER=${NAME} \
    -e MYSQL_PASSWORD=${NAME} \
    --rm -d --name ${NAME} mysql:5.7.19 \
)
```
### find ip ###
```bash
( \
export NAME=crudnotes && \
    echo $(docker inspect --format '{{ .NetworkSettings.IPAddress }}' ${NAME}) \
)
```
### connect ###
```bash
( \
export NAME=crudnotes && mysql \
    -h $(docker inspect --format '{{ .NetworkSettings.IPAddress }}' ${NAME}) \
    -u ${NAME} \
    -n ${NAME} \
    --password=${NAME} \
)
```
### recreate db and load fixtures ###
```bash
./bin/console doctrine:database:drop --if-exists -f \
&& ./bin/console doctrine:database:create --if-not-exists \
&& ./bin/console doctrine:migrations:migrate -n \
&& ./bin/console doctrine:fixtures:load -n
```

## api helpers ##

### users ###

#### create user #### 
```bash
curl --header "Content-Type: application/json" \
    --request POST \
    --data '{"username":"username","fullname":"fullname"}' \
    http://admin:admin@127.0.0.1:8000/users
```
#### read user #### 
```bash
curl -X "GET" http://admin:admin@127.0.0.1:8000/users/21
```
#### update user #### 
```bash
curl --header "Content-Type: application/json" \
    --request PUT \
    --data '{"fullname":"James Bond"}' \
    http://admin:admin@127.0.0.1:8000/users/21
```
#### delete user #### 
```bash
curl -X "DELETE" http://admin:admin@127.0.0.1:8000/users/21
```
### notes ###

#### create note ####
```
curl --header "Content-Type: application/json" \
    --request POST \
    --data '{"title":"title","body":"body","username":"username"}' \
    http://note:note@127.0.0.1:8000/notes
```
#### update note #### 
```bash
curl --header "Content-Type: application/json" \
    --request PUT \
    --data '{"title":"Foo Bar","username":"username","body":"Eu non diam phasellus vestibulum lorem sed risus ultricies tristiqu"}' \
    http://note:note@127.0.0.1:8000/notes/201
```
#### delete user #### 
```bash
curl -X "DELETE" \
    --data '{"username":"username"}' \
    http://note:note@127.0.0.1:8000/notes/201
```