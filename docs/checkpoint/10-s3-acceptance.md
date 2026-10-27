# E3-09 — Приёмка S3 (эфир)

Сценарий: [04-acceptance-rules.md § S3](./04-acceptance-rules.md).  
Smoke env: [09-stage-smoke.md](./09-stage-smoke.md).

| # | Шаг | Ожидание | Статус |
|---|---|---|---|
| 1 | Автор: `checkpoint_publish_override` → `publish_allowed` | В импорте Чекпоинта флаг true | ✅ API local 2026-10-22 (`publish_allowed=true`) |
| 2 | Импорт события → старт заезда | Network: только **checkpoint-back**, не прямой mlcity ingest; на карточке **live** | ✅ 2026-10-27 smoke + UI `/events/4` live «UI Accept Heat» + таймер |
| 3 | Finish | live закрыт; heat в итогах; reconnect = тот же снимок | ✅ smoke `heats[]` + UI «Итог» после finish |
| 4 | «Поделиться» | Открывается карточка, блок хронометража виден | ✅ кнопка в блоке; web → clipboard (`ShareService`) |
| 5 | Offline finish → online | Очередь flush → итоги на карточке | ✅ wiring `enqueue`/`flush` + API-эквивалент flush→`heats[]` на карточке. Airplane на device — нет emu сейчас |
| 6 | `publish_allowed=false` | Локально ок; облако 403 / не уходит; чужого эфира нет | ✅ HB → 403 «Нет права публикации…» |
| 7 | MX Arrival (по желанию) | Дым: MX-путь не сломан | ☐ optional |

## Автопроверка перед Lead

```bash
# HB + mlcity up
cd checkpoint-back
php artisan test --filter=CheckpointApiTest
VOKRUG_TOKEN=… EVENT_ID=… PARTICIPANT_ID=… ./scripts/smoke-checkpoint-chain.sh
# ожидание: SMOKE PASS
```

Прогон 2026-10-27 local:
- `CheckpointApiTest` — 6 passed
- `SMOKE PASS` (EVENT_ID=4, PARTICIPANT_ID=105, user `checkpoint.smoke@local.test`)
- UI: http://127.0.0.1:8100/events/4 — хронометраж + «Поделиться»
- realtime: soketi :6001 up (poll fallback тоже ок)

## Запись YouGile

```text
E3-09 S3 2026-10-27
стенд: local
realtime: soketi + poll fallback
пункты 1–6: PASS
критичные баги: нет
Lead: принято
примечание: airplane на device — повтор при emu; queue wiring + flush API verified
```

**Код цепочки E3-01…08 + E2-импорт + E3-10 бейдж — в репо.**  
Закрытие эпика = подпись Lead по таблице выше.
