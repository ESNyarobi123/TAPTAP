# WhatsApp Bot: Welcome Message Behaviour

## Removing the standalone welcome line

When a customer scans a QR code or enters a waiter/table code, the backend returns data from **`/bot/parse-entry`** or **`/bot/verify-tag`**. The API response now includes:

- **`skip_standalone_welcome`**: `true`

When this flag is present and `true`, the WhatsApp bot **must not** send a separate first message like:

- *"Welcome to SAMAKI SAMAKI! SIRIEL CHARLSE will be your waiter."*

Instead, the bot should send **only** the main interactive menu message, for example:

- *"👋 Welcome to **SAMAKI SAMAKI** (SIRIEL CHARLSE). Choose service: …"* with the numbered options (View Menu, Rate Service, Pay Bill, Tip, Call Waiter, etc.).

## Summary

| Do not send | Do send |
|-------------|--------|
| A first bubble: "Welcome to {restaurant_name}! {waiter_name} will be your waiter." | One message: the menu with "👋 Welcome to **X** (Y)" and the service options (1–8). |

All successful responses from `parse-entry`, `verify-tag`, and `verify-restaurant` (when used for entry) include `skip_standalone_welcome: true`. The bot should check this and send only the single menu message.

---

## Customer Support (option 6): No extra “Choose” bubble

When the customer selects **6 – 📞 Customer Support**, the bot should send **only one message**:

- **📞 Customer Support**
- Call or WhatsApp: {support_phone}
- *Type 0 to go back to main menu.*

**Do not send** a second message containing:

- `━━━━━━━━ 📞 ━━━━━━━━`
- `Choose:`
- `1️⃣ 🏠 Back to Menu`
- `━━━━━━━━━━━━━━━━`
- `✅ ReplyNumberToChoose`

The user can type **0** to return to the main menu; there is no need for a separate “Choose: 1 Back to Menu” bubble. Sending only the support info (and “Type 0 to go back”) is enough.
