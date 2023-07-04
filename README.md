# bot-platform

Проект по работе с доступными к интеграции системами ботов. На данный момент vk, telegram

## Локальная разработка

### Первый запуск
```bash
make init #команда при первом запуске проекта
```

### Запуск и проверка очередей
Веб интерфейс [rabbitmq](http://localhost:15672/)
Закинуть сообщение в очередь можно по api [api/example/rabbit](http://localhost:8057/api/example/rabbit)
или через команду получение сообщений с [бота](#работа-с-телеграмм-ботом)
```bash
make consumer_bot_platform # запустить все консюмеры
```

### Запуск ecs, phpstan и phpunit
Запустить автоматическое исправление кода и его проверку
```bash
make check # запуск всех линтеров
make ecs_fix # отдельно ecs
make phpstan # отдельно phphstan
```

### Запуск тестов
```bash
make unit # запуск unit-тестов
```

### IDE
Для phpstorm советуется установить плагин [symfony](https://plugins.jetbrains.com/plugin/7219-symfony-support)
и включить его PHP->symfony->enable

### [Документация API](http://localhost:8057/doc/doc)

### [Profiler](http://localhost:8057/_profiler)

#### Остальные команды
```bash
make #то же самое, что и make check
make up #запустить контейнеры
make check #запуск линтеров + unit-тесты
make composer #composer install
make clear #bin/console cache:clear
make cli # войти в контейнер в оболочку bash
make ecs # запуск ecs
make ecs_fix # запуск ecs --fix
make phpstan # запуск phpstan
make migration_generate # создать миграцию
make migration # применить все не примененные миграции
make consumer_bot_platform # запустить все очереди на прослушку
```

#### Работа с админкой
Админка доступна по адресу - http://localhost:8057/admin

#### Работа с телеграмм ботом
Нужно сгенерировать секретный ключ
```bash
bin/console encryption:generate-key
```
То что получили положить в .env.local (создать, если ранее этого не делали)
```bash
ENCRYPTION_KEY=
```
Далее через команду создать себе локального бота с его [токеном](https://sendpulse.com/knowledge-base/chatbot/telegram/create-telegram-chatbot). С/без выбора группы ботов, если не указана, выберется случайно
```bash
bin/console tg:create-bot -t {token} -g {?telegram_bot_group_id?}
```
После чего запустить команду на опрос вашего бота
```bash
bin/console tg:get-update
```
И запустить очереди
```bash
make consumer_telegram_bot
```

## Команды, которые могут помочь
Команды для взаимодействия с ботами
```bash
bin/console list tg
bin/console list vk
```
Команды для шифрования
```bash
bin/console list encryption
```

## Подготовленная архитектура
* [UuidService](src/Infrastructure/Utils/UuidService.php) - генерация UUID V7, в ядре использовать через `\App\Domain\SharedCore\Port\Utils\IUuidService`
* [RequestJsonDtoResolver](src/Infrastructure/ArgumentResolver/RequestJsonDtoResolver.php) - резольвер аргументов. Если в контроллере запросить аргумент с типом `\App\Domain\SharedCore\Dto\Request\JsonRequestDto`, то он попробует десериализовать json-тело запроса в Dto
* [ApiExceptionSubscriber](src/Infrastructure/EventSubscriber/ApiExceptionSubscriber.php) - перехват исключений и автоматическое формирование правильного ответа со стандартизированной ошибкой
* [ApiResponseSubscriber](src/Infrastructure/EventSubscriber/ApiResponseSubscriber.php) - перехват ответов не реализующих интерфейс Response и формирование типизированного ответа