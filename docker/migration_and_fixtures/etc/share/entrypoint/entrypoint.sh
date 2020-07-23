#!/usr/bin/env sh

. .env

( \
  cd "${_SOURCE_ROOT}" \
  && bin/console doctrine:migrations:migrate -n\
  && bin/console doctrine:fixtures:load -n \
)
