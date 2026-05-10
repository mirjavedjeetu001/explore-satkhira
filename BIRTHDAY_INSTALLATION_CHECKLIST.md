# জন্মদিন ফিচার - ইনস্টলেশন চেকলিস্ট

## তৈরি করা ফাইলসমূহ

### Database Migrations ✅
- [x] `database/migrations/2026_05_10_000001_add_birthday_to_users_table.php`
- [x] `database/migrations/2026_05_10_000002_create_birthday_cards_table.php`
- [x] `database/migrations/2026_05_10_000003_create_birthday_card_comments_table.php`

### Models ✅
- [x] `app/Models/BirthdayCard.php`
- [x] `app/Models/BirthdayCardComment.php`
- [x] `app/Models/User.php` (আপডেটেড)

### Controllers ✅
- [x] `app/Http/Controllers/Admin/BirthdayController.php`
- [x] `app/Http/Controllers/BirthdayCardController.php`

### Routes ✅
- [x] `routes/web.php` (আপডেটেড - Admin routes, Public routes, Imports)

### Views ✅
- [x] `resources/views/admin/birthdays/index.blade.php`
- [x] `resources/views/admin/birthdays/edit.blade.php`
- [x] `resources/views/admin/birthdays/todays.blade.php`
- [x] `resources/views/birthday-cards/show.blade.php`
- [x] `resources/views/birthday-cards/todays.blade.php`
- [x] `resources/views/frontend/home.blade.php` (আপডেটেড)
- [x] `resources/views/frontend/dashboard/profile.blade.php` (আপডেটেড)

### Commands ✅
- [x] `app/Console/Commands/GenerateBirthdayCards.php`
- [x] `app/Console/Kernel.php`

### Documentation ✅
- [x] `BIRTHDAY_FEATURE_SETUP.md`

## আপডেট করা ফাইলসমূহ

### Controllers
- [x] `app/Http/Controllers/HomeController.php`
  - BirthdayCard import যোগ করা
  - `todaysBirthdays` ডেটা fetch করা হোমপেজ ভিউতে পাঠানো

- [x] `app/Http/Controllers/UserDashboardController.php`
  - `updateProfile()` method-এ `date_of_birth` validation যোগ করা
  - রোল-ভিত্তিক অ্যাক্সেস চেক

### Models
- [x] `app/Models/User.php`
  - `date_of_birth` fillable array-এ যোগ করা
  - `date_of_birth` casts-এ যোগ করা
  - `birthdayCard()` relationship যোগ করা

### Views
- [x] `resources/views/frontend/home.blade.php`
  - "আজকের জন্মদিন" সেকশন যোগ করা (হিরো স্লাইডার এবং সার্চ বক্সের মধ্যে)

- [x] `resources/views/frontend/dashboard/profile.blade.php`
  - জন্মতারিখ ইনপুট ফিল্ড যোগ করা (Admin/Moderator/Upazila Moderator এর জন্য)

### Routes
- [x] `routes/web.php`
  - `BirthdayCardController` import যোগ করা
  - `Admin\BirthdayController` import যোগ করা
  - Admin birthdays routes যোগ করা (prefix: admin/birthdays)
  - Public birthday-cards routes যোগ করা

## ডাটাবেস পরিবর্তন

### users Table
```sql
ALTER TABLE users ADD COLUMN date_of_birth DATE NULL AFTER phone;
```

### নতুন Tables
```sql
CREATE TABLE birthday_cards (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  birthday_date DATE NOT NULL,
  card_image VARCHAR(255) NULL,
  bengali_message LONGTEXT NULL,
  english_message LONGTEXT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE (user_id, birthday_date),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE birthday_card_comments (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  birthday_card_id BIGINT NOT NULL,
  visitor_name VARCHAR(255) NOT NULL,
  visitor_phone VARCHAR(255) NOT NULL,
  wish_message LONGTEXT NOT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE (birthday_card_id, visitor_phone),
  UNIQUE (visitor_phone),
  FOREIGN KEY (birthday_card_id) REFERENCES birthday_cards(id) ON DELETE CASCADE
);
```

## সেটআপ স্টেপস

