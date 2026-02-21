# TAPTAP System – Feature Updates Document (Client Request)

**Document version:** 1.5  
**Date:** February 2026  
**Prepared for:** Client (mtenegnezeee)  
**Systems:** TAPTAP Laravel Backend + Web Portals | TipTap WhatsApp Bot (tiptopbot)

---

## 1. Executive Summary

This document lists the **client-requested features**, shows **which were originally in scope** (Table 1), **which have been implemented** (Table 2), and gives per-feature requirements and recommendations. The WhatsApp bot **uses the same Laravel backend as the web** (Web = Manager/Waiter/Admin; WhatsApp = TipTap bot calling `/api/bot/*`).

---

## 2. Table 1 – Original Features (Requested Scope)

*Zile features zilizokuwa zimeombwa mwanzoni – platform (Web / WhatsApp) zilizotajwa.*

| # | Feature | Platform (requested) |
|---|---------|----------------------|
| 1 | Payment confirmation from waiters tab → go to waiter | Web + WhatsApp |
| 2 | Link table and waiter (ease calling) | Web (link + UI) + WhatsApp (ask table, validate, show "Table X" to waiter) |
| 3 | Add 0 to go back to main menu + specific commands for menu items | WhatsApp only |
| 4 | Enter phone number to be billed | Web + WhatsApp |
| 5 | Enter comment or type END to finish | WhatsApp only |
| 6 | Anonymous comments; appear on waiter platform after configured time | Web + WhatsApp |
| 7 | Add customer support number | Web + WhatsApp |
| 8 | Change language as main menu item | WhatsApp only |
| 9 | Hand over of tables before departure | Web (Waiter/Manager) |
| 10 | Change notification before payment | Web + WhatsApp |
| 11 | Change BoT to TipTap (branding) | Web + WhatsApp |
| 12 | Don't show tips to manager | Web only |
| 13 | Tip the chef | Web + WhatsApp |

---

## 3. Table 2 – Implementation Status

*Hii table inaonyesha zipi zimetekelezwa (Done) na zipi bado (Pending).*

| # | Feature | Web | WhatsApp | Notes |
|---|---------|-----|----------|--------|
| 1 | Payment confirmation → waiter | **Done** | **Done** | GET /api/waiter/payments; "Payment successful – from Table X"; waiter tab Payments |
| 2 | Link table and waiter | **Done** | **Done** | tables.waiter_id; Manager assign waiter; bot asks "Upo meza namba ngapi?", validates; waiter sees "at Table X" |
| 3 | 0 = main menu + menu commands | — | **Done** | Global "0" → HOME; menu image + caption with commands (0 = back, Order = type item + qty) |
| 4 | Enter phone number to be billed | **Done** | **Done** | All pay-bill flows: "Enter phone number to be billed" |
| 5 | Comment or type END to finish | — | **Done** | Feedback: "Enter comment or type END to finish"; END/skip = no comment |
| 6 | Anonymous comments; visible after delay | **Done** | **Done** | No customer/table in waiter view; FEEDBACK_VISIBLE_AFTER_MINUTES; waiter sees after delay |
| 7 | Customer support number | **Done** | **Done** | restaurants.support_phone; Manager QR & Mobile API page; bot HOME "Customer Support" |
| 8 | Change language (main menu) | — | **Done** | Swahili/English; main menu "Change language"; session.lang, lang.js |
| 9 | Hand over tables before departure | **Done** | — | GET my-tables, colleagues; POST handover-tables; /waiter/handover page |
| 10 | Change notification before payment | **Done** | **Done** | POST cash/change-notification (change_to_give); cash payment with amount_received |
| 11 | BoT → TipTap (branding) | **Done** | **Done** | User-facing "TipTap"; console "TipTap WhatsApp" |
| 12 | Don't show tips to manager | **Done** | — | No tips on manager dashboard/sidebar/waiters; /manager/tips redirects with message |
| 13 | Tip the chef | **Pending** | **Pending** | Not yet implemented (chef role / chef_id + bot flow + chef view) |

---

## 4. Per-Feature Analysis and Recommendations

### 3.1 Payment confirmation from waiters tab → go to waiter

