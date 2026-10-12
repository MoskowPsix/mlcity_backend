# Контракт результатов: hrono-back → Вокруг v1

Статус: пилотный контракт ingest на стороне Вокруг (реализован 2026-10-06).  
Дополняет, но не изменяет `01-import-contract.md`.

**Клиент ingest с 2026-10-13:** только **hrono-back** (forward после приёма от Чекпоинта).  
Телефон **не** вызывает этот endpoint напрямую. API телефона → hrono-back: [08-hrono-back-checkpoint-api.md](./08-hrono-back-checkpoint-api.md).

```text
Чекпоинт (custom heat)
  → POST hrono-back /api/checkpoint/…   (см. 08)
  → hrono-back persist + forward
  → POST mlcity /api/checkpoint/competitions/{eventId}/results   (этот документ)
  → GET /api/events/{eventId}/checkpoint-results  (карточка Вокруг)
```

---

## Авторизация

`POST /api/checkpoint/competitions/{eventId}/results` требует Sanctum (Bearer пользователя Вокруг).

- Автор события дополнительно должен иметь `checkpoint_publish_override`.
- Принявший член комиссии этого события отправляет результаты без этого флага и без `checkpoint_access`.
- Сервер не принимает право публикации из payload.

hrono-back **пробрасывает** Bearer, полученный от приложения Чекпоинт. Ответы 401/403 mlcity отдаются приложению через ответ HB.

---

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

Типы: `heat.open`, `results.upsert`, `heat.finish`, `live.close`.

| Тип | Смысл |
|---|---|
| `heat.open` | Открывает live для heat |
| `results.upsert` | Обновляет строки live |
| `heat.finish` | Финальная таблица; закрывает заезд в итогах |
| `live.close` | Закрытие эфира без нового финала (если отделено от finish) |

`participant_id` — стабильный id комиссии Вокруг из контракта импорта.

---

## Чтение и восстановление

`GET /api/events/{eventId}/checkpoint-results` публичен для Event с включённым Checkpoint и всегда возвращает полный актуальный снимок: `live` и завершённые `heats[]`. Клиент после reconnect запрашивает его повторно.

При включённом Pusher сервер также публикует снимок в публичный канал `checkpoint.event.{eventId}`, событие `checkpoint.results.updated`. WebSocket — ускорение UI; REST-снимок остаётся источником восстановления.

---

## Связанные документы

- API Чекпоинт → hrono-back: [08-hrono-back-checkpoint-api.md](./08-hrono-back-checkpoint-api.md)
- Задачи этапа 3: [07-yougile-tasks-stage-3.md](./07-yougile-tasks-stage-3.md)
- Decision-log: запись 2026-10-13
