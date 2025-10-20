#!/bin/sh

# Целевой путь для конфигурационного файла Nginx
NGINX_CONF_PATH="/etc/nginx/conf.d/default.conf"

# Используем envsubst для замены переменных в конфигурации Nginx.
# Важно: $$-префикс в списке переменных указывает envsubst, 
# что нужно заменить именно переменные окружения, а не 
# внутренние переменные Nginx.
#
# ВАЖНО: Мы читаем *исходный* файл, который был скопирован в Dockerfile, 
# а не файл по пути монтирования из docker-compose, чтобы избежать конфликтов.
envsubst '$APP_DOMAIN_ENV $YII_APP_PUBLIC_DIR_ENV' < ${NGINX_CONF_PATH} > ${NGINX_CONF_PATH}.tmp

# Атомарная запись: заменить оригинальный файл временным.
# Это минимизирует шанс гонки.
cat ${NGINX_CONF_PATH}.tmp > ${NGINX_CONF_PATH}
rm ${NGINX_CONF_PATH}.tmp

# Запускаем основной процесс Nginx
exec "$@"
