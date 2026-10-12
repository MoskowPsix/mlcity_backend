# YouGile: задачи этапа 3 — эфир и итоги в Вокруг (через hrono-back)

Шаблоны карточек для эпика **Этап 3**.

**Исполнитель всех задач:** `Stage3` (один разработчик; рекоменд. Developer B / Чекпоинт + hrono).  
Репозитории: `hrono-back`, `hrono` / `hrono-app`, `mlcity_frontend` (и при необходимости smoke на `mlcity_backend`).

Контракты:

- Чекпоинт → hrono-back: [08-hrono-back-checkpoint-api.md](./08-hrono-back-checkpoint-api.md)
- hrono-back → Вокруг: [06-results-contract-v1.md](./06-results-contract-v1.md)

Карта экранов: [02-screen-map.md](./02-screen-map.md) A5, B8.  
Приёмка: сценарий **S3** в [04-acceptance-rules.md](./04-acceptance-rules.md).

Колонки доски: **Бэклог → На уточнении → В работе → На проверке → Готово**.

---

## Контекст этапа (не переписывать в каждую задачу)

Цепочка MVP:

```text
Чекпоинт (custom heat)
  → POST hrono-back /api/checkpoint/…  (stream open | results | final | stream close)
  → hrono-back persist + forward
  → POST mlcity …/competitions/{eventId}/results  (heat.open | results.upsert | heat.finish | live.close)
  → карточка события Вокруг: live + завершённые heats
  → «Поделиться» ссылкой на событие
```

**Гейт публикации (до оплаты):** флаг админки автора `checkpoint_publish_override` → в API `publish_allowed`. Член комиссии может слать результаты своего события без этого флага (см. контракт 06).  
**Локальный** хронометраж и экран итогов (E2-11) **не** блокируются.

**Уже сделано на mlcity (не дублировать с нуля):**  
`CheckpointResultsController`, `CheckpointResultsService`, `GET events/{id}/checkpoint-results`, тесты publish/idempotency.  
**Уже есть черновик UI** на `event-show` (poll + таблицы).  
**Не делать:** прямой `POST` results с телефона в mlcity.

---

## Порядок и зависимости

Один поток (параллели A/B нет):

```text
E3-01 HB API + миграции
  → E3-02 HB forward в mlcity
  → E3-03 App client → hrono-back
  → E3-04 Offline queue
  → E3-05 UX publish_allowed + встройка в custom heat
  → E3-06 Вокруг UI «Хронометраж» + poll
  → E3-07 Share
  → E3-08 Stage/env smoke
  → E3-09 Приёмка S3
  → E3-10 (опц.) бейдж «Эфир»
```

Lead принимает S3 на E3-09. Code review — по договорённости; стык HB↔mlcity при блокере может помочь Lead точечно.

---

## Эпик: Этап 3

### E3-01. hrono-back: checkpoint API + миграции

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | L |
| **Зависимость** | контракт [08](./08-hrono-back-checkpoint-api.md); желательно E2-08 / E2-11 на стороне app позже |

**Сделать:**
- Миграции `checkpoint_heats` (+ results / JSON snapshot) — **не** таблицы Arrival MX.
- Роуты под `api.secret`:
  - `POST /api/checkpoint/events/{vokrugEventId}/heats`
  - `POST /api/checkpoint/heats/{externalId}/stream/open`
  - `POST /api/checkpoint/heats/{externalId}/results`
  - `POST /api/checkpoint/heats/{externalId}/final-results`
  - `POST /api/checkpoint/heats/{externalId}/stream/close`
- Приём и persist payload; идемпотентность по `event_id` на уровне HB (или готовность к ней в E3-02).
- Feature/unit тесты приёма без обязательного live mlcity (forward можно заглушить/выключить в E3-01).
- `MLCITY_API_URL` в `config/hrono.php` (может быть пустым до E3-02).

**Критерий готовности:**
- С API-secret можно создать heat, open, results, final, close; данные в БД HB.
- MX `/api/arrivals/…` регрессией не сломан (дымовой тест или ручной чеклист).

**Не входит:** forward в mlcity (E3-02); клиент телефона.

**Ссылки:** [08-hrono-back-checkpoint-api.md](./08-hrono-back-checkpoint-api.md).

---

