# Руководство по использованию Swagger UI

## Что такое Swagger UI?

Swagger UI - это инструмент, который позволяет визуализировать и взаимодействовать с API без необходимости писать код. Он автоматически генерирует интерактивную документацию на основе спецификации OpenAPI (ранее известной как Swagger).

## Как получить доступ к Swagger UI в этом проекте

1. Запустите проект

2. Откройте в браузере URL:
   ```
   http://localhost:8000/api/documentation
   ```

## Аутентификация

API защищен с помощью API-ключа. Для использования API через Swagger UI:

1. Найдите кнопку "Authorize" в правом верхнем углу интерфейса Swagger UI
2. Введите ваш API-ключ в поле "X-API-KEY"
3. Нажмите "Authorize"

После этого все запросы, отправляемые через Swagger UI, будут включать ваш API-ключ.

## Доступные эндпоинты

В API доступны следующие группы эндпоинтов:

### Buildings (Здания)
- GET /api/buildings - получить список всех зданий
- POST /api/buildings - создать новое здание
- GET /api/buildings/{id} - получить информацию о конкретном здании
- PUT /api/buildings/{id} - обновить информацию о здании
- DELETE /api/buildings/{id} - удалить здание

### Activities (Виды деятельности)
- GET /api/activities - получить список всех видов деятельности
- POST /api/activities - создать новый вид деятельности
- GET /api/activities/{id} - получить информацию о конкретном виде деятельности
- PUT /api/activities/{id} - обновить информацию о виде деятельности
- DELETE /api/activities/{id} - удалить вид деятельности
- GET /api/activities/tree - получить иерархическую структуру видов деятельности

### Organizations (Организации)
- GET /api/organizations - получить список всех организаций
- POST /api/organizations - создать новую организацию
- GET /api/organizations/{id} - получить информацию о конкретной организации
- PUT /api/organizations/{id} - обновить информацию об организации
- DELETE /api/organizations/{id} - удалить организацию
- GET /api/buildings/{buildingId}/organizations - получить организации в конкретном здании
- GET /api/activities/{activityId}/organizations - получить организации с конкретным видом деятельности
- GET /api/activities/{activityId}/organizations/recursive - получить организации с конкретным видом деятельности (включая дочерние виды деятельности)
- POST /api/organizations/search/location - найти организации по местоположению
- POST /api/organizations/search/name - найти организации по названию

## Как использовать Swagger UI

1. **Просмотр документации**: Swagger UI отображает все доступные эндпоинты, сгруппированные по тегам. Нажмите на эндпоинт, чтобы увидеть подробную информацию о нем.

2. **Тестирование API**:
   - Выберите нужный эндпоинт
   - Нажмите кнопку "Try it out"
   - Заполните необходимые параметры
   - Нажмите "Execute"
   - Swagger UI отправит запрос и покажет результат, включая статус ответа, заголовки и тело ответа

3. **Схемы данных**: Swagger UI также отображает схемы данных, используемые в API. Это помогает понять структуру данных, которые вы отправляете и получаете.

## Улучшение документации API

Текущая документация API является базовой. Для улучшения документации можно добавить аннотации OpenAPI к методам контроллеров. Например:

```php
/**
 * @OA\Get(
 *     path="/api/buildings",
 *     summary="Get list of buildings",
 *     tags={"Buildings"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Building")
 *         )
 *     ),
 *     security={{"ApiKeyAuth": {}}}
 * )
 */
public function index()
{
    // ...
}
```

## Дополнительные ресурсы

- [Официальная документация Swagger UI](https://swagger.io/tools/swagger-ui/)
- [Документация OpenAPI](https://swagger.io/specification/)
- [Документация пакета L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)
