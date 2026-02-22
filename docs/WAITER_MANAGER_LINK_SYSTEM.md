# TIPTAP: Mfumo Mpya wa Waiters na Manager (Link / Unlink)

Hati hii inaeleza mabadiliko yanayotakiwa kwenye mfumo wa **waiters** na **managers**: waiters wanajisajili wenyewe, na manager **anashiriki (link)** au **anatoa (unlink)** waiter kwenye restaurant yake. Haiendi tena manager kuunda waiters na kuwapa credentials.

---

## 1. Mabadiliko Makubwa

| Sasa | Baadaye |
|------|--------|
| Manager anaunda waiter kwenye dashboard na kumpa credentials | Waiter anajisajili mwenyewe kwenye web; manager ana **link** / **unlink** tu |
| Waiter ana restaurant moja (aliyoundwa nayo) | Waiter ana **nambari ya pekee (unique)**; anaweza kuwa **linked** kwenye restaurant moja kwa wakati; history inabaki |
| — | Manager: **search** waiter kwa nambari ya pekee → **link** au **unlink** kwenye restaurant yake |

---

## 2. Upande wa Waiter

### 2.1 Kujisajili (Self-Registration)

- **Sehemu:** Ukurasa wa **kujisajili kwa waiters** (public, bila kuwa logged in).
- **Details zinazohitajika (mfano):**
  - Jina la kwanza
  - Jina la mwisho
  - Barua pepe (email)
  - Nambari ya simu
  - Location (mahali / mkoa – optional)
  - Neno la siri (password)
- **Baada ya kusajili:**
  - Akaunti inafunguliwa.
  - **Nambari ya pekee ya waiter** inatengenezwa na kumwekea (unique waiter number/code).
  - Hajaunganishwa na restaurant yoyote bado – anaweza kuingia kwenye system lakini **hana dashboard ya restaurant** mpaka manager ame **link** kwenye restaurant.

### 2.2 Nambari ya Peke ya Waiter (Unique Waiter Number)

- **Lengo:** Kila waiter ana **code/nambari ya kipekee** ya kudumu (hata akibadilisha restaurant).
- **Muundo unaoweza kutumika (mfano):**
  - `TIPTAP-W-00001`, `TIPTAP-W-00002`, … (TIPTAP = jina la mfumo, W = Waiter, nambari ya kipekee)
  - Au: `TPTP-10001`, `TPTP-10002`, …
- Nambari hii:
  - Inaonekana kwenye **profile ya waiter** (akilogi ndani).
  - Manager atatumia hii kwa **search** na kisha **link** waiter kwenye restaurant yake.

### 2.3 Kuingia (Login) na Dashboard

- Waiter anaingia kwa **email + password** (kama kawaida).
- **Ikiwa haja-linkiwa na restaurant yoyote:**
  - Anaweza kuona profile yake na **nambari yake ya pekee (TIPTAP-W-xxxxx)**.
  - Anaweza kuona ujumbe kwamba “Hujaunganishwa na restaurant – manager atakuunga.”
- **Ikiwa ame-linkiwa na restaurant (na manager):**
  - Anaona **dashboard ya waiter** ya restaurant ile: orders, tips, QR code, code ya waiter ya restaurant ile, nk.
  - QR code na “waiter code” za **restaurant husika** ndizo zinazoonekana (kwa kutumia restaurant ya sasa).

### 2.4 Kuondoka Restaurant (Unlink)

- Waiter anaondoka restaurant (anamuambia manager).
- Manager anafanya **unlink** kwenye system (inaelezewa chini).
- **Baada ya unlink:**
  - Waiter tena hana restaurant “current” – dashboard ya waiter ya restaurant inapotea.
  - **History haitaki:** mambo aliyofanya (orders, ratings, tips) kwenye restaurant ile **inabaki kwenye system** – haifutwi.
  - Waiter anaweza ku-linkiwa na **restaurant nyingine** baadaye; wakati wa search wataona **details zake + history (kazi na ratings)**.

---

## 3. Upande wa Manager

### 3.1 Kuondoa “Add Waiter” (Kutengeneza Waiters Wapya)

- **Hatutaki tena:** manager kuunda waiter mpya na kumpa email/password.
- **Tunachotaka:** waiter ajisajili mwenyewe; manager ana **link** tu kwenye restaurant yake.

### 3.2 Sehemu ya Waiters: Link na Unlink

- Manager ana **ukurasa wa Waiters** kwenye dashboard yake.
- **Kazi zinazopatikana:**
  1. **Search waiter** kwa **nambari ya pekee** (TIPTAP-W-xxxxx au format tulochagua).
  2. **Kuona details za waiter** (jina, simu, email, location, na **history ya kazi / ratings** kwenye restaurants zilizopita) – **UI nzuri**.
  3. **Link waiter** kwenye restaurant ya manager: kama waiter haja-linkiwa mahali, manager abonyeze “Link waiter” → waiter anaunganishwa na restaurant ya manager; waiter akilogi ataona dashboard ya waiter ya restaurant ile (QR, code, orders, nk).
  4. **Unlink waiter** kwenye restaurant ya manager: manager abonyeze “Unlink” kwa waiter fulani → waiter anaondolewa kwenye restaurant ile; **history (kazi, ratings) inabaki**, ila waiter sasa “hana restaurant” mpaka a-linkiwe na restaurant nyingine.

### 3.3 Aina mbili ya ku-link: Muda mrefu (Permanent) na Muda / Show-time (Temporary)

