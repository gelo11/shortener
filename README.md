# Сервис коротких ссылок на Yii2 + Docker

Простой и быстрый сервис для сокращения ссылок с поддержкой аналитики, учётом регистра в коротких кодах и проверкой ботов.

---

## 📋 Функционал

- Ввод длинной ссылки → получение короткой (AJAX)
- Уникальный короткий URL: `http://yourdomain/AbC123`
- Учёт регистра: `AbC123 ≠ abc123`
- Повторные ссылки возвращают тот же короткий URL
- Моментальный 301-редирект
- Запись переходов (если пользователь не бот)
- Проверка ботов через `http://qnits.net/api/checkUserAgent`
- SQL-запрос для аналитики по месяцам и топу ссылок
- Полная Docker-инфраструктура (Nginx + PHP-FPM + MySQL)
- Юнит-тесты

---

## 🚀 Установка и запуск

### 1. Клонируйте репозиторий

```bash
git clone https://github.com/gelo11/shortener.git
cd shortener

# Соберите и запустите контейнеры
docker-compose up -d --build

# Установить зависимости Yii2
docker-compose exec php composer install

# Установить HTTP-клиент (для BotDetector)
docker-compose exec php composer require yiisoft/yii2-httpclient

# Установить Codeception (для тестов)
docker-compose exec php composer require --dev codeception/codeception

# Выполнить миграции базы данных
docker-compose exec php php yii migrate

# Создать и разрешить запись в папку runtime
docker exec -it shortener-php-1 bash
chmod -R 777 runtime

# Запустить конкретный тест
docker-compose exec php vendor/bin/codecept run unit LinkTest
```

Откройте в браузере:

👉 http://localhost:8080

## Аналитика: SQL-запрос
### Получить топ ссылок по месяцам:

```sql
SELECT
    DATE_FORMAT(FROM_UNIXTIME(v.visited_at), '%Y-%m') AS `Месяц (перехода по ссылке)`,
    l.original_url AS `Ссылка`,
    COUNT(v.id) AS `Кол-во переходов`,
    RANK() OVER (PARTITION BY DATE_FORMAT(FROM_UNIXTIME(v.visited_at), '%Y-%m') ORDER BY COUNT(v.id) DESC) AS `Позиция в топе месяца по переходам`
FROM
    visits v
        JOIN
    links l ON v.link_id = l.id
GROUP BY
    `Месяц (перехода по ссылке)`, l.original_url
ORDER BY
    `Месяц (перехода по ссылке)` DESC, `Кол-во переходов` DESC;
