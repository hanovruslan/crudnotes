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
### recreate database and load fixtures ###
```bash
bin/console doctrine:database:drop --if-exists -f \
&& bin/console doctrine:database:create --if-not-exists \
&& bin/console doctrine:migrations:migrate -n \
&& bin/console doctrine:fixtures:load -n
```
### create migration ###
```bash
bin/console doctrine:migrations:diff --allow-empty-diff --line-length=120 --formatted -n
```
## api helpers ##
### users ###
#### create user #### 
```bash
curl http://admin:admin@127.0.0.1:8000/users \
    --header "Content-Type: application/json" \
    --data '{"username":"username","fullname":"fullname"}' \
    --request POST
```
#### read user #### 
```bash
curl http://admin:admin@127.0.0.1:8000/users/21 \
    --request GET
```
#### update user #### 
```bash
curl http://admin:admin@127.0.0.1:8000/users/21 \
    --header "Content-Type: application/json" \
    --data '{"fullname":"James Bond"}' \
    --request PUT
```
#### delete user #### 
```bash
curl http://admin:admin@127.0.0.1:8000/users/21 \
    --request DELETE
```
### notes ###
#### create note ####
```bash
curl http://note:note@127.0.0.1:8000/notes \
    --header "Content-Type: application/json" \
    --data '{"i_am":"username","title":"title","body":"body"}' \
    --request POST
```
#### read note #### 
```bash
curl http://note:note@127.0.0.1:8000/notes/21 \
    --data '{"i_am":"username"}' \
    --request GET
```
#### update note #### 
```bash
curl http://note:note@127.0.0.1:8000/notes/1 \
    --header "Content-Type: application/json" \
    --data '{"i_am":"username_1","title":"Foo Bar","body":"Eu non diam phasellus vestibulum lorem sed risus ultricies tristiqu"}' \
    --request PUT
# or by share write access
curl http://note:note@127.0.0.1:8000/notes/1 \
    --header "Content-Type: application/json" \
    --data '{"i_am":"username_12","title":"Foobar","body":"The etymology of foobar is generally traced to the World War II military slang FUBAR"}' \
    --request PUT
```
#### delete note #### 
```bash
curl http://note:note@127.0.0.1:8000/notes/21 \
    --data '{"i_am":"username"}' \
    --request DELETE
```
#### list notes ####
```bash
curl http://note:note@127.0.0.1:8000/notes \
    --data '{"i_am":"username_1"}' \
    --request GET
```
#### share note ####
```bash
curl http://note:note@127.0.0.1:8000/notes/1/share \
    --header "Content-Type: application/json" \
    --data '{"i_am":"username_1","access"=>"read","usernames":["username_3","username_4"]}' \
    --request PUT
```
#### deshare note ####
```bash
curl http://note:note@127.0.0.1:8000/notes/1/share \
    --header "Content-Type: application/json" \
    --data '{"i_am":"username_1","access"=>"read","usernames":["username_3","username_4"]}' \
    --request DELETE
```