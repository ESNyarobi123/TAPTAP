# TAP Brand Implementation - Code Changes

## Summary of Changes Required

### 1. SHOW HOME SCREEN (Welcome)
**Current:** Single long list with all options
**New:** Welcome message + Quick Actions + Full Services button

```javascript
// BEFORE:
rows = [
    { id: 'view_menu', title: '🍽️ View Menu', description: '...' },
    { id: 'rate_service', title: '⭐ Rate Service', description: '...' },
    { id: 'live_bill', title: '💳 Pay Bill', description: '...' },
    // ... 8 more items
]

// AFTER:
// Step 1: Send Welcome Text with Quick Buttons
await sendButtons(sock, from, 
    `🔵 *KARIBU ${name}*\n\n🧑‍🍳 ${session.waiter_name || 'Mhudumu'}\n🪑 ${session.table_number || '-'}\n\nNinaweza kukusaidia nini?`,
    [
        { id: 'view_menu', text: '🍽️ Tazama Menyu' },
        { id: 'call_waiter', text: '🔔 Mwambie Mhudumu' },
        { id: 'pay_bill', text: '💳 Lipa Bili' }
    ]
);

// Step 2: Follow with Services List button
await sendList(sock, from, 
    '📋 *HUDUMA ZOTE*',
    '🟣 Chagua huduma',
    sections, // Categorized
    '🏠 Home',
    '0️⃣ Rudi nyuma'
);
```

### 2. NEW SERVICES LIST SCREEN
**New function:** `showServicesList()`

```javascript
async function showServicesList(sock, from, session) {
    session.state = 'SERVICES_LIST';
    
    const sections = [
        {
            title: '🍽️ CHAKULA NA KINYWAJI',
            rows: [
                { id: 'view_menu', title: 'Tazama Menyu', description: 'Chakula, vinywaji, matoleo' },
                { id: 'call_waiter', title: 'Mwambie Mhudumu', description: 'Erick atakuja haraka' }
            ]
        },
        {
            title: '💳 MALIPO',
            rows: [
                { id: 'live_bill', title: 'Lipa Bili', description: 'M-Pesa, Airtel, Cash, Card' },
                { id: 'give_tips', title: 'Mpe Tip', description: 'Mshukuru mhudumu' }
            ]
        },
        {
            title: '⭐ MAONI NA MSAADA',
            rows: [
                { id: 'rate_service', title: 'Pima Huduma', description: 'Mpe Erick alama' },
                { id: 'customer_support', title: 'Msaada wa Wateja', description: 'Wasiliana na timu' }
            ]
        },
        {
            title: '⚙️ MIPANGILIO',
            rows: [
                { id: 'change_language', title: '🌐 Badilisha Lugha', description: 'Swahili / English' },
                { id: 'exit_bot', title: '❌ Maliza', description: 'Toka kwenye bot' }
            ]
        }
    ];
    
    await sendList(sock, from,
        '🟣 *HUDUMA ZOTE*\n\nChagua kundi la huduma:',
        '📋 Chagua',
        sections,
        '🏠 SAMAKI SAMAKI',
        '0️⃣ Rudi nyuma'
    );
}
```

### 3. PAY & TIP SCREEN
**New function:** `showPayAndTip()`

```javascript
async function showPayAndTip(sock, from, session) {
    session.state = 'PAY_TIP_MENU';
    
    const sections = [
        {
            title: '💳 LIPA BILI',
            rows: [
                { id: 'view_bill', title: '👀 Angalia Bili', description: 'Ona vitu uliyoagiza' },
                { id: 'pay_now', title: '💳 Lipa Sasa', description: 'M-Pesa, Airtel, Cash' }
            ]
        },
        {
            title: '💵 MPE TIP',
            rows: [
                { id: 'tip_1000', title: '💵 TSh 1,000', description: 'Quick tip' },
                { id: 'tip_2000', title: '💵 TSh 2,000', description: 'Quick tip' },
                { id: 'tip_5000', title: '💵 TSh 5,000', description: 'Quick tip' },
                { id: 'tip_custom', title: '✏️ Kiasi Kingine', description: 'Weka kiasi chako' }
            ]
        }
    ];
    
    await sendList(sock, from,
        '💠 *LIPA & TIP*\n\nChagua nini unataka kufanya:',
        '💳 Chagua',
        sections,
        '🏠 Home',
        '0️⃣ Rudi nyuma'
    );
}
```

