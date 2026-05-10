# জন্মদিন ব্যবস্থাপনা ফিচার - সেটআপ গাইড

## সারাংশ

এটি একটি সম্পূর্ণ জন্মদিন ব্যবস্থাপনা সিস্টেম যা Admin, Moderator এবং Upazila Moderator এর জন্যদিন ট্র্যাক করে এবং তাদের জন্মদিনে হোমপেজে একটি বিশেষ কার্ড প্রদর্শন করে।

## ফিচারসমূহ

✅ **Admin Panel:**
- জন্মদিন ব্যবস্থাপনা ইন্টারফেস
- সমস্ত Admin/Moderator/Upazila Moderator এর তালিকা
- প্রতিটি ব্যবহারকারীর জন্মতারিখ এডিট করার ক্ষমতা
- আজকের জন্মদিন দেখার পেজ

✅ **ব্যবহারকারী প্রোফাইল:**
- প্রোফাইল থেকে জন্মতারিখ যোগ করা/এডিট করা (শুধুমাত্র Admin/Moderator/Upazila Moderator)
- যেকোনো সময় পরিবর্তন করার সুবিধা

✅ **পাবলিক ফিচার:**
- হোমপেজে আজকের জন্মদিনের ঘোষণা
- আলাদা "আজকের জন্মদিন" পেজ
- সুন্দর জন্মদিন কার্ড ডিসপ্লে
- দর্শকদের শুভেচ্ছা কমেন্ট সিস্টেম (নাম + ফোন নম্বর দিয়ে)
- প্রতিটি ব্যক্তি শুধুমাত্র একবার মন্তব্য করতে পারে
- কার্ড ডাউনলোড/শেয়ার ফিচার (বাংলা মেসেজ সাপোর্ট)

✅ **অটোমেশন:**
- দৈনিক রাত ১২টায় স্বয়ংক্রিয়ভাবে জন্মদিন কার্ড তৈরি হয়

## ইনস্টলেশন স্টেপস

### ১. ডাটাবেস মাইগ্রেশন চালান

```bash
php artisan migrate
```

এই কমান্ড তিনটি নতুন টেবিল তৈরি করবে:
- `users` টেবিলে `date_of_birth` কলাম যোগ হবে
- `birthday_cards` নতুন টেবিল
- `birthday_card_comments` নতুন টেবিল

### ২. ক্যাশ ক্লিয়ার করুন (ঐচ্ছিক কিন্তু সুপারিশকৃত)

```bash
php artisan config:clear
php artisan cache:clear
```

### ৩. সার্ভার চালু করুন

```bash
php artisan serve
```

## ব্যবহার গাইড

### Admin/Moderator এর জন্য

#### পদ্ধতি ১: Admin Panel থেকে
1. Admin Dashboard-এ যান
2. **জন্মদিন ব্যবস্থাপনা** মেনু খুঁজুন
3. **সমস্ত ব্যবহারকারী** দেখুন
4. যে ব্যবহারকারীর জন্মতারিখ যোগ করতে চান তার পাশে **এডিট** বাটন ক্লিক করুন
5. তারিখ নির্বাচন করে সেভ করুন

#### পদ্ধতি ২: ব্যক্তিগত প্রোফাইল থেকে
1. ড্যাশবোর্ড > প্রোফাইল সম্পাদনা যান
2. "জন্মতারিখ" ফিল্ড খুঁজুন
3. তারিখ নির্বাচন করে আপডেট করুন

### দর্শকদের জন্য

1. হোমপেজ খোলুন
2. "আজকের জন্মদিন" সেকশন দেখুন
3. সম্পূর্ণ পেজ দেখতে ক্লিক করুন
4. জন্মদিনের কার্ড দেখুন
5. নাম, ফোন নম্বর এবং শুভেচ্ছা লিখুন
6. শুভেচ্ছা পাঠান

## ডাটাবেস স্ট্রাকচার

### users টেবিল (নতুন কলাম)
```
date_of_birth: DATE (nullable)
```

### birthday_cards টেবিল
```
id: Integer (PK)
user_id: Integer (FK)
birthday_date: DATE
card_image: String (nullable)
bengali_message: TEXT (nullable)
english_message: TEXT (nullable)
created_at: TIMESTAMP
updated_at: TIMESTAMP
```

### birthday_card_comments টেবিল
```
id: Integer (PK)
birthday_card_id: Integer (FK)
visitor_name: String
visitor_phone: String (Unique globally, Unique per card)
wish_message: TEXT
created_at: TIMESTAMP
updated_at: TIMESTAMP
```

## কনসোল কমান্ড

### ম্যানুয়াল জন্মদিন কার্ড তৈরি করুন
```bash
php artisan birthdays:generate
```

