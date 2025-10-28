## 🛠️ Запуск проекта

Все операции по сборке, запуску и управлению проектом выполняются с помощью `docker-compose` из корневой директории проекта.



### 1. Сборка и запуск контейнеров

#### 1.1 Команд для первого запуска

```bash
  docker compose -f docker/docker-compose.yaml up --build -d
```

#### 1.2 Команда для повторных запусков
```bash
  docker compose -f docker/docker-compose.yaml up -d
```

#### 1.3 Остановка проекта
```bash
  docker compose -f docker/docker-compose.yaml down
```



### 2. Установка зависимостей проекта

#### 2. Установка зависимостей Composer
```bash
  docker-compose -f docker/docker-compose.yaml exec -w /app php-fpm composer install
```


### 3. Применение миграций

#### 3.1 Команда выполнения миграции со структурой данных
```bash
  cat migrations/test_db_structure.sql | docker compose -f docker/docker-compose.yaml exec -T mysql mysql -u root -pyour_mysql_root_password yii2_db
```

#### 3.2 Команда выполнения миграции с дампом данных
```bash
  cat migrations/test_db_data.sql | docker compose -f docker/docker-compose.yaml exec -T mysql mysql -u root -pyour_mysql_root_password yii2_db
```

#### 3.3 Миграции структуры БД (добавление внешних ключей и индексов)
```bash  
  docker-compose -f docker/docker-compose.yaml exec -w /app php-fpm php yii migrate
```



### 4. Ссылка на страницу модуля
```
http:localhost:8080/order/table
```