### E3-02. hrono-back: forward в mlcity + ошибки publish

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | L |
| **Зависимость** | E3-01; контракт [06](./06-results-contract-v1.md) |

**Сделать:**
- Сервис forward (по аналогии с `SendRaceResultsToMotoAction`): Bearer Вокруг из запроса → `POST {MLCITY_API_URL}checkpoint/competitions/{eventId}/results`.
- Маппинг: open → `heat.open`, results → `results.upsert`, final → `heat.finish`, close → `live.close`.
- Проброс `401`/`403` приложению; не считать успехом.
- Политика ретрая: для live достаточно последнего upsert; final persist + повтор с тем же UUID.
- Тесты с Http::fake на mlcity (accepted true/false, 403 publish).

**Критерий готовности:**
- На stage/local: open+upsert → `GET …/checkpoint-results` на mlcity показывает live; finish → heat в `heats[]`.
- Повтор UUID → без дублей строк на mlcity.
- 403 без publish понятен в ответе HB.

**Не входит:** UI карточки; оплата; клиент app.

**Ссылки:** [06](./06-results-contract-v1.md), [08](./08-hrono-back-checkpoint-api.md).

---

### E3-03. Чекпоинт: клиент отправки в hrono-back

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | L |
| **Зависимость** | E3-02; E2-08; желательно E2-11 |

**Сделать:**
- Сервис (напр. `checkpoint-results-sync`): вызовы **hrono-back** по контракту 08 (`X-Api-Secret` + Bearer Вокруг).
- **Не** вызывать `POST …/checkpoint/competitions/…/results` на mlcity с телефона.
- UUID `event_id` на каждое сообщение; `external_id` = локальный id custom heat; `participant_id` = id комиссии Вокруг; `place` / `result` / `result_value_ms`.
- Throttle live results (1–3 с или по изменению ranking).
- Учитывать `publish_allowed` (E3-05 детализирует UX).

**Критерий готовности:**
- С тестовым user с publish: после open+results на карточке/GET snapshot есть live; после final — итоги.
- В network inspector — хост hrono-back, не прямой ingest mlcity.
- 403 обрабатывается без краша.

**Не входит:** офлайн-очередь (E3-04); UI Вокруг.

**Ссылки:** [08](./08-hrono-back-checkpoint-api.md).

---

### E3-04. Чекпоинт: офлайн-очередь публикации

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | M |
| **Зависимость** | E3-03 |

**Сделать:**
- При отсутствии сети / 5xx: класть open / последний results / final / close в очередь (расширить `pending-http-queue` или отдельная checkpoint-очередь на **URL hrono-back**).
- Политика: live = **последний** upsert по heat; `final-results` обязателен и не теряется.
- Ретрай при сети + валидном токене Вокруг; те же UUID.

**Критерий готовности:**
- Заезд офлайн → сеть → final уходит на HB → итоги на карточке Вокруг (при publish).
- Нет шторма из сотен устаревших upsert после reconnect.

**Не входит:** ручной конфликт-резолв правок на сервере.

---

### E3-05. Чекпоинт: UX без publish + встройка в custom heat

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | M |
| **Зависимость** | E3-03, E3-04 |

**Сделать:**
- Если `publish_allowed === false` (и нет сценария члена комиссии с правом): хронометраж и локальные итоги работают; облачные вызовы не идут (или 403 без краша).
- Сообщение: «Публикация в Вокруг недоступна. Обратитесь к организатору / включите публикацию в админке» (без оплаты в тексте пилота).
- Привязка `vokrug_event_id` к сессии заезда.
- Хуки: start → stream/open (+ meta heat); во время заезда → results; finish → final-results + close; не ломать MX (`mototreckStream` выкл. для custom).

**Критерий готовности:**
- Без флага: локально ок; нет успешной публикации в облако.
- С флагом: импорт → старт → метки → live на карточке → finish → `heats[]`.
- Готовность к E3-09.

**Не входит:** pay-service; несколько створов; LAN TV.

---

### E3-06. Вокруг: UI блока «Хронометраж» + обновление снимка

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | M |
| **Зависимость** | GET snapshot уже есть; данные появляются после E3-02+ |

**Контекст:** Черновик на `event-show` — довести до приёмки S3. Poll и reconnect — в этой же карточке (один исполнитель).