### Step 1: মাইগ্রেশন চালান
```bash
php artisan migrate
```
✅ এটি সমস্ত নতুন টেবিল এবং কলাম তৈরি করবে

### Step 2: ক্যাশ ক্লিয়ার করুন
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Step 3: সার্ভার রিস্টার্ট করুন (যদি প্রয়োজন)
```bash
php artisan serve
```

### Step 4: Cron জব সেটআপ করুন (অপশনাল কিন্তু সুপারিশকৃত)
```bash
crontab -e
# নিম্নলিখিত লাইন যোগ করুন:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## ফিচার পরীক্ষা করুন

### ১. Admin Panel চেক করুন
```
✅ URL: /admin/birthdays
✅ সমস্ত Admin/Moderator/Upazila Moderators তালিকা দেখা যায়
✅ এডিট ফর্ম কাজ করে
✅ তারিখ সংরক্ষণ করা যায়
```

### ২. ব্যবহারকারী প্রোফাইল চেক করুন
```
✅ URL: /dashboard/profile
✅ Admin/Moderator/Upazila Moderator এর জন্য জন্মতারিখ ফিল্ড দেখা যায়
✅ তারিখ সংরক্ষণ করা যায়
```

### ৩. হোমপেজ চেক করুন
```
✅ URL: /
✅ "আজকের জন্মদিন" সেকশন দেখা যায় (যদি আজ কেউর জন্মদিন থাকে)
✅ লিঙ্ক সঠিকভাবে কাজ করে
```

### ৪. জন্মদিন কার্ড পেজ চেক করুন
```
✅ URL: /birthday-cards/todays
✅ আজকের জন্মদিনের কার্ড দেখা যায়
✅ শুভেচ্ছা যোগ করার ফর্ম কাজ করে
```

### ৫. পরীক্ষা টেস্ট ডেটা দিয়ে (ঐচ্ছিক)
```bash
# কনসোল কমান্ড ম্যানুয়ালি চালান:
php artisan birthdays:generate
```

## সম্ভাব্য সমস্যা

### সমস্যা: "Class not found" এরর
**সমাধান:** 
```bash
php artisan clear-compiled
composer dump-autoload
```

### সমস্যা: মাইগ্রেশন ফেইল হচ্ছে
**সমাধান:** 
```bash
php artisan migrate:reset
php artisan migrate
```

### সমস্যা: ভিউ ফাইল দেখা যাচ্ছে না
**সমাধান:** 
```bash
php artisan view:clear
php artisan cache:clear
```

### সমস্যা: "UNIQUE constraint failed" এরর
**কারণ:** একই ব্যবহারকারী একাধিকবার কমেন্ট করার চেষ্টা  
**সমাধান:** এটি স্বাভাবিক, আপনার ফিচার ঠিকভাবে কাজ করছে

## দ্রুত রেফারেন্স

### URL Map
```
Dashboard: /admin/birthdays
Today's Page: /birthday-cards/todays
Birthday Card: /birthday-cards/{id}
Profile: /dashboard/profile
```

### Important Commands
```
php artisan migrate              # মাইগ্রেশন চালান
php artisan birthdays:generate   # জন্মদিন কার্ড তৈরি করুন
php artisan tinker               # ডেটাবেস পরীক্ষা করুন
```

### Database Queries (Testing)
```php
// Tinker-এ চালান: php artisan tinker

// আজকের জন্মদিন দেখুন
$today = now()->format('m-d');
App\Models\User::whereNotNull('date_of_birth')
    ->get()
    ->filter(fn($u) => $u->date_of_birth?->format('m-d') === $today)
    ->map(fn($u) => $u->name);

// সমস্ত কমেন্ট দেখুন
App\Models\BirthdayCardComment::all();

// নির্দিষ্ট কার্ড দেখুন
App\Models\BirthdayCard::with('comments')->find(1);
```

---

**স্ট্যাটাস:** সম্পূর্ণ  
**শেষ আপডেট:** ১০ মে ২০২৬  
**পরীক্ষা করা হয়েছে:** ❌ (স্থানীয় সার্ভারে পরীক্ষা করুন)
