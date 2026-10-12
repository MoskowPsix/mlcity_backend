# API: Чекпоинт → hrono-back (checkpoint / этап 3)

Статус: **черновик контракта для реализации** (E3-01 / E3-02).  
Не путать с MX Arrival API (`/api/arrivals/…`).

Зеркало роли hrono-back в мотокроссе: телефон пишет сюда; сервер хранит снимок и **форвардит** в продукт зрителя (здесь — Вокруг / mlcity по [06-results-contract-v1.md](./06-results-contract-v1.md)).

---

## 1. Цепочка

```text
hrono-app (custom)
  → X-Api-Secret + Authorization: Bearer <vokrug>
  → hrono-back /api/checkpoint/…
  → persist (checkpoint_heats / results)
  → forward POST {MLCITY_API_URL}checkpoint/competitions/{eventId}/results
  → mlcity publish gate + snapshot
```

Телефон **не** вызывает mlcity ingest напрямую.

---

## 2. Авторизация

| Заголовок | Назначение |
|---|---|
| `X-Api-Secret` (или согласованный Bearer секрета) | Как у MX: middleware `api.secret`, конфиг `hrono.api_secret` / `API_SECRET` |
| `Authorization: Bearer <token>` | Токен пользователя Вокруг; **пробрасывается** в mlcity без локальной валидации прав publish |

Ошибки mlcity `401` / `403` (в т.ч. нет `publish_allowed`) hrono-back возвращает приложению с понятным телом (статус и message не глотать).

---

## 3. Конфиг hrono-back

Расширить `config/hrono.php` (имена ориентировочные):

| Ключ | Env | Смысл |
|---|---|---|
| `api_secret` | `API_SECRET` | Уже есть |
| `moto_api_url` | `MOTO_API_URL` | MX, не трогать |
| `mlcity_api_url` | `MLCITY_API_URL` | Базовый URL API Вокруг **с** `/api/` на конце, напр. `https://vokrug.example/api/` |

Forward URL: `{mlcity_api_url}checkpoint/competitions/{vokrugEventId}/results`.

---

## 4. Идентификаторы

| Поле | Смысл |
|---|---|
| `vokrug_event_id` | Id мероприятия Вокруг (из импорта competition) |
| `external_id` | Стабильный id heat на телефоне (локальный id custom heat / UUID) |
| `event_id` (UUID) | Идемпотентность **каждого** сообщения; тот же UUID при ретрае |

---

## 5. Роуты (зеркало MX: open / results / final / close)

Все под middleware `api.secret`.

### 5.1. Upsert meta heat

`POST /api/checkpoint/events/{vokrugEventId}/heats`

```json
{
  "external_id": "heat-local-42",
  "name": "Финал, М18",
  "heat_type": "mass_start_time"
}
```

Ответ: heat создан/обновлён в БД hrono-back. Может сразу forward `heat.open` **или** open отдельным вызовом stream/open (предпочтительно отдельно — как MX).

### 5.2. Открыть эфир

`POST /api/checkpoint/heats/{externalId}/stream/open`

```json
{
  "event_id": "2c594780-0084-4c31-a777-86884f5b5221",
  "occurred_at": "2026-10-13T10:00:00+05:00",
  "vokrug_event_id": 12345,
  "heat": {"external_id": "heat-local-42", "name": "Финал, М18"}
}
```

HB: пометить heat live → forward type `heat.open` в mlcity.

### 5.3. Live results

`POST /api/checkpoint/heats/{externalId}/results`

```json
{
  "event_id": "a1111111-0084-4c31-a777-86884f5b5221",
  "occurred_at": "2026-10-13T10:01:30+05:00",
  "vokrug_event_id": 12345,
  "heat": {"external_id": "heat-local-42", "name": "Финал, М18"},
  "results": [
    {
      "participant_id": 55,
      "place": 1,
      "result": "00:35:12.420",
      "result_value_ms": 2112420
    }
  ]
}
```

HB: сохранить последний snapshot → forward type `results.upsert`.  
При ретрае forward достаточно **последнего** upsert по heat (не копить все тики).

### 5.4. Final results

`POST /api/checkpoint/heats/{externalId}/final-results`

```json
{
  "event_id": "b2222222-0084-4c31-a777-86884f5b5221",
  "occurred_at": "2026-10-13T10:45:00+05:00",
  "vokrug_event_id": 12345,
  "heat": {"external_id": "heat-local-42", "name": "Финал, М18"},
  "results": [ … ]
}
```

HB: persist final → forward type `heat.finish`. Final **не терять** при ошибке сети к mlcity (очередь/retry на HB или повтор с телефона с тем же `event_id`).

### 5.5. Закрыть эфир

`POST /api/checkpoint/heats/{externalId}/stream/close`

```json
{
  "event_id": "c3333333-0084-4c31-a777-86884f5b5221",
  "occurred_at": "2026-10-13T10:46:00+05:00",
  "vokrug_event_id": 12345,
  "heat": {"external_id": "heat-local-42", "name": "Финал, М18"}
}
```

HB: снять live → forward type `live.close` (если ещё не закрыто finish’ем — по правилам mlcity).

---

## 6. Хранение в hrono-back

Минимальная модель (имена ориентировочные):

**`checkpoint_heats`**

- `id`, `vokrug_event_id`, `external_id` (unique с event), `name`, `heat_type`
- `stream_status`: `closed` | `open`
- `final_forwarded_at` nullable
- timestamps

**`checkpoint_heat_results`** или JSON-колонка `last_results` / `final_results` на heat

- последний live snapshot + финальный snapshot для ретрая forward

Не использовать таблицы `arrivals` / MX `final_results` as-is.

---

## 7. Ответы и ошибки

| Ситуация | Поведение HB |
|---|---|
| Успех + forward ok | `200`/`201`, тело с `accepted` от mlcity при наличии |
| Повтор того же `event_id` | Не плодить строки; вернуть актуальное состояние (`accepted: false` от mlcity допустимо) |
| mlcity 403 publish | Пробросить `403` приложению |
| mlcity 5xx / сеть | Persist локально; `502`/`503` приложению **или** `202` + async retry (зафиксировать в E3-02 один вариант; для MVP допустим sync + ретрай с телефона) |
| Нет `MLCITY_API_URL` | `500` / явная ошибка конфигурации, лог |

---

## 8. Что не входит

- Замена MX Arrival API.
- Оплата / pay-service.
- Прямой вызов mlcity ingest с телефона.
- Многостворность / LAN TV.

---

## 9. Связанные задачи

Реализация: [07-yougile-tasks-stage-3.md](./07-yougile-tasks-stage-3.md) **E3-01**, **E3-02**.  
Приёмка: сценарий **S3** в [04-acceptance-rules.md](./04-acceptance-rules.md).
