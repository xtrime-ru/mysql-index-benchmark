#!/usr/bin/env bash

docker-compose-wait \
&& php index.php "$@"