### 4. RATE & MORE SCREEN
**New function:** `showRateAndMore()`

```javascript
async function showRateAndMore(sock, from, session) {
    session.state = 'RATE_MORE_MENU';
    
    const sections = [
        {
            title: '⭐ PIMA HUDUMA',
            rows: [
                { id: 'rate_waiter', title: '⭐ Mpe Erick Alama', description: '1-5 stars' },
                { id: 'leave_comment', title: '📝 Maoni Yako', description: 'Andika ujumbe' }
            ]
        },
        {
            title: '🌐 LUGHA',
            rows: [
                { id: 'lang_sw', title: '🇹🇿 Kiswahili', description: 'Badilisha lugha' },
                { id: 'lang_en', title: '🇬🇧 English', description: 'Change language' }
            ]
        },
        {
            title: '❌ MALIZA',
            rows: [
                { id: 'exit_bot', title: '👋 Kwa Heri', description: 'Toka kwenye bot' }
            ]
        }
    ];
    
    await sendList(sock, from,
        '⭐ *PIMA & ZAIDI*\n\nChagua kipengele:',
        '⭐ Chagua',
        sections,
        '🏠 Home',
        '0️⃣ Rudi nyuma'
    );
}
```

### 5. STATE HANDLER UPDATES

```javascript
// Add new cases in processMessage() switch:
case 'HOME':
    // Now redirects to specific screens
    if (text === 'view_menu') await handleViewMenu(sock, from, session);
    else if (text === 'services_list') await showServicesList(sock, from, session);
    else if (text === 'pay_tip') await showPayAndTip(sock, from, session);
    else if (text === 'rate_more') await showRateAndMore(sock, from, session);
    break;

case 'SERVICES_LIST':
    // Handle services selection
    await handleServicesListState(sock, from, session, text);
    break;

case 'PAY_TIP_MENU':
    // Handle pay/tip selection
    await handlePayTipState(sock, from, session, text);
    break;

case 'RATE_MORE_MENU':
    // Handle rate/more selection
    await handleRateMoreState(sock, from, session, text);
    break;
```

## Files to Modify

1. **tiptopbot/src/handler.js**
   - Refactor `showHomeScreen()` (lines ~1222)
   - Add `showServicesList()`
   - Add `showPayAndTip()`
   - Add `showRateAndMore()`
   - Add state handlers
   - Update menu_options mapping

2. **tiptopbot/src/lang.js** (if separate)
   - Update `home_welcome` → Add 🔵
   - Update `menu_view` → Add 🍽️
   - Add new strings for sections

3. **Test Commands**
   - `npm test` (if available)
   - Manual test: Send messages to bot

## Deployment Steps

```bash
# 1. Local changes
cd /Users/eunice/LARAVEL PROJECT/TPTAP/TAPTAP_sauth/tiptopbot
# Edit handler.js

# 2. Commit & push
git add -A
git commit -m "feat(whatsapp): TAP Brand UI restructure - 4-section menu flow"
git push origin main

# 3. Deploy to VPS
./deploy.sh
# OR manually:
sshpass -p 'HMF$_h9TMN6XT.x' ssh root@139.59.151.111 "cd /root/TIPTAP/tiptopbot && git pull && docker compose restart"

# 4. Test
# Send message to WhatsApp bot
# Verify new menu flow works
```

## Rollback Plan

If issues occur:
```bash
# Revert to previous commit
git revert HEAD
git push origin main

# Restart bot
sshpass -p 'HMF$_h9TMN6XT.x' ssh root@139.59.151.111 "cd /root/TIPTAP && docker compose restart"
```

## Time Estimate

- **Implementation:** 2-3 hours
- **Testing:** 1 hour
- **Deployment:** 30 minutes
- **Total:** ~4 hours
