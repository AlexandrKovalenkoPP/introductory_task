- команда для сборки проекта
  docker-compose -f docker/docker-compose.yaml up --build -d
- команда выполнения миграций через yii2
  docker-compose -f docker/docker-compose.yaml exec -w /app php-fpm php yii migrate
- команда выполнения миграции с дампом данных
  cat migrations/test_db_data.sql | docker compose -f docker/docker-compose.yaml exec -T mysql mysql -u root -pyour_mysql_root_password yii2_db
- 