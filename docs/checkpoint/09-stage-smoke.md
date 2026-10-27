# E3-08 — Stage / local smoke цепочки эфира

Дата черновика: 2026-10-22  
**Прогон local 2026-10-22:** `SMOKE PASS` (HB→mlcity open/upsert/finish/close + snapshot).  
**Прогон local 2026-10-27:** `SMOKE PASS` + UI `/events/4` (live→finish→«Поделиться»); `CheckpointApiTest` 6/6.  

Стенд local (dev):

| Сервис | URL |
|---|---|
| mlcity API | `http://127.0.0.1:8001/api` |
| checkpoint-back (HB) | `http://127.0.0.1:8081/api` |
| hrono-app | `checkpointApiUrl` → HB; `apiVokrugUrl` → mlcity |
| Soketi (опц.) | `127.0.0.1:6001` — `docker compose --profile realtime up -d soketi` |
| Карточка UI | poll 10s всегда fallback; WS если `pusherKey` + Soketi |

Секреты (local, совпадают):

```text
API_SECRET / X-Api-Secret = local-checkpoint-secret
checkpoint-back MLCITY_API_URL = http://127.0.0.1:8001/api/
hrono-app apiSecret = local-checkpoint-secret
```

Realtime: `BROADCAST_DRIVER=pusher`, `PUSHER_HOST=localhost`, `PUSHER_PORT=6001`.  
Без Soketi — явно **poll-only** (критерий E3-08 выполнен).

## Автоматический smoke (HB → mlcity)

Локальный smoke-user (создан tinker’ом):

```text
email/name: checkpoint.smoke@local.test
password:   password
event_id:   4
participant_id: 105
```

Важно: `POST /api/login` принимает **`name` = email**, не `email`.

```bash
# postgres: docker start vokrug-local-postgres
# soketi:  docker start vokrug-soketi
# mlcity:  php artisan serve --host=127.0.0.1 --port=8001
# HB:      php artisan serve --host=127.0.0.1 --port=8081  (в checkpoint-back)

TOKEN=$(curl -sfS -X POST http://127.0.0.1:8001/api/login \
  -H 'Content-Type: application/json' -H 'Accept: application/json' \
  -d '{"name":"checkpoint.smoke@local.test","password":"password"}' \
  | php -r 'echo json_decode(stream_get_contents(STDIN))->access_token;')

cd checkpoint-back
VOKRUG_TOKEN="$TOKEN" EVENT_ID=4 PARTICIPANT_ID=105 ./scripts/smoke-checkpoint-chain.sh
```

Ожидание: `SMOKE PASS`; `/tmp/cp-smoke-snapshot*.json` — live после open, `heats[]` после finish.

## Ручной чеклист (приложение)

1. Логин **Вокруг** в Чекпоинте.
2. Settings → Импорт из Вокруг → событие с `publish_allowed`.
3. Заезд → старт → в Network только хост **checkpoint-back**, не `…/checkpoint/competitions/…/results`.
4. Карточка события Вокруг: блок «Хронометраж» обновляется (WS или ≤10s poll).
5. Finish → итоги в `heats[]`; «Поделиться».
6. Airplane mode → finish → online → очередь flush → итоги на карточке.
7. `publish_allowed=false` → локально ок, облако 403 / запросы не уходят.

## Тесты

```bash
cd checkpoint-back && php artisan test --filter=CheckpointApiTest
cd mlcity_backend && php artisan test --filter=Checkpoint
```

Приёмка S3 (Lead): [10-s3-acceptance.md](./10-s3-acceptance.md).

## Запись для YouGile (шаблон)

```text
E3-08 smoke YYYY-MM-DD
стенд: local | stage URL…
HB: …
mlcity: …
realtime: soketi | poll-only
результат: PASS / FAIL (+ что упало)
```
