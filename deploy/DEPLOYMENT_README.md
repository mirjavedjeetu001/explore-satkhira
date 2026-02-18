# Explore Satkhira - Deployment Guide for Shared Hosting

## Server Information
- **URL:** demosatkhira.metasoftinfo.com
- **Home Directory:** /home/metasoft
- **Database:** metasoft_satkhira
- **Database User:** metasoft_satkhira
- **Database Password:** JavedMir41@@@

## Folder Structure on Server
```
/home/metasoft/
├── satkhira-app/              # Laravel application files
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── lang/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── ...
│
└── public_html/
    └── demosatkhira/          # Public files (web accessible)
        ├── index.php          # Modified to point to satkhira-app
        ├── .htaccess
        ├── build/
        ├── css/
        ├── js/
        ├── storage -> /home/metasoft/satkhira-app/storage/app/public
        └── ...
```

## Deployment Steps

### Step 1: Create MySQL Database
1. Login to cPanel
2. Go to MySQL Databases
3. Create database: `metasoft_satkhira`
4. Create user: `metasoft_satkhira` with password: `JavedMir41@@@`
5. Add user to database with ALL PRIVILEGES

### Step 2: Upload Application Files
1. Upload `satkhira-app.zip` to `/home/metasoft/`
2. Extract the zip file: `unzip satkhira-app.zip`
3. This will create `/home/metasoft/satkhira-app/` folder

### Step 3: Upload Public Files
1. Upload contents of `demosatkhira/` folder to `/home/metasoft/public_html/demosatkhira/`
2. Make sure `.htaccess` and `index.php` are uploaded

### Step 4: Configure Environment
1. SSH into server or use File Manager
2. Navigate to `/home/metasoft/satkhira-app/`
3. Rename `.env.production` to `.env`
4. Update `.env` if needed:
```
APP_URL=https://demosatkhira.metasoftinfo.com
DB_DATABASE=metasoft_satkhira
DB_USERNAME=metasoft_satkhira
DB_PASSWORD=JavedMir41@@@
```

### Step 5: Set Permissions
Run these commands via SSH or Terminal:
```bash
cd /home/metasoft/satkhira-app
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 6: Create Storage Symlink
```bash
cd /home/metasoft/public_html/demosatkhira
ln -s /home/metasoft/satkhira-app/storage/app/public storage
```

### Step 7: Run Migrations & Seed Database
Via SSH:
```bash
cd /home/metasoft/satkhira-app
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder --force
```

Or via cPanel Terminal.

### Step 8: Optimize for Production
```bash
cd /home/metasoft/satkhira-app
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Login Credentials
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@satkhira.com | password |
| Moderator | moderator@satkhira.com | password |
| User | user@satkhira.com | password |

## Troubleshooting

### 500 Internal Server Error
- Check storage permissions: `chmod -R 775 storage`
- Check bootstrap/cache permissions: `chmod -R 775 bootstrap/cache`
- Check error logs in `/home/metasoft/satkhira-app/storage/logs/laravel.log`

### Database Connection Error
- Verify database credentials in `.env`
- Make sure database user has all privileges

### Storage/Images Not Loading
- Recreate symlink:
```bash
rm /home/metasoft/public_html/demosatkhira/storage
ln -s /home/metasoft/satkhira-app/storage/app/public /home/metasoft/public_html/demosatkhira/storage
```

### Clear Caches
```bash
cd /home/metasoft/satkhira-app
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Important Notes
- Always backup database before any changes
- Test on staging before production updates
- Keep `.env` file secure and never commit to git