এই কমান্ড:
- আজকের তারিখ খুঁজে দেখে
- সব Admin/Moderator/Upazila Moderators খুঁজে যাদের আজ জন্মদিন
- প্রতিটির জন্য একটি জন্মদিন কার্ড তৈরি করে

## ডেভেলপার তথ্য

### Models
- `App\Models\BirthdayCard`
- `App\Models\BirthdayCardComment`
- `App\Models\User` (আপডেটেড)

### Controllers
- `App\Http\Controllers\Admin\BirthdayController` - Admin প্যানেল
- `App\Http\Controllers\BirthdayCardController` - পাবলিক ফিচার

### Routes
```
Admin:
  GET  /admin/birthdays                           - সমস্ত ব্যবহারকারী
  GET  /admin/birthdays/todays                   - আজকের জন্মদিন
  GET  /admin/birthdays/{user}/edit              - এডিট ফর্ম
  PUT  /admin/birthdays/{user}                   - আপডেট করুন

Public:
  GET  /birthday-cards/todays                    - আজকের জন্মদিন পেজ
  GET  /birthday-cards/{id}                      - কার্ড দেখুন
  POST /birthday-cards/{id}/comment              - শুভেচ্ছা যোগ করুন
  POST /birthday-cards/{id}/download             - ডাউনলোড করুন
```

### Views
- `resources/views/admin/birthdays/`
  - `index.blade.php` - সমস্ত ব্যবহারকারী
  - `edit.blade.php` - এডিট ফর্ম
  - `todays.blade.php` - আজকের জন্মদিন

- `resources/views/birthday-cards/`
  - `show.blade.php` - কার্ড ডিটেইল পেজ
  - `todays.blade.php` - আজকের জন্মদিন পেজ

- `resources/views/frontend/home.blade.php` (আপডেটেড)
  - হোমপেজে জন্মদিন সেকশন যোগ

## নিরাপত্তা

✅ শুধুমাত্র প্রমাণীকৃত ব্যবহারকারীরা শুভেচ্ছা যোগ করতে পারে
✅ প্রতিটি ফোন নম্বর শুধুমাত্র একবার শুভেচ্ছা যোগ করতে পারে
✅ Admin panel রোল-ভিত্তিক অ্যাক্সেস নিয়ন্ত্রণ দিয়ে সুরক্ষিত

## স্বয়ংক্রিয় শিডিউলিং

জন্মদিন কার্ড স্বয়ংক্রিয়ভাবে প্রতিদিন রাত ১২টায় তৈরি হয়। এর জন্য আপনার সার্ভারে Laravel Task Scheduler সক্রিয় থাকতে হবে।

### Linux/Unix-এ Cron সেটআপ
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

এই লাইন টি crontab-এ যোগ করুন:
```bash
crontab -e
```

## সম্ভাব্য সমস্যা সমাধান

### Q: জন্মদিন কার্ড তৈরি হচ্ছে না?
A: নিশ্চিত করুন যে:
1. মাইগ্রেশন চালানো হয়েছে
2. ব্যবহারকারীর `date_of_birth` সেট করা আছে
3. সার্ভার স্বয়ংক্রিয় টাস্ক সমর্থন করে অথবা ম্যানুয়াল কমান্ড চালান

### Q: শুভেচ্ছা সেভ হচ্ছে না?
A: নিশ্চিত করুন যে:
1. ফোন নম্বর সঠিক ফরম্যাটে আছে
2. একই ফোন নম্বর আগে ব্যবহার করা হয়নি

### Q: হোমপেজে জন্মদিন সেকশন দেখা যাচ্ছে না?
A: নিশ্চিত করুন যে:
1. `HomeController` আপডেট করা হয়েছে
2. ভিউ ফাইল সঠিকভাবে ক্যাশ করা হয়েছে (`php artisan view:clear`)

## ভবিষ্যতের সম্প্রসারণ

সম্ভাব্য উন্নতি:
- 📧 জন্মদিনের দিন ইমেইল নোটিফিকেশন
- 🎁 কাস্টম জন্মদিন কার্ড টেমপ্লেট
- 🎨 কার্ড ডিজাইনার টুল
- 📊 জন্মদিন পরিসংখ্যান
- 🔔 পুশ নোটিফিকেশন

## সহায়তা

যেকোনো সমস্যা বা প্রশ্নের জন্য Admin Panel-এ যোগাযোগ করুন অথবা সিস্টেম অ্যাডমিনিস্ট্রেটরকে জানান।

---

**সংস্করণ:** ১.০  
**তারিখ:** ১০ মে ২০২৬  
**ভাষা:** বাংলা ও ইংরেজি সাপোর্ট
