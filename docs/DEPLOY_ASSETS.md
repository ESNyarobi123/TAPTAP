# Ku-deploy Vite assets (CSS/JS) kwenye host

## Shida
- **Local:** UI inaonekana vizuri (Tailwind, layout).
- **Host:** Ukurasa inaonekana vibaya (rangi, spacing, vitufe) — kwa sababu CSS/JS za Vite hazijapaki.

## Sababu
Laravel inatumia **Vite**. Faili za CSS/JS zinajengwa na Vite na huwekwa kwenye `public/build/`. Folder hii iko kwenye `.gitignore`, hivyo **haipaki** wakati wa `git push` au deploy. Kwenye host hakuna faili za build → `@vite()` haipati manifest → Tailwind na app.css hazijapaki.

## Suluhu (chagua moja)

### A. Build kwenye server (baada ya deploy)
Baada ya ku-pull code kwenye host:

```bash
cd /path/to/your/project
npm ci --omit=dev    # au: npm install
npm run build
```

Hii inatengeneza `public/build/` na `public/build/manifest.json`. Ukurasa wa register (na wote) utapata CSS/JS.

### B. Build kwenye machine yako, kisha upload build folder
Kwenye laptop yako:

```bash
npm run build
```

Kisha upload folder **`public/build`** kwenye host (kwa FTP, SCP, au deploy script) kwenye path: `public/build/` ya project.

### C. Deploy script (mfano)
Ikiwa unatumia script ya deploy:

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci --omit=dev
npm run build
php artisan config:cache
php artisan view:clear
```

## Kuthibitisha
- Kwenye host, hakikisha faili zipo: `public/build/manifest.json` na `public/build/assets/*.css`, `*.js`.
- Fungua ukurasa wa register-waiter kwenye browser → **View Page Source** → utaona `<link href="/build/assets/...">`. Ikiwa link ina 404, bado build haija deploy.

## Kumbuka
- **Si shida ya vendor** (Composer) — ni **assets za frontend** (Vite/Tailwind).
- Baada ya kufanya build na ku-deploy `public/build`, UI kwenye host itafanana na local.