- **Muda mrefu (Permanent):** Waiter anaunganishwa na restaurant bila tarehe ya mwisho. Anaendelea kuwa “linked” mpaka manager amfanye **unlink**.
- **Muda / Show-time (Temporary):** Waiter anaunganishwa kwa **muda maalum** (mfano siku ya event, mass, show). Manager anaweka **tarehe ya mwisho** (linked_until). **Baada ya tarehe ile:**
  - System inamwona waiter **si linked tena** (anaondoka wenyewe kwenye mfumo wa restaurant ile).
  - History (orders, tips, ratings) **inabaki** – waiter anaweza ku-linkiwa na restaurant nyingine baadaye.
- **Wote** (permanent na temporary) wanajisajili wenyewe, wana **nambari ya pekee** na **history inabaki**.
- Manager wakati wa **Link waiter** anachagua: **Muda mrefu** au **Muda / Show-time**; ikiwa Show-time anaweka **mpaka tarehe** (linked_until).

### 3.4 Mtiririko (Flow) ya Link

1. Waiter mpya anajisajili kwenye web → anapata **nambari ya pekee** (inaonekana kwenye profile yake).
2. Waiter anampa manager **nambari ile** (kwa mkono / simu).
3. Manager anaingia dashboard → **Waiters** → **Search** (anaweka nambari ya pekee).
4. System inamtafuta waiter, inamwekea **details** (jina, simu, email, na history ikiwa ipo) kwenye UI nzuri.
5. Manager anachagua **aina:** Muda mrefu (Permanent) au Muda / Show-time (na tarehe ya mwisho ikiwa Show-time) → abonyeze **“Link waiter”**.
6. System ina **muunga** waiter na restaurant ya manager (na employment_type + linked_until ikiwa temporary).
7. Waiter akilogi anaona dashboard ya waiter wa restaurant ile, QR code yake na code yake. Waiter wa Show-time baada ya tarehe ya mwisho anaondoka wenyewe (link inaisha); history inabaki.

---

## 4. History na Restaurant Mpya

- **Unlink:** history (orders, tips, ratings) kwenye restaurant iliyopita **haifutwi** – inabaki kwenye database.
- **Waiter akienda restaurant nyingine:** manager wa restaurant nyingine anafanya **search** (nambari ya pekee) → anaona **details za waiter + history ya kazi na ratings** (kwenye restaurants zilizopita).
- **Baada ya “Link” kwenye restaurant mpya:**
  - **Dashboard ya waiter** inabadilika: sasa ni **restaurant mpya** (QR code na code za restaurant mpya).
  - **History ya zamani** inaendelea kuhifadhiwa – inaweza kuonyeshwa kwenye profile/history (e.g. “Alifanya kazi restaurant X, ratings Z”) ila “current” restaurant ni ile mpya.

---

## 5. Muundo wa Nambari ya Peke (Unique Waiter Number)

- **Upendekezi:** `TIPTAP-W-` + nambari (kwa mfano `TIPTAP-W-00001`).
  - TIPTAP = jina la mfumo.
  - W = Waiter.
  - Nambari ya kipekee (sequence) – inaweza kuwa 5 digits: 00001, 00002, …
- Nambari hii:
  - Inatolewa **mara moja** waiter anapojisajili.
  - **Haibadiliki** hata akibadilisha restaurant (link/unlink).
  - Inatumika kwa **search** na **link** kwa manager.

---

## 6. Muhtasari wa Kitu Kinachohitajika (Technical Summary)

| Sehemu | Kitu |
|--------|------|
| **Public** | Ukurasa wa **waiter self-registration** (form: jina, email, simu, location, password). Baada ya success → login na profile na **unique waiter number**. |
| **Waiter** | Profile inaonyesha **TIPTAP-W-xxxxx**. Ikiwa haja-linkiwa: ujumbe “Hujaunganishwa na restaurant.” Ikiwa ame-linkiwa: dashboard ya waiter kama sasa (QR, code, orders, nk) kwa **restaurant ya sasa**. |
| **Manager** | **Waiters page:** (1) Search kwa unique waiter number. (2) UI nzuri ya details + history. (3) **Link waiter** – chagua **Permanent** au **Temporary (Show-time)** + tarehe ya mwisho. (4) **Unlink waiter** (ondoa kwenye restaurant yangu; history inabaki). Waiters wa Show-time link inaisha kiotomatiki baada ya linked_until. **Remove:** kutengeneza waiter mpya na kumpa credentials. |
| **Database / Backend** | Waiter ana **unique_waiter_number** (au column sawa) ya kudumu. **restaurant_id** ya waiter inaweza kuwa null (haja-linkiwa) au ID ya restaurant aliyo-linkiwa. **employment_type** (permanent / temporary) na **linked_until** (tarehe ya mwisho kwa temporary). History (orders, tips, feedback) inabaki kwa **waiter_id** na **restaurant_id** iliyopita. |

---

## 7. Hatua Inayofuata

- **Umeelewa:** Hati hii inaeleza mfumo kama ulivyoeleza (waiters wanajisajili, nambari ya pekee, manager link/unlink, history inabaki).
- **Kabla ya implementation:** Ona kama maelezo haya yanafanana na dhana yako; ukipenda muundo wa nambari (TIPTAP-W-00001) au unataka tofauti (mfano TPTP-10001), weza kubadilisha hapa.
- **Baada ya kukubali:** Implementation kwenye web (Laravel + UI) inaweza kuanza kwa kufuata hati hii.

---

*Hati iliandaliwa kwa ajili ya TIPTAP – Waiter & Manager Link System.*
