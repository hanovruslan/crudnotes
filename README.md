# crud notes #



```bash

(\
export NAME=crudnotes && docker run \
    -e MYSQL_ROOT_PASSWORD=root \
    -e MYSQL_DATABASE=${NAME} \
    -e MYSQL_USER=${NAME} \
    -e MYSQL_PASSWORD=${NAME} \
    --rm -d --name ${NAME} mysql:5.7.17 \
)

(\
export NAME=crudnotes && \
    echo $(docker inspect --format '{{ .NetworkSettings.IPAddress }}' ${NAME}) \
)

(\
export NAME=crudnotes && mysql \
    -h $(docker inspect --format '{{ .NetworkSettings.IPAddress }}' ${NAME}) \
    -u ${NAME} \
    -n ${NAME} \
    --password=${NAME} \
)

```