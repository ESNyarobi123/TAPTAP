# TAPTAP API Routes Reference

Base URL: `/api` (e.g. `https://your-domain.com/api`)

All routes below are under this base. Each route has a controller and method. For **feature request scope** and **implementation status** (which features are Done/Pending), see **docs/TAPTAP_Feature_Updates_Request.md** (Table 1 = original features, Table 2 = implementation status).

---

## Auth (no auth required for login)

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| POST | `/auth/login` | Api\AuthController | login |
| POST | `/auth/logout` | Api\AuthController | logout |

---

## Waiter API

**Prefix:** `/waiter`  
**Middleware:** `auth:sanctum`, `role:waiter`

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| GET | `/waiter/dashboard` | Api\Waiter\DashboardController | index |
| GET | `/waiter/dashboard/stats` | Api\Waiter\DashboardController | stats |
| GET | `/waiter/orders` | Api\Waiter\DashboardController | orders |
| GET | `/waiter/tips` | Api\Waiter\DashboardController | tips |
| GET | `/waiter/ratings` | Api\Waiter\DashboardController | ratings |
| GET | `/waiter/menu` | Api\Waiter\MenuController | index |
| GET | `/waiter/requests` | Api\Waiter\DashboardController | pendingRequests |
| GET | `/waiter/payments` | Api\Waiter\DashboardController | payments |
| GET | `/waiter/my-tables` | Api\Waiter\DashboardController | myTables |
| GET | `/waiter/colleagues` | Api\Waiter\DashboardController | colleagues |
| POST | `/waiter/handover-tables` | Api\Waiter\DashboardController | handoverTables |
| POST | `/waiter/orders/{order}/claim` | Api\Waiter\DashboardController | claimOrder |
| POST | `/waiter/requests/{customerRequest}/complete` | Api\Waiter\DashboardController | completeRequest |

---

## V1 API (general)

**Prefix:** `/v1`  
**Middleware:** `auth:sanctum`

### Restaurants

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| GET | `/v1/restaurants/search` | Api\V1\RestaurantController | search |
| GET | `/v1/restaurants/{restaurant}` | Api\V1\RestaurantController | show |

### Menu

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| GET | `/v1/restaurants/{restaurant}/categories` | Api\V1\MenuController | categories |
| GET | `/v1/restaurants/{restaurant}/menu` | Api\V1\MenuController | index |

### Orders

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| POST | `/v1/orders` | Api\V1\OrderController | store |
| GET | `/v1/orders/{order}` | Api\V1\OrderController | show |
| GET | `/v1/orders/{order}/status` | Api\V1\OrderController | status |
| PATCH | `/v1/orders/{order}/status` | Api\V1\OrderController | updateStatus |

### Payments

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| POST | `/v1/payments/ussd-request` | Api\V1\PaymentController | ussdRequest |
| POST | `/v1/payments/cash/change-notification` | Api\V1\PaymentController | cashChangeNotification |
| POST | `/v1/payments/cash` | Api\V1\PaymentController | cashPayment |
| GET | `/v1/payments/{order}/status` | Api\V1\PaymentController | status |

### Feedback & Tips

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| POST | `/v1/feedback` | Api\V1\FeedbackController | store |
| POST | `/v1/tips` | Api\V1\TipController | store |

---

## V1 Manager API

**Prefix:** `/v1/manager`  
**Middleware:** `auth:sanctum`, `role:manager`

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| GET | `/v1/manager/categories` | Api\Manager\CategoryController | index |
| POST | `/v1/manager/categories` | Api\Manager\CategoryController | store |
| GET | `/v1/manager/categories/{category}` | Api\Manager\CategoryController | show |
| PUT/PATCH | `/v1/manager/categories/{category}` | Api\Manager\CategoryController | update |
| DELETE | `/v1/manager/categories/{category}` | Api\Manager\CategoryController | destroy |
| GET | `/v1/manager/menu` | Api\Manager\MenuController | index |
| POST | `/v1/manager/menu` | Api\Manager\MenuController | store |
| GET | `/v1/manager/menu/{menuItem}` | Api\Manager\MenuController | show |
| PUT/PATCH | `/v1/manager/menu/{menuItem}` | Api\Manager\MenuController | update |
| DELETE | `/v1/manager/menu/{menuItem}` | Api\Manager\MenuController | destroy |
| GET | `/v1/manager/tables` | Api\Manager\TableController | index |
| POST | `/v1/manager/tables` | Api\Manager\TableController | store |
| GET | `/v1/manager/tables/{table}` | Api\Manager\TableController | show |
| PUT/PATCH | `/v1/manager/tables/{table}` | Api\Manager\TableController | update |
| DELETE | `/v1/manager/tables/{table}` | Api\Manager\TableController | destroy |

---

## Bot API (WhatsApp)

**Prefix:** `/bot`  
**Middleware:** `auth:sanctum`

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| GET | `/bot/verify-restaurant` | Api\WhatsAppBotController | verifyRestaurant |
| GET | `/bot/verify-tag` | Api\WhatsAppBotController | verifyTag |
| GET/POST | `/bot/parse-entry` | Api\WhatsAppBotController | parseEntry |
| GET | `/bot/search-restaurant` | Api\WhatsAppBotController | searchRestaurant |
| GET | `/bot/restaurant/{restaurantId}/full-menu` | Api\WhatsAppBotController | getFullMenu |
| GET | `/bot/restaurant/{restaurantId}/categories` | Api\WhatsAppBotController | getCategories |
| GET | `/bot/category/{categoryId}/items` | Api\WhatsAppBotController | getCategoryItems |
| GET | `/bot/item/{itemId}` | Api\WhatsAppBotController | getItemDetails |
| POST | `/bot/order` | Api\WhatsAppBotController | createOrder |
| POST | `/bot/order/text` | Api\WhatsAppBotController | createOrderByText |
| GET | `/bot/order/{orderId}/status` | Api\WhatsAppBotController | getOrderStatus |
| POST | `/bot/payment/ussd` | Api\WhatsAppBotController | initiatePayment |
| POST | `/bot/feedback` | Api\WhatsAppBotController | submitFeedback |
| POST | `/bot/tip` | Api\WhatsAppBotController | submitTip |
| GET | `/bot/restaurant/{restaurantId}/tables` | Api\WhatsAppBotController | getTables |
| POST | `/bot/call-waiter` | Api\WhatsAppBotController | callWaiter |
| GET | `/bot/restaurant/{restaurantId}/waiters` | Api\WhatsAppBotController | getWaiters |
| GET | `/bot/active-order` | Api\WhatsAppBotController | getActiveOrder |
| GET | `/bot/restaurant/{restaurantId}/menu-image` | Api\WhatsAppBotController | getMenuImage |
| POST | `/bot/payment/quick` | Api\WhatsAppBotController | initiateQuickPayment |
| GET | `/bot/payment/quick/{paymentId}/status` | Api\WhatsAppBotController | getQuickPaymentStatus |

---

## Other

| Method | Endpoint | Controller | Method |
|--------|----------|-------------|--------|
| GET | `/user` | Closure | (return request user) |

---

*Generated from `routes/api.php`. Every route has a corresponding controller method.*
