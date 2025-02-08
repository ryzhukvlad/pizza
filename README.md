# Pizza

Laravel Backend приложение с API для сайта пиццерии.

## Содержание

- [Установка](#установка)
- [Работа с запросами](#работа-с-запросами)

## Установка

1. Клонируйте репозиторий:
```bash
git clone https://github.com/ryzhukvlad/pizza.git
```

2. Перейдите в директорию проекта:
```bash
cd pizza
```

3. Настройте файл конфигурации:
```bash
cp .env.example .env
```

4. Установите зависимости Composer, используя Docker:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```
5. Установите [alias sail](https://laravel.com/docs/11.x/sail#configuring-a-shell-alias) в конфигурацию командной оболочки
```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

6. Запустите приложение:
```bash
sail up -d
```

7. Запустите миграции и сидеры:
```bash
sail art migrate --seed
``` 
8. Запустите тесты:
```bash
sail art test
```

9. Создать пользователя с правами администратора:
```bash
sail art app:make-admin {name} {email} {password}
```

## Работа с запросами
Для использования и тестрования API вручную:

* Коллекция для Postman [коллекцию запросов](Pizza.postman_collection.json)
* перейти по адресу [OpenApi документации](http://localhost:81/docs/api)

Авторизация осуществляется с помощью Bearer Token.\

Получение токена (аутентификация) осуществляется обращением POST запросом на user/login 
(пример можно посмотреть в коллекции или документации)
