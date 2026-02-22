# Storage Paths (storage za kwenye root)

Faili zote za upload (picha za wasifu, menu images, n.k.) zinahifadhiwa chini ya folder **`storage`** ya root ya project.

## Muundo

```
storage/                    ← storage ya kwenye root
└── app/
    └── public/             ← disk 'public' (php artisan storage:link inalenga hapa)
        ├── profile/        ← waiter profile photos (upload: Waiter ProfileController)
        ├── menu_images/    ← restaurant menu image (upload: Manager MenuImageController)
        ├── menu_items/     ← picha za vyakula (menu items)
        └── categories/     ← picha za categories
```

## Path na URL ya ku-fetch

| Aina            | Path kwenye disk                    | URL ya ku-fetch (code) |
|-----------------|-------------------------------------|-------------------------|
| **Profile** (waiter upload) | `storage/app/public/profile/{file}`  | `User::profilePhotoUrl()` |
| **Menu image** (manager upload) | `storage/app/public/menu_images/{file}` | `Restaurant::menuImageUrl()` |

- **Profile:** Waiter anapo-upload picha ya wasifu → inaenda `storage/app/public/profile/`. Kuonyesha: `$user->profilePhotoUrl()` (inatuma `asset('storage/'.$profile_photo_path)`).
- **Menu image:** Manager anapo-upload menu image → inaenda `storage/app/public/menu_images/`. Kuonyesha: `$restaurant->menuImageUrl()` (inatuma `asset('storage/'.$menu_image)`).

## Muhimu

- Endesha **`php artisan storage:link`** ili `public/storage` iungane na `storage/app/public` (kisha URL za picha zifanye kazi).
- Faili zote za profile na menu_images ziko **ndani ya storage** ya root, si nje.
