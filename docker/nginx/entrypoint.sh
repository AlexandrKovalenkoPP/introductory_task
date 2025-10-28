#!/bin/sh

NGINX_CONF_PATH="/etc/nginx/conf.d/default.conf"

envsubst '$APP_DOMAIN_ENV $YII_APP_PUBLIC_DIR_ENV' < ${NGINX_CONF_PATH} > ${NGINX_CONF_PATH}.tmp

cat ${NGINX_CONF_PATH}.tmp > ${NGINX_CONF_PATH}
rm ${NGINX_CONF_PATH}.tmp

exec "$@"
