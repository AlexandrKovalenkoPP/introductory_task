# Используем официальный образ Nginx
FROM nginx:stable-alpine

# Копируем кастомный файл конфигурации Nginx
COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Копируем скрипт для обработки переменных окружения
# Nginx не умеет напрямую читать переменные из Docker-Compose,
# поэтому мы используем скрипт-обработчик.
COPY ./docker/nginx/entrypoint.sh /docker-entrypoint.sh

# Даем права на выполнение скрипту
RUN chmod +x /docker-entrypoint.sh

# Устанавливаем скрипт в качестве точки входа
ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["nginx", "-g", "daemon off;"]