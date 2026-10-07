# Контракт результатов: Checkpoint → Вокруг v1

Статус: пилотный контракт, реализованный 2026-10-06 по задаче руководителя. Он дополняет, но не изменяет `01-import-contract.md`.

## Авторизация

`POST /api/checkpoint/competitions/{eventId}/results` требует Sanctum. Автор события дополнительно должен иметь `checkpoint_publish_override`. Принявший член комиссии этого события отправляет результаты без этого флага и без `checkpoint_access`. Сервер не принимает право публикации из payload.

## Событие

Каждая отправка содержит стабильный UUID `event_id`. Повторная доставка того же UUID возвращает актуальное состояние с `accepted: false` и не создаёт дублей.

```json
{
  "event_id": "2c594780-0084-4c31-a777-86884f5b5221",
  "type": "results.upsert",
  "occurred_at": "2026-10-06T12:30:00+05:00",
  "heat": {"external_id": "heat-1", "name": "Финал, М18"},
  "results": [{"participant_id": 55, "place": 1, "result": "00:35:12.420", "result_value_ms": 2112420}]
}
```

Типы: `heat.open`, `results.upsert`, `heat.finish`, `live.close`. `heat.open` открывает live, `results.upsert` обновляет строки, остальные закрывают заезд. `participant_id` — стабильный id комиссии Вокруг из контракта импорта.

## Чтение и восстановление

`GET /api/events/{eventId}/checkpoint-results` публичен для Event с включённым Checkpoint и всегда возвращает полный актуальный снимок: `live` и завершённые `heats[]`. Клиент после reconnect запрашивает его повторно.

При включённом Pusher сервер также публикует снимок в публичный канал `checkpoint.event.{eventId}`, событие `checkpoint.results.updated`. WebSocket — ускорение UI; REST-снимок остаётся источником восстановления.
