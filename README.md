## 🛠️ Запуск проекта

Все операции по сборке, запуску и управлению проектом выполняются с помощью `docker-compose` из корневой директории проекта.

### 1. Сборка и запуск контейнеров

Эта команда собирает образы (если они не существуют), создает контейнеры и запускает их в фоновом режиме (`-d`).

```bash
docker compose -f docker/docker-compose.yaml up --build -d
```

Остановка проекта
```bash
docker compose -f docker/docker-compose.yaml down
```


### 2. Миграции структуры БД
```bash  
docker-compose -f docker/docker-compose.yaml exec -w /app php-fpm php yii migrate
```

### 3. Команда выполнения миграции с дампом данных
```bash
cat migrations/test_db_data.sql | docker compose -f docker/docker-compose.yaml exec -T mysql mysql -u root -pyour_mysql_root_password yii2_db
```

### 4. Ссылка на страницу модуля
```
http:localhost:8080/order/table
```