**Сделать:**
- Блок только при `checkpoint_enabled`.
- Состояния: «Трансляция ещё не началась» / live-таблица / завершённые heats.
- Колонки: место, номер, ФИО (если в снимке), результат.
- Первичный `GET …/checkpoint-results`; poll (~10 с) и/или Pusher; после reconnect — повторный GET (REST = правда).
- Не ломать остальную карточку.

**Критерий готовности:**
- Зритель без спец. прав видит live и итоги на stage.
- Обрыв сети у зрителя → после восстановления снимок догоняет без полного reload приложения.
- Нет утечек подписок; событие без checkpoint — блока нет.

**Не входит:** бейдж в ленте (E3-10); админка; настройка Pusher у DevOps (если нет — «poll-only» в комментарии).

---

### E3-07. Вокруг: «Поделиться» результатами

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | S |
| **Зависимость** | E3-06 |

**Сделать:**
- «Поделиться» в контексте хронометража или общая share события (deep link на карточку достаточна).
- Акцент, когда есть live или heats; без отдельного paywall UI.

**Критерий готовности:**
- Ссылка открывает карточку; при снимке виден блок хронометража.
- Мобильный share sheet / копирование URL.

**Не входит:** отдельный лендинг только под результаты; UTM.

---

### E3-08. Stage / env: секреты и smoke цепочки

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | S |
| **Зависимость** | E3-02 (минимум) |

**Сделать:**
- Stage: `API_SECRET` на HB; `MLCITY_API_URL`; приложение Чекпоинт смотрит на HB.
- Smoke: heat open → forward → `GET checkpoint-results` на mlcity.
- Регресс Feature-тестов Checkpoint results на mlcity; при realtime — env Pusher или явно poll-only.
- Не расширять оплату.

**Критерий готовности:**
- Документированный smoke в комментарии YouGile (URL стендов, что прогнано).
- Расхождение имени флага в доках vs коде не ломает `publish_allowed`.

**Не входит:** pay-service.

---

### E3-09. Сквозная приёмка S3

| | |
|---|---|
| **Исполнитель** | Stage3 (+ Lead принимает) |
| **Размер** | S |
| **Зависимость** | E3-05, E3-06, E3-07, E3-08 |

**Сделать:**
- Прогон [S3](./04-acceptance-rules.md): publish вкл. → вызовы HB (не прямой mlcity ingest) → live → finish → итоги → share.
- Offline → online → итоги на карточке.
- Publish выкл. → локально ок, облако нет.
- Краткая запись в YouGile: дата, стенд, результат.

**Критерий готовности:**
- Lead принимает S3; критичные баги заведены или закрыты.

---

### E3-10. Бейдж «Эфир» в ленте событий (опционально, не блокер MVP)

| | |
|---|---|
| **Исполнитель** | Stage3 |
| **Размер** | S |
| **Зависимость** | E3-06 |

**Сделать:**
- В ленте/превью индикатор открытого live (без тяжёлого poll на всю ленту, если дорого).

**Критерий готовности:**
- Зритель видит, что идёт эфир — или задача отложена «не MVP».

**Не входит:** обязательность для закрытия этапа 3.

---

## Сводка для YouGile

| ID | Кто | Размер | Название |
|---|---|---|---|
| E3-01 | Stage3 | L | hrono-back: checkpoint API + миграции |
| E3-02 | Stage3 | L | hrono-back: forward в mlcity |
| E3-03 | Stage3 | L | Чекпоинт: клиент → hrono-back |
| E3-04 | Stage3 | M | Чекпоинт: офлайн-очередь на HB |
| E3-05 | Stage3 | M | Чекпоинт: UX publish + встройка в custom heat |
| E3-06 | Stage3 | M | Вокруг: UI хронометража + poll |
| E3-07 | Stage3 | S | Вокруг: поделиться |
| E3-08 | Stage3 | S | Stage/env smoke цепочки |
| E3-09 | Stage3 + Lead | S | Сквозная приёмка S3 |
| E3-10 | Stage3 | S | Бейдж «Эфир» в ленте (опционально) |

Не создавать одну задачу «Сделать этап 3».  
Не заводить параллельную ветку «прямой POST телефона в mlcity».

**Порядок:** строго E3-01 → … → E3-09; E3-10 опционально после E3-06.