- **Platform:** **WEB + WHATSAPP** (both).
- **What it means:**  
  When a customer’s payment is **successful (paid)**, the **waiter** should get a **special notification/alert** that says **“Payment successful”** and shows **which table** the payment came from (e.g. “Payment successful – Order from **Table 5** has been paid”). So the waiter knows: (1) payment was successful, and (2) which table has paid.  
  **Before** the customer pays, we must know **which table** they are at: either **ask “Upo meza namba ngapi?”** (if we don’t know yet) or use the table already in session (e.g. from “call waiter” or from their order). That way, when payment succeeds we know “anaye lipia” (the one paying) is at “meza flan” (that table), and the waiter sees “Payment successful – from Table X” on their screen.
- **Current state:**  
  - Payment is recorded via API/bot; when paid, order/payment status updates.  
  - Waiter does **not** have a dedicated tab/notification for **successful payments** with table number (“Payment successful – from Table X”).  
  - Before payment, the bot may not always have or ask for table number, so we don’t always know “anaye lipia” is at which table.
- **What is needed:**
  - **Backend:**  
    - Ensure payment/order stores **table_number** (and table_id if used) so we can show “from Table X” to the waiter.  
    - Waiter API: list or stream of **successful payments** for the waiter (by waiter_id or by their orders/tables), with **table_number** and order id, e.g. “Payment successful – Order #123 from Table 5”.  
    - Optional: real-time or polling so waiter sees new “Payment successful” soon after customer pays.
  - **Web (Waiter):**  
    - A **tab/section “Payment confirmations”** or “Successful payments” where the waiter sees **“Payment successful – from Table X”** (and order #). So confirmation **goes to** the waiter in a clear, visible way (special place for successful payments with table info).
  - **WhatsApp (Bot):**  
    - **Before payment:** If we don’t already know the table (from session, e.g. from call waiter or from order), **ask “Upo meza namba ngapi?”** and validate table against restaurant list; store in session so we know “anaye lipia” is at that table.  
    - When payment succeeds (USSD/cash confirmed), backend has order + table; waiter then sees “Payment successful – from Table X” on the **web** (waiter tab). Customer can still get “Payment successful” on WhatsApp as now.
- **Recommendation:**  
  **Web:** Waiter tab “Payment confirmations” / “Successful payments” showing “Payment successful – from Table X” (and order). **WhatsApp:** Before payment, ask “Upo meza namba ngapi?” if table unknown (or use table from session); when payment succeeds, backend has table so waiter sees the confirmation on web. Optional: for **cash**, waiter can also have “Confirm cash received” if you want a second step.

---

### 3.2 Link table and waiter to ease calling experience

- **Platform:** **WEB** (full link + UI) and **WHATSAPP** (ask table, validate, show table to waiter – **table–waiter auto-assign disabled on bot**).
- **What it means:**  
  - **Web:** Tables are **linked** to a waiter (manager assigns which waiter serves which table). Waiter sees incoming requests with a clear “User needs you – at **Table X**” so they know where to go.  
  - **WhatsApp:** The **table–waiter link is not used on the bot** for auto-assigning the request to a waiter. Instead, the bot **eases the calling experience** by: (1) When the customer chooses “Call waiter”, the bot asks **“Upo meza namba ngapi?”** (Which table are you at?). (2) Customer enters the table number. (3) **Validate** that this table exists in the restaurant’s table list (using the same backend that the web uses). (4) Bot sends the call-waiter request to the backend with that `table_number` (and `table_id` if matched). (5) On the **web**, the waiter sees **“User needs you – at Table X”** (and the request appears in their list). So the user’s call goes through, and the waiter sees the correct table; the backend is shared, but the bot does not auto-assign waiter from table.
- **Current state:**  
  - **Backend:** `tables` has no `waiter_id`; `customer_requests` has `table_id` and `waiter_id`. Backend has `GET /api/bot/restaurant/{id}/tables` (list tables). Call-waiter accepts `table_number` and `table_id`.  
  - **Web:** No table–waiter assignment in Manager yet; waiter sees requests with table_number where available.  
  - **WhatsApp:** Bot can send table_number/table_id when session has them (e.g. after table QR scan); if user did not scan a table QR, the flow may not always ask for table or validate it against the restaurant’s list.
- **What is needed:**
  - **Backend (used by both):**  
    - Add `waiter_id` (nullable) to `tables` for **web** (manager assigns waiter to table).  
    - Optional: endpoint to **validate table** for a restaurant (e.g. “is table number X or table_id Y valid for restaurant_id Z?”) so the bot can confirm the entered table exists before sending call-waiter.  
    - When creating CustomerRequest, store `table_number` and `table_id` so **web** waiter view shows “User at Table X”.
  - **Web (Manager):**  
    - Tables CRUD: dropdown “Assigned waiter” per table; save.  
    - Waiter portal: ensure requests show **“User needs you – at Table [number]”** (already partly there via table_number).
  - **WhatsApp (Bot):**  
    - When user chooses “Call waiter” (and does not already have a validated table in session): ask **“Upo meza namba ngapi?”** (or “Which table are you at?”).  
    - User replies with table number (or selects from list from API).  
    - **Validate** table against restaurant’s table list (call backend: get tables or validate-table). If invalid, ask again or show “Table not found”.  
    - If valid: send call-waiter to backend with `table_number` (and `table_id` if available).  
    - **Do not** auto-assign request to waiter from table’s `waiter_id` on the bot side (that behaviour is disabled); assignment can still happen on the web side if you use table–waiter link for routing.
- **Recommendation:**  
  **Web:** Add `waiter_id` to `tables` and Manager UI; waiter sees “at Table X” on requests. **WhatsApp:** Implement “ask table number → validate via backend → send call-waiter with table_number” so the call reaches the backend and waiter sees “User needs you – at Table X” on the web.

---

### 3.3 Add 0 to go back to main menu and specific commands for menu items

- **Platform:** **WHATSAPP only**.
- **What it means:**  
  - **0** always returns to **main menu** (HOME).  
  - **Specific commands** for menu items (e.g. “1” for first category, “2” for second, or short codes like “CHIPS”, “SODA”) so users can type a number or keyword instead of only list buttons.
- **Current state:**  
  Bot uses state machine and number mapping (`menu_options`) in handler; “0” is not globally mapped to HOME in all states.
- **What is needed:**
  - **WhatsApp (Bot):**  
    - In `handleMessage`, before or inside state switch: if `text === '0'`, set `session.state = 'HOME'` and call `showHomeScreen(...)`, then return.  
    - For “specific commands for menu items”: keep and extend `menu_options` (e.g. “1”–“9” for list items) and optionally add keyword aliases (e.g. “chakula” → menu, “soda” → item id X) in a small map or config.
- **Recommendation:**  
  Implement global **0 = Back to main menu** first; then add shortcut numbers and a few keyword commands per restaurant if needed (can be config-driven later).

---

### 3.4 Enter phone number to be billed

- **Platform:** **WEB + WHATSAPP**.
- **What it means:**  
  When paying (especially for “bill me” or quick payment), the system should ask for and use the **phone number to be billed** (the number that will receive the USSD push or be shown on the bill).
- **Current state:**  
  Bot already asks for phone for USSD; order has `customer_phone`; payment has `customer_phone`. For “pay at table” or “bill this number”, the flow may not always clearly ask “Enter phone number to be billed”.
- **What is needed:**
  - **WhatsApp (Bot):**  
    - In “Pay bill” / “Live bill” / Quick payment flow: ensure a clear step “Enter phone number to be billed (e.g. 0712345678)” and store it in session, then pass to payment API.  
    - For order payment, use same phone for USSD; for quick payment, already has phone – just make label explicit (“to be billed”).
  - **Web (Manager/Waiter):**  
    - If manager/waiter can initiate a payment on behalf of a table: add “Customer phone (to be billed)” field and send to backend; backend already supports `customer_phone` on payment.
- **Recommendation:**  
  Clarify wording in bot (“Enter phone number to be billed”) and ensure that value is sent as `phone_number` / `customer_phone` in all payment endpoints; no backend schema change if already present.

---

### 3.5 Enter your comment or type END to finish

- **Platform:** **WHATSAPP only** (feedback/comment flow).
- **What it means:**  
  When collecting **comments** (e.g. feedback), the user can type multiple lines or one comment; typing **END** (or “end”) finishes and submits the comment instead of requiring a button.
- **Current state:**  
  Bot has feedback flow with rating and optional comment; likely single message for comment.
- **What is needed:**
  - **WhatsApp (Bot):**  
    - In feedback/comment state: accept multiple messages; concatenate lines into one comment until user sends “END” (or “end”); then submit feedback via API and return to HOME.  
    - Optional: send “Type END when done” after first comment line.
- **Recommendation:**  
  Implement multi-line comment with “END” to finish in the feedback flow; backend already accepts `comment` string – no change needed there.

---

### 3.6 Anonymous comments; appear on waiter platform after configured time

- **Platform:** **WEB + WHATSAPP**.
- **What it means:**  
  - All comments (feedback) are **anonymous** (no customer identity shown to waiter).  
  - Comments appear on the **waiter platform** only **after a configurable delay** (e.g. 24 hours), so managers can set “anonymous message appearing time”.
- **Current state:**  
  Feedback has `waiter_id`, `rating`, `comment`; waiter sees feedback in “Ratings”. No anonymity flag; no delay; no “time manager” setting.
- **What is needed:**
  - **Backend:**  
    - Add optional `is_anonymous` (boolean) and `visible_at` (timestamp) to `feedback` (or a separate “anonymous_comments” table if you want to separate from ratings).  
    - Setting: e.g. `anonymous_feedback_delay_hours` (or minutes) in `settings` or per restaurant.  
    - When creating feedback (from bot/API), set `visible_at = now() + delay`, and `is_anonymous = true` (or always true for this flow).  
    - Waiter API: when returning feedback, filter `where('visible_at', '<=', now())` and do not expose customer phone/name (already anonymous in response if you don’t return them).
  - **Web (Manager):**  
    - Settings page: “Anonymous feedback: show to waiter after [X] hours” (or dropdown: 0, 1, 6, 24, 48 hours). Save to settings.
  - **WhatsApp (Bot):**  
    - In feedback flow, send comment as anonymous; backend sets `visible_at` and `is_anonymous`.  
    - No need to ask “anonymous?” if all comments are anonymous.
- **Recommendation:**  
  Add `visible_at` and optional `is_anonymous` to feedback; add one manager setting for delay; waiter list feedback only where `visible_at <= now()` and never show customer identity.

---

### 3.7 Add customer support number

- **Platform:** **WEB + WHATSAPP**.
- **What it means:**  
  Show a **customer support number** (e.g. restaurant or head office) so customers can call or WhatsApp for help.
- **Current state:**  
  There may be no global “support number” in settings or on bot main menu.
- **What is needed:**
  - **Backend:**  
    - Add setting e.g. `customer_support_phone` (or use existing contact) in `settings` or per restaurant.
  - **Web (Manager/Admin):**  
    - In Settings or Restaurant profile: field “Customer support number”; save to settings/restaurant.
  - **WhatsApp (Bot):**  
    - Main menu: add option “Contact support” or “Customer support”; show message like “For support call/WhatsApp: +255 XXX XXX XXX” (from API or env).  
    - Optional: fetch support number from API (e.g. restaurant config) so each restaurant can have its own.
- **Recommendation:**  
  One setting (global or per restaurant); show on bot main menu and optionally on web footer or help page.

---

### 3.8 Change language as a menu item on the main menu

- **Platform:** **WHATSAPP only**.
- **What it means:**  
  On the **main menu**, add an option “Change language” (or “Badilisha lugha”) so the user can switch language (e.g. English / Swahili) for bot messages.
- **Current state:**  
  Bot messages are likely in one language (e.g. English or Swahili); no language selection or stored preference.
- **What is needed:**
  - **WhatsApp (Bot):**  
    - Add `session.language = 'en' | 'sw'` (or similar).  
    - Main menu: add row “Change language” / “Badilisha lugha”.  
    - When selected, show list “1. English  2. Kiswahili”; set session.language and optionally save to a minimal backend (e.g. phone + language) for next session.  
    - Use `session.language` when sending any message (either a simple dictionary of strings per language or a small helper that picks text).
  - **Backend (optional):**  
    - Optional endpoint to save/retrieve language preference by phone (so next time they start, language is pre-set).
- **Recommendation:**  
  Implement in bot only first (session-based language); add backend preference later if you want persistence across sessions.

---

### 3.9 Hand over of tables before departure

- **Platform:** **WEB** (Waiter/Manager).
- **What it means:**  
  When a waiter is about to leave (end of shift), they **hand over** their assigned **tables** to another waiter so that orders and “call waiter” for those tables go to the new waiter.
- **Current state:**  
  Tables are not assigned to waiters in DB; orders have `waiter_id` (who served the order). So there is no formal “table assignment” to hand over.
- **What is needed:**
  - **Backend:**  
    - If you add `waiter_id` to `tables` (see 3.2), then “hand over” = reassign tables from Waiter A to Waiter B.  
    - Endpoint e.g. `POST /waiter/tables/handover` with `target_waiter_id` and list of `table_ids`, or “hand over all my tables to X”.  
    - Permission: only waiter can hand over their own tables; manager can reassign any table.
  - **Web (Waiter):**  
    - “Hand over tables” button: list my tables, select target waiter, confirm → call handover API.  
  - **Web (Manager):**  
    - Can reassign table waiter from table management (same as 3.2).
- **Recommendation:**  
  Implement after **table–waiter linking** (3.2); then handover is “bulk reassign my tables to another waiter”.

---

### 3.10 Change the notification before payment

- **Platform:** **WEB + WHATSAPP**.
- **What it means:**  
  **Change** the **notification** that the customer sees **before** they pay (e.g. text of the message that says “Confirm payment” or “You will receive USSD push” – wording or channel).
- **Current state:**  
  Bot sends messages before initiating USSD (e.g. “Pay now”, “Confirm on your phone”); manager might have in-app notifications for new orders/payments.
- **What is needed:**
  - **WhatsApp (Bot):**  
    - Make the “pre-payment” message configurable: e.g. store in settings `pre_payment_message` or use a few placeholders (amount, phone). Bot reads from API or config and sends that message before calling payment API.  
  - **Web (Manager/Admin):**  
    - Settings: “Notification before payment (WhatsApp)” – text area; optional placeholders like {amount}, {phone}.  
  - **Backend:**  
    - Expose `pre_payment_message` in settings or restaurant config; bot fetches when building the message.
- **Recommendation:**  
  Add one configurable “pre-payment message” (and optionally “pre-payment message SW”) for the bot; manager can edit in Settings.

---

### 3.11 Change BoT to TipTap (branding)

- **Platform:** **WEB + WHATSAPP**.
- **What it means:**  
  Replace any visible “BoT” or “Bot” with **TipTap** (or TAPTAP) so branding is consistent.
- **Current state:**  
  App name may be TAPTAP; bot might show “TipTap” in some places and “Bot” in others (e.g. “ReplyNumberToChoose” or titles).
- **What is needed:**
  - **WhatsApp (Bot):**  
    - Replace all user-facing strings: “TipTap”, “TIPTAP”, “Welcome to TipTap” (no “Bot”).  
    - Console/logger can stay as “bot” for dev.
  - **Web:**  
    - Replace “Bot” with “TipTap” in menus, titles, and footer where it refers to the product name (e.g. “TipTap WhatsApp”, not “WhatsApp Bot” in public-facing text).
- **Recommendation:**  
  Global search in bot handler and web views for “bot”/“BoT” in user-visible copy and replace with TipTap/TAPTAP as appropriate.

---

### 3.12 Don’t show tips to manager

- **Platform:** **WEB only** (Manager portal).
- **What it means:**  
  **Managers** should **not** see tip amounts or tip reports; tips are only for **waiters** (and possibly admin for compliance, depending on policy).
- **Current state:**  
  Manager has Tips page (`TipController`): total tips today, top waiter, waiter performance with tips. So manager currently sees tips.
- **What is needed:**
  - **Web (Manager):**  
    - Remove or restrict access to Tips page: either hide menu item and return 403 for `/manager/tips`, or show a placeholder “Tip data is only visible to waiters” with no figures.  
  - **Backend:**  
    - Manager tips API (if any): return empty or 403.  
  - **Waiter:**  
    - No change; waiter continues to see their own tips.
- **Recommendation:**  
  Hide manager Tips page and any manager tips API; keep waiter tips and optional admin-only reporting if needed for audits.

---

### 3.13 Tip the chef

- **Platform:** **WEB + WHATSAPP**.
- **What it means:**  
  Customers can **tip the chef** (in addition to or instead of waiter). So tips can go to a “chef” entity, not only to waiters.
- **Current state:**  
  Tips are tied to `waiter_id` (User with role waiter); there is no “chef” role or separate chef entity.
- **What is needed:**
  - **Backend:**  
    - Option A: Add role **chef** (Spatie) and allow `tips.waiter_id` to be a chef user (same table, just another role).  
    - Option B: Add `chef_id` (nullable) to `tips` and optionally a `chefs` or use User with role chef.  
    - Bot/API: when submitting tip, accept `waiter_id` or `chef_id` (or “tip type”: waiter | chef); create Tip with the right recipient.  
    - Waiter app: show only tips where `waiter_id = me`. Chef app or “Chef” section: show tips where chef_id = me (or waiter_id and user is chef).  
  - **Web:**  
    - If chef is a role: Chef dashboard (or section) for “My tips”.  
    - Manager: if “don’t show tips to manager” is applied, manager doesn’t see waiter or chef tips; otherwise only aggregate for reporting if needed.  
  - **WhatsApp (Bot):**  
    - After order/payment: “Tip waiter?”, “Tip chef?”, or “Tip: 1. Waiter  2. Chef”.  
    - For “Tip chef”, send `chef_id` or type=chef + restaurant’s default chef (if one chef per restaurant).  
  - **Data:**  
    - Either add `chef_id` to tips and a way to select chef (e.g. one chef per restaurant, or list of chefs), or use User with role chef and `waiter_id` for both waiters and chefs (simplest).
- **Recommendation:**  
  Add **chef** as a role (User); allow tips to have `waiter_id` pointing to a chef user; in bot add “Tip chef” flow and in web add “Chef” view for tips (and hide from manager if 3.12 is done).

---

## 5. Implementation Priority (Suggested)

| Priority | Feature | Reason |
|----------|---------|--------|
| P0 | 3.11 Change BoT to TipTap | Quick, branding |
| P0 | 3.12 Don’t show tips to manager | Privacy, quick |
| P1 | 3.1 Payment confirmation to waiter | Improves cash flow and waiter responsibility |
| P1 | 3.2 Link table and waiter | Improves call-waiter experience |
| P1 | 3.7 Customer support number | Simple and high value |
| P2 | 3.3 Add 0 + menu commands (WhatsApp) | Better UX in bot |
| P2 | 3.4 Enter phone to be billed | Clarify wording and flow |
| P2 | 3.5 Comment + END to finish | Better feedback collection |
| P2 | 3.10 Notification before payment | Configurable message |
| P3 | 3.6 Anonymous comments + delay | Needs DB + settings + waiter filter |
| P3 | 3.8 Change language (menu item) | Session + copy per language |
| P3 | 3.9 Hand over tables | Depends on 3.2 |
| P3 | 3.13 Tip the chef | Role/DB + bot + chef view |

---

## 6. Dependencies Between Features

- **3.2 (Table–waiter link)** should be done before **3.9 (Hand over tables)** so that tables have an assigned waiter to hand over.
- **3.12 (Don’t show tips to manager)** is independent; can be done anytime.
- **3.13 (Tip the chef)** can re-use existing Tip model and add chef as recipient (role or column).

---

## 7. Notes on Your Current System

- **Backend:** Laravel 12, Sanctum, Spatie roles. Tables have `waiter_id` (link table and waiter). See `docs/update 2.txt` for change log.
- **Bot:** Node (Baileys), state machine, session per JID; all backend calls via `/api/bot/*` with Bearer token. Parse entry supports START_R, START_R_T, START_R_W and tags.
- **Payments:** Selcom USSD; cash payments recorded; payment has `waiter_id`; no “waiter confirmed cash” step yet.
- **Reports:** The payroll/salary slip images you shared (NSSF, PAYE, etc.) are separate from TAPTAP’s restaurant flow; if you later need “tips” or “payments” to feed into payroll, that can be a separate export or report module.

---

## 8. Document Control

- **Web** = Laravel web app (Admin, Manager, Waiter portals).
- **WhatsApp** = TipTap WhatsApp bot (tiptopbot).
- **Backend** = Laravel API + DB used by both Web and Bot.

---

## 9. Implementation / Updates log

- **Table 1** = zile features zilizokuwa zimeombwa mwanzoni (requested scope). **Table 2** = implementation status (Done / Pending) kwa Web na WhatsApp.
- **Log ya mabadiliko ya routes na APIs:** Kila mabadiliko yanaandikwa kwenye **`docs/update 2.txt`**. Orodha kamili ya API routes: **`docs/API_ROUTES.md`**.
- Features 1–12 zimetekelezwa kwa kadri ya Table 2; Feature 13 (Tip the chef) iko Pending.
