# TIPTAP Waiter API Documentation

Base URL: `https://tiptapafrica.co.tz/api`

Auth: `Authorization: Bearer {token}` (from `/api/auth/login`)

Role: All endpoints require `waiter` role.

---

## Auth (Public)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/login` | Login, returns token + user |
| POST | `/auth/logout` | Logout, revoke token |

---

## Waiter Dashboard

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/waiter/dashboard` | Full dashboard data (stats, orders, requests, feedback) |
| GET | `/waiter/dashboard/stats` | Quick stats only (for polling) |

---

## Orders

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/waiter/orders` | List my orders (paginated, 15 per page) |
| POST | `/waiter/orders/{order}/claim` | Claim an unassigned order |

---

## Customer Calls (Call Waiter / Request Bill)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/waiter/requests` | List pending customer requests |
| POST | `/waiter/requests/{customerRequest}/complete` | Mark request as attended |

---

## Tips & Ratings

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/waiter/tips` | List my tips (paginated) |
| GET | `/waiter/ratings` | List my feedback/ratings (paginated) |

---

## Menu

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/waiter/menu` | Restaurant menu (categories + items) |

---

## Request/Response Examples

### POST /auth/login
```json
// Request
{ "email": "waiter@example.com", "password": "..." }

// Response 200
{
  "success": true,
  "token": "1|xxx",
  "user": {
    "id": 13,
    "name": "...",
    "restaurant_name": "Samaki Samaki",
    "waiter_code": "SAM-W02",
    "waiter_qr_url": "https://wa.me/255794321510?text=START_2_W13"
  }
}
```

### GET /waiter/dashboard
```json
// Response 200
{
  "success": true,
  "data": {
    "stats": { "tips_today": 0, "my_active_orders": 2, ... },
    "unassigned_orders": [...],
    "pending_requests": [...],
    "recent_feedback": [...],
    "my_orders_today": [...]
  }
}
```

### POST /waiter/orders/{id}/claim
```json
// Response 200
{ "success": true, "message": "Order #45 is now assigned to you!", "data": { "order_id": 45 } }

// Response 422 - Already claimed
{ "success": false, "message": "This order has already been claimed by another waiter." }
```

### POST /waiter/requests/{id}/complete
```json
// Response 200
{ "success": true, "message": "Request marked as attended!", "data": { "request_id": 1 } }
```
