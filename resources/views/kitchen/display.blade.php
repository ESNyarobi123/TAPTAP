<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen Display - {{ $restaurant->name }}</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f0a1e 0%, #1a1333 50%, #0d0816 100%);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
            animation: pulse-bg 8s ease-in-out infinite;
        }

        .bg-animation::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
            animation: pulse-bg 8s ease-in-out infinite reverse;
        }

        @keyframes pulse-bg {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        /* Header */
        .header {
            background: rgba(15, 10, 30, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1920px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.4);
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text span {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }

        .header-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
        }

        .stat-badge.urgent {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .stat-badge.preparing {
            background: rgba(245, 158, 11, 0.15);
            border-color: rgba(245, 158, 11, 0.3);
        }

        .stat-badge.pending {
            background: rgba(139, 92, 246, 0.15);
            border-color: rgba(139, 92, 246, 0.3);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .stat-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .header-time {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .current-time {
            font-size: 2.5rem;
            font-weight: 900;
            font-variant-numeric: tabular-nums;
            background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .last-refresh {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Main Content */
        .main-content {
            padding: 7rem 2rem 2rem;
            position: relative;
            z-index: 1;
        }

        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            max-width: 1920px;
            margin: 0 auto;
        }

        /* Order Card */
        .order-card {
            background: rgba(28, 22, 51, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideIn 0.5s ease-out;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .order-card.vip {
            border: 2px solid rgba(245, 158, 11, 0.5);
            box-shadow: 0 0 40px rgba(245, 158, 11, 0.2);
        }

        .order-card.sla-red {
            border-color: rgba(239, 68, 68, 0.5);
            animation: pulse-urgent 2s ease-in-out infinite;
        }

        @keyframes pulse-urgent {
            0%, 100% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 40px rgba(239, 68, 68, 0.6); }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Order Header */
        .order-header {
            padding: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .order-table {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-number {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 900;
            box-shadow: 0 8px 24px rgba(139, 92, 246, 0.3);
        }

        .order-card.vip .table-number {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
        }

        .table-info h3 {
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .table-info span {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
        }

        .vip-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(239, 68, 68, 0.2) 100%);
            border: 1px solid rgba(245, 158, 11, 0.4);
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 800;
            color: #f59e0b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Timer */
        .order-timer {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .timer-value {
            font-size: 1.5rem;
            font-weight: 900;
            font-variant-numeric: tabular-nums;
        }

        .timer-value.green { color: #10b981; }
        .timer-value.yellow { color: #f59e0b; }
        .timer-value.red { color: #ef4444; animation: blink 1s ease-in-out infinite; }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .timer-label {
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Order Items */
        .order-items {
            padding: 1rem 1.25rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .order-items::-webkit-scrollbar {
            width: 4px;
        }

        .order-items::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .order-items::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.3);
            border-radius: 10px;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .order-item:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 92, 246, 0.3);
        }

        .order-item.cooking {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
        }

        .order-item.ready {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .item-quantity {
            width: 36px;
            height: 36px;
            background: rgba(139, 92, 246, 0.2);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
            color: #a78bfa;
        }

        .order-item.cooking .item-quantity {
            background: rgba(245, 158, 11, 0.2);
            border-color: rgba(245, 158, 11, 0.3);
            color: #fbbf24;
        }

        .order-item.ready .item-quantity {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 0.9rem;
            font-weight: 700;
        }

        .item-notes {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            font-style: italic;
            margin-top: 2px;
        }

        .item-status {
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .item-status.pending {
            background: rgba(139, 92, 246, 0.2);
            color: #a78bfa;
        }

        .item-status.cooking {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .item-status.ready {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        /* Order Actions */
        .order-actions {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            gap: 0.75rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.85rem;
            border: none;
            border-radius: 14px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .action-btn.start {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3);
        }

        .action-btn.start:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.4);
        }

        .action-btn.ready {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        }

        .action-btn.ready:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
        }

        .action-btn.secondary {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .action-btn.secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem;
            text-align: center;
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
            border: 2px solid rgba(139, 92, 246, 0.2);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .empty-icon svg {
            color: rgba(139, 92, 246, 0.5);
        }

        .empty-state h2 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 1rem;
        }

        /* Connection Status */
        .connection-status {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            background: rgba(28, 22, 51, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            z-index: 100;
        }

        .connection-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        .connection-status.disconnected .connection-dot {
            background: #ef4444;
            animation: none;
        }

        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
        }

        .connection-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Sound Notification Toast */
        .notification-toast {
            position: fixed;
            top: 5.5rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.9) 0%, rgba(6, 182, 212, 0.9) 100%);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 200;
            transform: translateX(150%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 40px rgba(139, 92, 246, 0.4);
        }

        .notification-toast.show {
            transform: translateX(0);
        }

        .notification-toast span {
            font-weight: 700;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .header-stats {
                flex-wrap: wrap;
                justify-content: center;
            }

            .orders-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>

    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                        <line x1="6" x2="18" y1="17" y2="17"/>
                    </svg>
                </div>
                <div class="logo-text">
                    <h1>Kitchen Display</h1>
                    <span>{{ $restaurant->name }}</span>
                </div>
            </div>

            <div class="header-stats">
                <div class="stat-badge urgent" id="stat-overdue">
                    <div>
                        <div class="stat-value" id="overdue-count">0</div>
                        <div class="stat-label">Overdue</div>
                    </div>
                </div>
                <div class="stat-badge preparing" id="stat-preparing">
                    <div>
                        <div class="stat-value" id="preparing-count">0</div>
                        <div class="stat-label">Preparing</div>
                    </div>
                </div>
                <div class="stat-badge pending" id="stat-pending">
                    <div>
                        <div class="stat-value" id="pending-count">0</div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>

            <div class="header-time">
                <div class="current-time" id="current-time">00:00:00</div>
                <div class="last-refresh">Last refresh: <span id="last-refresh">--:--:--</span></div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="orders-grid" id="orders-container">
            <!-- Orders will be dynamically loaded here -->
            <div class="empty-state" id="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                        <line x1="6" x2="18" y1="17" y2="17"/>
                    </svg>
                </div>
                <h2>No Active Orders</h2>
                <p>New orders will appear here automatically</p>
            </div>
        </div>
    </main>

    <!-- Connection Status -->
    <div class="connection-status" id="connection-status">
        <div class="connection-dot"></div>
        <span class="connection-text">Live Updates Active</span>
    </div>

    <!-- Notification Toast -->
    <div class="notification-toast" id="notification-toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
        </svg>
        <span id="toast-message">New order received!</span>
    </div>

    <!-- Audio for new orders -->
    <audio id="order-sound" preload="auto">
        <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YVoGAACBhYqFbF1iZmZkZHF3eXd1dnZ4eXl4d3d3d3d4eXl5eHd3d3d4eHh4d3d3d3h4eHh3d3d3eHh4eHd3d3d4eHh4d3d3d3h4eHh3d3d3eHh4eHd3d3d4eHh4d3d3d3h4eHh3d3d3eHh4eHd3d3d4eHh4" type="audio/wav">
    </audio>

    <script>
        const token = "{{ $restaurant->kitchen_token }}";
        const apiUrl = `/kitchen/api/${token}/orders`;
        let previousOrderCount = 0;

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
            document.getElementById('current-time').textContent = timeStr;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Fetch and render orders
        async function fetchOrders() {
            try {
                const response = await fetch(apiUrl);
                const data = await response.json();

                if (data.success) {
                    renderOrders(data.orders);
                    updateStats(data.stats);
                    document.getElementById('last-refresh').textContent = data.timestamp;

                    // New order notification
                    if (data.orders.length > previousOrderCount && previousOrderCount !== 0) {
                        showNotification('New order received!');
                        playSound();
                    }
                    previousOrderCount = data.orders.length;

                    // Update connection status
                    document.getElementById('connection-status').classList.remove('disconnected');
                }
            } catch (error) {
                console.error('Failed to fetch orders:', error);
                document.getElementById('connection-status').classList.add('disconnected');
                document.querySelector('.connection-text').textContent = 'Connection Lost';
            }
        }

        // Render orders
        function renderOrders(orders) {
            const container = document.getElementById('orders-container');
            const emptyState = document.getElementById('empty-state');

            if (orders.length === 0) {
                container.innerHTML = '';
                container.appendChild(emptyState);
                emptyState.style.display = 'flex';
                return;
            }

            emptyState.style.display = 'none';

            const ordersHtml = orders.map(order => `
                <div class="order-card ${order.is_vip ? 'vip' : ''} sla-${order.sla_status}" data-order-id="${order.id}">
                    <div class="order-header">
                        <div class="order-table">
                            <div class="table-number">${order.table_number}</div>
                            <div class="table-info">
                                <h3>Table ${order.table_number}</h3>
                                <span>Order #${String(order.id).padStart(6, '0')} • ${order.waiter_name}</span>
                            </div>
                        </div>
                        <div class="order-timer">
                            <div class="timer-value ${order.sla_status}">${order.elapsed_time}</div>
                            <div class="timer-label">Elapsed</div>
                        </div>
                    </div>
                    ${order.is_vip ? '<div style="padding: 0 1.25rem;"><div class="vip-badge"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> VIP Priority</div></div>' : ''}
                    <div class="order-items">
                        ${order.items.map(item => `
                            <div class="order-item ${item.status}" data-item-id="${item.id}" onclick="toggleItemStatus('${item.id}', '${item.status}')">
                                <div class="item-quantity">${item.quantity}x</div>
                                <div class="item-details">
                                    <div class="item-name">${item.name}</div>
                                    ${item.notes ? `<div class="item-notes">${item.notes}</div>` : ''}
                                </div>
                                <div class="item-status ${item.status}">${item.status}</div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="order-actions">
                        ${order.status === 'pending' || order.status === 'confirmed' ? `
                            <button class="action-btn start" onclick="updateOrderStatus(${order.id}, 'preparing')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/></svg>
                                Start Cooking
                            </button>
                        ` : ''}
                        ${order.status === 'preparing' ? `
                            <button class="action-btn ready" onclick="updateOrderStatus(${order.id}, 'ready')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Mark Ready
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('');

            container.innerHTML = ordersHtml;
        }

        // Update stats
        function updateStats(stats) {
            document.getElementById('overdue-count').textContent = stats.overdue;
            document.getElementById('preparing-count').textContent = stats.preparing;
            document.getElementById('pending-count').textContent = stats.pending;
        }

        // Update order status
        async function updateOrderStatus(orderId, status) {
            try {
                const response = await fetch(`/kitchen/api/${token}/order/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order_id: orderId, status: status })
                });
                
                const data = await response.json();
                if (data.success) {
                    fetchOrders();
                    showNotification(`Order marked as ${status}!`);
                }
            } catch (error) {
                console.error('Failed to update order:', error);
            }
        }

        // Toggle item status
        async function toggleItemStatus(itemId, currentStatus) {
            const statusFlow = { 'pending': 'cooking', 'cooking': 'ready', 'ready': 'pending' };
            const newStatus = statusFlow[currentStatus] || 'cooking';

            try {
                const response = await fetch(`/kitchen/api/${token}/item/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ item_id: itemId, status: newStatus })
                });
                
                const data = await response.json();
                if (data.success) {
                    fetchOrders();
                }
            } catch (error) {
                console.error('Failed to update item:', error);
            }
        }

        // Show notification
        function showNotification(message) {
            const toast = document.getElementById('notification-toast');
            document.getElementById('toast-message').textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 4000);
        }

        // Play sound
        function playSound() {
            const sound = document.getElementById('order-sound');
            sound.currentTime = 0;
            sound.play().catch(() => {});
        }

        // Initial fetch and interval
        fetchOrders();
        setInterval(fetchOrders, 5000); // Refresh every 5 seconds
    </script>
</body>
</html>
