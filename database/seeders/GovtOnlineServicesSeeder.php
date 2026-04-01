<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GovtOnlineServicesSeeder extends Seeder
{
    public function run(): void
    {
        // Category 15 = Online Services (অনলাইন সেবা)

        $listings = [
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Bhumi Seva Portal (Land Services)',
                'title_bn' => 'ভূমি সেবা পোর্টাল',
                'short_description' => 'Online land management services portal - mutation, land tax, records & maps.',
                'description' => 'ভূমি মন্ত্রণালয়ের সকল সেবা একটি পোর্টালে। সাতক্ষীরা জেলার নাগরিকেরা এখান থেকে ভূমি সংক্রান্ত বিভিন্ন সেবা যেমন মিউটেশন, ভূমি উন্নয়ন কর, ভূমি রেকর্ড ও ম্যাপ দেখতে পারবেন। ভূমি সেবা হটলাইন ১৬১২২ নম্বরে কল করে সহায়তা পাওয়া যাবে।',
                'phone' => '16122',
                'website' => 'https://land.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'e-Namjari (Land Mutation Online)',
                'title_bn' => 'ই-নামজারি (অনলাইন মিউটেশন)',
                'short_description' => 'Online land mutation (namjari/kharij) application, fee payment & status tracking.',
                'description' => 'ভূমির মালিকানা পরিবর্তন বা সংশোধনের জন্য অনলাইনে নামজারি আবেদন করা যায় এই পোর্টালে। সাতক্ষীরা জেলার নাগরিকেরা অনলাইনে ফরম পূরণ, ফি পরিশোধ ও আবেদনের অবস্থা দেখতে পারবেন। খুলনা বিভাগের তথ্যও এখানে পাওয়া যায়।',
                'phone' => '16122',
                'website' => 'https://mutation.land.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Land Development Tax (Bhumi Unnayan Kor)',
                'title_bn' => 'ভূমি উন্নয়ন কর (অনলাইন পরিশোধ)',
                'short_description' => 'Online land development tax payment portal with NID-based registration.',
                'description' => 'ভূমি উন্নয়ন কর অনলাইনে পরিশোধ করার সরকারি পোর্টাল। সাতক্ষীরা জেলার ভূমি মালিকেরা জাতীয় পরিচয়পত্র ও খতিয়ান তথ্য দিয়ে নিবন্ধন করে অনলাইনে ভূমি উন্নয়ন কর দিতে পারবেন। মোবাইল অ্যাপও রয়েছে।',
                'phone' => '16122',
                'website' => 'https://ldtax.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Land Record & Map (DLRMS)',
                'title_bn' => 'ভূমি রেকর্ড ও ম্যাপ (ডিএলআরএমএস)',
                'short_description' => 'View digital land records - RS, BS, SA khatians and mouza maps online.',
                'description' => 'ডিজিটাল পদ্ধতিতে ভূমি রেকর্ড ও মৌজা ম্যাপ দেখার সরকারি পোর্টাল। সাতক্ষীরা জেলার যেকোনো মৌজার খতিয়ান ও নকশা অনলাইনে দেখা যায়। আরএস, বিএস, এসএ খতিয়ানসহ সকল ধরনের রেকর্ড এখানে পাওয়া যায়।',
                'phone' => '16122',
                'website' => 'https://dlrms.land.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'National ID (NID) Services',
                'title_bn' => 'জাতীয় পরিচয়পত্র (NID) সেবা',
                'short_description' => 'NID registration, correction, duplicate copy download & verification portal.',
                'description' => 'বাংলাদেশ নির্বাচন কমিশনের জাতীয় পরিচয়পত্র (NID) সংক্রান্ত অনলাইন সেবা পোর্টাল। সাতক্ষীরা জেলার নাগরিকেরা এখান থেকে NID সংশোধন, ডুপ্লিকেট কপি ও নতুন নিবন্ধনের জন্য আবেদন করতে পারবেন। হেল্পলাইন নম্বর ১০৫।',
                'phone' => '105',
                'website' => 'https://services.nidw.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'e-Passport Application',
                'title_bn' => 'ই-পাসপোর্ট আবেদন',
                'short_description' => 'Online e-Passport application, re-issue and status tracking portal.',
                'description' => 'ই-পাসপোর্টের অনলাইন আবেদন ও পুনঃইস্যু পোর্টাল। সাতক্ষীরা জেলার নাগরিকেরা অনলাইনে আবেদন করে নিকটস্থ পাসপোর্ট অফিসে (খুলনা আঞ্চলিক পাসপোর্ট অফিস) বায়োমেট্রিক তথ্য দিতে পারবেন। জরুরি ও সাধারণ দুই ধরনের আবেদন করা যায়।',
                'phone' => '16171',
                'website' => 'https://epassport.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Birth & Death Registration (BDRIS)',
                'title_bn' => 'জন্ম ও মৃত্যু নিবন্ধন (বিডিআরআইএস)',
                'short_description' => 'Online birth & death certificate application, verification and download.',
                'description' => 'অনলাইনে জন্ম ও মৃত্যু নিবন্ধন আবেদন করার সরকারি পোর্টাল। সাতক্ষীরা জেলার নাগরিকেরা ইউনিয়ন পরিষদ, পৌরসভা বা সিটি কর্পোরেশনের মাধ্যমে জন্ম ও মৃত্যু সনদ পেতে পারবেন। অনলাইনে সনদ যাচাই ও ডাউনলোডও করা যায়।',
                'website' => 'https://bdris.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'e-TIN Registration (Income Tax)',
                'title_bn' => 'ই-টিআইএন নিবন্ধন (আয়কর)',
                'short_description' => 'Electronic TIN certificate registration - required for bank, land and business.',
                'description' => 'আয়কর রিটার্ন দাখিল ও করদাতা সনাক্তকরণ নম্বর (TIN) নিবন্ধনের পোর্টাল। সাতক্ষীরা জেলার করদাতারা অনলাইনে ই-টিআইএন সার্টিফিকেট নিতে পারবেন। ব্যাংক একাউন্ট খোলা, জমি রেজিস্ট্রেশন সহ বিভিন্ন কাজে ই-টিআইএন প্রয়োজন হয়।',
                'website' => 'https://secure.incometax.gov.bd/TINHome',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'e-Return (Online Income Tax Filing)',
                'title_bn' => 'ই-রিটার্ন (অনলাইন আয়কর দাখিল)',
                'short_description' => 'File income tax returns online from home - no need to visit tax office.',
                'description' => 'অনলাইনে আয়কর রিটার্ন দাখিল করার সরকারি পোর্টাল। সাতক্ষীরা জেলার করদাতারা ঘরে বসে অনলাইনে আয়কর রিটার্ন দাখিল, পেমেন্ট সার্টিফিকেট (PSR) যাচাই ও ই-ট্যাক্স সেবা পেতে পারবেন। হটলাইন ০৯৬৪৩৭১৭১৭১।',
                'phone' => '09643717171',
                'website' => 'https://etaxnbr.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Bangladesh Railway e-Ticket',
                'title_bn' => 'বাংলাদেশ রেলওয়ে ই-টিকিট',
                'short_description' => 'Book train tickets online - pay with bKash, Nagad, Rocket or card.',
                'description' => 'বাংলাদেশ রেলওয়ের অনলাইন টিকিট ক্রয়ের পোর্টাল। সাতক্ষীরা থেকে ঢাকা বা অন্যান্য গন্তব্যে ট্রেনের টিকিট অনলাইনে বুক করা যায়। বিকাশ, নগদ, রকেট, ভিসা/মাস্টারকার্ডে পেমেন্ট করা যায়। রেল সেবা অ্যাপও আছে।',
                'phone' => '131',
                'website' => 'https://eticket.railway.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'BPDB Electricity Bill Payment',
                'title_bn' => 'বিদ্যুৎ বিল অনলাইনে পরিশোধ (বিউবো)',
                'short_description' => 'Check and pay BPDB electricity bills online using meter number.',
                'description' => 'বাংলাদেশ বিদ্যুৎ উন্নয়ন বোর্ডের অনলাইনে বিদ্যুৎ বিল দেখা ও পরিশোধ করার পোর্টাল। সাতক্ষীরা জেলার বিদ্যুৎ গ্রাহকেরা তাদের মিটার নম্বর দিয়ে বিল দেখতে ও অনলাইনে পরিশোধ করতে পারবেন। প্রিপেইড মিটার সেবাও আছে।',
                'phone' => '16999',
                'website' => 'https://bpdb.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'BMET - Overseas Employment Registration',
                'title_bn' => 'বিএমইটি - বিদেশ গমন নিবন্ধন',
                'short_description' => 'Overseas worker registration, visa verification & training for Satkhira residents.',
                'description' => 'প্রবাসী শ্রমিকদের নিবন্ধন, প্রশিক্ষণ ও কল্যাণ সংক্রান্ত সরকারি পোর্টাল। সাতক্ষীরা জেলা থেকে বিদেশে কাজে যেতে ইচ্ছুক ব্যক্তিরা এখান থেকে নিবন্ধন, ভিসা যাচাই, ও প্রশিক্ষণ সংক্রান্ত তথ্য পাবেন। প্রবাসবন্ধু কল সেন্টার ১৬১৩৫।',
                'phone' => '16135',
                'website' => 'https://bmet.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Probashi Kalyan (Expatriate Welfare)',
                'title_bn' => 'প্রবাসী কল্যাণ ও বৈদেশিক কর্মসংস্থান',
                'short_description' => 'Visa verification, CIP application, complaint & pre-departure info for workers.',
                'description' => 'প্রবাসী কল্যাণ ও বৈদেশিক কর্মসংস্থান মন্ত্রণালয়ের সরকারি পোর্টাল। সাতক্ষীরা জেলার প্রবাসীরা ও বিদেশগামী শ্রমিকেরা ভিসা যাচাই, সিআইপি আবেদন, অভিযোগ দাখিল ও বিদেশ যাওয়ার পূর্বের প্রস্তুতি সংক্রান্ত তথ্য পাবেন।',
                'phone' => '16135',
                'website' => 'https://probashi.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Department of Fisheries',
                'title_bn' => 'মৎস্য অধিদপ্তর',
                'short_description' => 'Fish farming guidance, training manuals & statistics for Satkhira shrimp farmers.',
                'description' => 'মৎস্য অধিদপ্তরের সরকারি পোর্টাল। সাতক্ষীরা জেলার মৎস্যচাষীরা চিংড়ি ও মাছ চাষ সংক্রান্ত পরামর্শ, প্রশিক্ষণ ম্যানুয়াল, মৎস্য পরিসংখ্যান ও বিভিন্ন প্রকাশনা এখান থেকে পাবেন। সাতক্ষীরা চিংড়ি শিল্পের জন্য এটি অত্যন্ত গুরুত্বপূর্ণ।',
                'website' => 'https://fisheries.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Agricultural Extension (DAE)',
                'title_bn' => 'কৃষি সম্প্রসারণ অধিদপ্তর',
                'short_description' => 'Crop production, pesticide, fertilizer & agricultural weather info for farmers.',
                'description' => 'কৃষি সম্প্রসারণ অধিদপ্তরের সরকারি পোর্টাল। সাতক্ষীরা জেলার কৃষকেরা ফসল উৎপাদন, বালাইনাশক, সার ব্যবস্থাপনা, কৃষি আবহাওয়া তথ্য ও প্রশিক্ষণের জন্য এই পোর্টাল ব্যবহার করতে পারবেন। মাঠ পর্যায়ের কার্যালয়ের তথ্যও পাওয়া যায়।',
                'website' => 'https://dae.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Universal Pension System',
                'title_bn' => 'সর্বজনীন পেনশন ব্যবস্থা',
                'short_description' => 'Register for Probas, Progoti, Surokkha or Somota pension schemes online.',
                'description' => 'সর্বজনীন পেনশন স্কিমে নিবন্ধন ও পরিচালনার সরকারি পোর্টাল। সাতক্ষীরা জেলার নাগরিকেরা প্রবাস, প্রগতি, সুরক্ষা ও সমতা - এই ৪টি স্কিমে নিবন্ধন করতে পারবেন। ইউনিয়ন ডিজিটাল সেন্টার থেকেও নিবন্ধন করা যায়। হেল্পলাইন ১৬১৩১।',
                'phone' => '16131',
                'website' => 'https://upension.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Food Directorate (Krishok App)',
                'title_bn' => 'খাদ্য অধিদপ্তর (কৃষক অ্যাপ)',
                'short_description' => 'Food grain licensing, ration card & Krishok app for paddy selling info.',
                'description' => 'খাদ্য মন্ত্রণালয়ের অধীন খাদ্য অধিদপ্তরের সরকারি পোর্টাল। সাতক্ষীরা জেলার কৃষকেরা কৃষক অ্যাপ ব্যবহার করে ধান বিক্রির তথ্য পাবেন। খাদ্যশস্যের লাইসেন্স আবেদন ও নবায়ন এবং খাদ্যবান্ধব কর্মসূচির তথ্যও এখানে পাওয়া যায়।',
                'website' => 'https://dgfood.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Grievance Redress System (GRS)',
                'title_bn' => 'অভিযোগ প্রতিকার ব্যবস্থা (জিআরএস)',
                'short_description' => 'Submit and track complaints against any government office online.',
                'description' => 'সরকারি সেবা সংক্রান্ত অভিযোগ অনলাইনে দাখিল ও অবস্থা পর্যবেক্ষণের পোর্টাল। সাতক্ষীরা জেলার নাগরিকেরা যেকোনো সরকারি অফিসের বিরুদ্ধে অভিযোগ দাখিল করতে পারবেন এবং অভিযোগের নিষ্পত্তি অগ্রগতি অনলাইনে দেখতে পারবেন।',
                'website' => 'https://www.grs.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'D-Nothi (Digital Filing)',
                'title_bn' => 'ডি-নথি (নাগরিক আবেদন)',
                'short_description' => 'Submit applications to any government office digitally through D-Nothi system.',
                'description' => 'সরকারের ডিজিটাল নথি ব্যবস্থাপনা পদ্ধতি। নাগরিকেরা অনলাইনে সরকারি অফিসে আবেদন দাখিল করতে পারবেন। সাতক্ষীরা জেলা প্রশাসনসহ সকল সরকারি অফিসে ডি-নথির মাধ্যমে নাগরিক আবেদন গ্রহণ করা হয়।',
                'website' => 'https://www.nothi.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Bangladesh National Portal',
                'title_bn' => 'বাংলাদেশ জাতীয় তথ্য বাতায়ন',
                'short_description' => 'Central portal connecting all government ministries, departments & district offices.',
                'description' => 'বাংলাদেশ সরকারের সকল মন্ত্রণালয়, বিভাগ, অধিদপ্তর ও জেলা-উপজেলা অফিসের কেন্দ্রীয় তথ্য পোর্টাল। সাতক্ষীরা জেলার সকল সরকারি অফিসের তথ্য, নোটিশ, সেবার তালিকা ও ই-ডিরেক্টরি এখানে পাওয়া যায়।',
                'website' => 'https://bangladesh.gov.bd',
            ],
            [
                'category_id' => 15,
                'upazila_id' => 1,
                'title' => 'Government Helpline 333',
                'title_bn' => 'সরকারি তথ্য ও সেবা হেল্পলাইন ৩৩৩',
                'short_description' => 'Call 333 for any government service info, complaints & grievance redressal 24/7.',
                'description' => 'বাংলাদেশ সরকারের জাতীয় তথ্য ও সেবা হেল্পলাইন। মোবাইল থেকে ৩৩৩ এবং ল্যান্ডফোন/বিদেশ থেকে ০৯৬৬৬৭৮৯৩৩৩ নম্বরে কল করে সরকারি সেবা সংক্রান্ত তথ্য ও অভিযোগ জানাতে পারবেন। ২৪/৭ সেবা পাওয়া যায়।',
                'phone' => '333',
                'website' => 'https://333.gov.bd',
            ],
        ];

        $inserted = 0;
        $skipped = 0;

        foreach ($listings as $data) {
            $exists = DB::table('listings')
                ->where('title', $data['title'])
                ->orWhere('title_bn', $data['title_bn'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            $baseSlug = Str::slug($data['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (DB::table('listings')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            DB::table('listings')->insert([
                'user_id' => 4,
                'category_id' => $data['category_id'],
                'upazila_id' => $data['upazila_id'],
                'title' => $data['title'],
                'title_bn' => $data['title_bn'],
                'slug' => $slug,
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'] ?? null,
                'address' => $data['address'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'website' => $data['website'] ?? null,
                'facebook' => $data['facebook'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'map_embed' => $data['map_embed'] ?? null,
                'opening_hours' => null,
                'status' => 'pending',
                'is_featured' => false,
                'views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $inserted++;
        }

        $this->command->info("Inserted: $inserted, Skipped (duplicates): $skipped");
    }
}
