<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SatkhiraProperDataSeeder extends Seeder
{
    public function run(): void
    {
        // Categories: 1=Home Tutor, 2=To-Let, 3=Restaurant, 4=Hospital, 5=School, 6=College,
        // 7=Pharmacy, 8=Doctor, 9=Government Office, 10=Bank, 11=Joruri Sheba,
        // 12=Samajik Songothon, 13=Tourist Spots, 14=Shopping Center, 15=Online Services,
        // 16=Residential Hotel, 17=Business Establishments, 18=Digital Services,
        // 20=Transport, 21=Job Circular, 22=Events, 23=Newspaper

        // Upazilas: 1=Satkhira Sadar, 2=Kaliganj, 3=Shyamnagar, 4=Assasuni, 5=Debhata, 6=Kalaroa, 7=Tala

        $listings = [

            // ===== HOSPITALS (category_id=4) =====
            [
                'category_id' => 4,
                'upazila_id' => 1,
                'title' => 'Satkhira Sadar Hospital (250 Bed)',
                'title_bn' => 'সাতক্ষীরা সদর হাসপাতাল (২৫০ শয্যা)',
                'short_description' => 'Government general hospital in Satkhira town with 250 beds, emergency, and outdoor services.',
                'description' => 'সাতক্ষীরা সদর হাসপাতাল সাতক্ষীরা জেলার প্রধান সরকারি হাসপাতাল। এটি ২৫০ শয্যা বিশিষ্ট এবং জরুরি বিভাগ, আউটডোর, অপারেশন থিয়েটার, প্যাথলজি, রেডিওলজি সহ বিভিন্ন সেবা প্রদান করে। ২৪ ঘণ্টা জরুরি সেবা চালু আছে।',
                'address' => 'R760, Town, Satkhira Sadar, Satkhira 9400',
                'phone' => '01765-874812',
                'latitude' => '22.7185',
                'longitude' => '89.0715',
                'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3666.5!2d89.0715!3d22.7185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDQzJzA2LjYiTiA4OcKwMDQnMTcuNCJF!5e0!3m2!1sen!2sbd!4v1" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা', 'sunday' => '২৪ ঘণ্টা', 'monday' => '২৪ ঘণ্টা', 'tuesday' => '২৪ ঘণ্টা', 'wednesday' => '২৪ ঘণ্টা', 'thursday' => '২৪ ঘণ্টা', 'friday' => '২৪ ঘণ্টা']),
            ],
            [
                'category_id' => 4,
                'upazila_id' => 1,
                'title' => 'Satkhira Medical College & Hospital',
                'title_bn' => 'সাতক্ষীরা মেডিকেল কলেজ ও হাসপাতাল',
                'short_description' => 'Government medical college hospital with specialized departments and teaching facilities.',
                'description' => 'সাতক্ষীরা মেডিকেল কলেজ ও হাসপাতাল সাতক্ষীরা জেলার একমাত্র সরকারি মেডিকেল কলেজ। এখানে এমবিবিএস কোর্স পরিচালিত হয় এবং হাসপাতালে বিভিন্ন বিশেষজ্ঞ বিভাগে চিকিৎসা সেবা প্রদান করা হয়।',
                'address' => 'Satkhira Medical College Road, Satkhira Sadar, Satkhira',
                'phone' => '0471-62001',
                'latitude' => '22.7160',
                'longitude' => '89.0680',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা', 'sunday' => '২৪ ঘণ্টা', 'monday' => '২৪ ঘণ্টা', 'tuesday' => '২৪ ঘণ্টা', 'wednesday' => '২৪ ঘণ্টা', 'thursday' => '২৪ ঘণ্টা', 'friday' => '২৪ ঘণ্টা']),
            ],
            [
                'category_id' => 4,
                'upazila_id' => 1,
                'title' => 'CB Hospital Limited, Satkhira',
                'title_bn' => 'সিবি হাসপাতাল লিমিটেড, সাতক্ষীরা',
                'short_description' => 'Private hospital with modern diagnostic and surgical facilities in Satkhira town.',
                'description' => 'সিবি হাসপাতাল লিমিটেড সাতক্ষীরা শহরের একটি নামকরা বেসরকারি হাসপাতাল। এখানে আধুনিক ডায়াগনস্টিক, সার্জিক্যাল ও চিকিৎসা সেবা পাওয়া যায়।',
                'address' => 'Satkhira - Jessore Hwy, Satkhira',
                'phone' => '0471-63456',
                'latitude' => '22.7210',
                'longitude' => '89.0750',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা', 'sunday' => '২৪ ঘণ্টা', 'monday' => '২৪ ঘণ্টা', 'tuesday' => '২৪ ঘণ্টা', 'wednesday' => '২৪ ঘণ্টা', 'thursday' => '২৪ ঘণ্টা', 'friday' => '২৪ ঘণ্টা']),
            ],
            [
                'category_id' => 4,
                'upazila_id' => 1,
                'title' => 'Islami Bank Community Hospital, Satkhira',
                'title_bn' => 'ইসলামী ব্যাংক কমিউনিটি হাসপাতাল, সাতক্ষীরা',
                'short_description' => 'Affordable healthcare facility managed by Islami Bank with diagnostic and treatment services.',
                'description' => 'ইসলামী ব্যাংক কমিউনিটি হাসপাতাল সাতক্ষীরা লিমিটেড স্বল্প মূল্যে মানসম্মত চিকিৎসা সেবা প্রদান করে। এখানে আউটডোর, ইনডোর, ডায়াগনস্টিক, অপারেশন ও জরুরি সেবা পাওয়া যায়।',
                'address' => 'Satkhira Town, Satkhira Sadar, Satkhira',
                'phone' => '0471-62456',
                'website' => 'https://ibchbd.com',
                'latitude' => '22.7195',
                'longitude' => '89.0735',
                'opening_hours' => json_encode(['saturday' => '৮:০০ AM - ১০:০০ PM', 'sunday' => '৮:০০ AM - ১০:০০ PM', 'monday' => '৮:০০ AM - ১০:০০ PM', 'tuesday' => '৮:০০ AM - ১০:০০ PM', 'wednesday' => '৮:০০ AM - ১০:০০ PM', 'thursday' => '৮:০০ AM - ১০:০০ PM', 'friday' => '৮:০০ AM - ১০:০০ PM']),
            ],
            [
                'category_id' => 4,
                'upazila_id' => 2,
                'title' => 'Upazila Health Complex, Kaliganj',
                'title_bn' => 'উপজেলা স্বাস্থ্য কমপ্লেক্স, কালীগঞ্জ',
                'short_description' => 'Government health complex serving Kaliganj upazila with 50-bed inpatient and emergency services.',
                'description' => 'কালীগঞ্জ উপজেলা স্বাস্থ্য কমপ্লেক্স সরকারি স্বাস্থ্যসেবা কেন্দ্র। এখানে আউটডোর, ইনডোর (৫০ শয্যা), জরুরি বিভাগ, প্রসূতি সেবা ও টিকাদান কার্যক্রম পরিচালিত হয়।',
                'address' => 'Kaliganj Upazila, Satkhira',
                'phone' => '04724-75001',
                'latitude' => '22.4614',
                'longitude' => '89.0289',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা (জরুরি)', 'sunday' => '২৪ ঘণ্টা (জরুরি)', 'monday' => '২৪ ঘণ্টা (জরুরি)', 'tuesday' => '২৪ ঘণ্টা (জরুরি)', 'wednesday' => '২৪ ঘণ্টা (জরুরি)', 'thursday' => '২৪ ঘণ্টা (জরুরি)', 'friday' => '২৪ ঘণ্টা (জরুরি)']),
            ],
            [
                'category_id' => 4,
                'upazila_id' => 3,
                'title' => 'Upazila Health Complex, Shyamnagar',
                'title_bn' => 'উপজেলা স্বাস্থ্য কমপ্লেক্স, শ্যামনগর',
                'short_description' => 'Government health complex serving Shyamnagar upazila near the Sundarbans.',
                'description' => 'শ্যামনগর উপজেলা স্বাস্থ্য কমপ্লেক্স সরকারি স্বাস্থ্যসেবা কেন্দ্র। সুন্দরবন সংলগ্ন এলাকার জনগণকে স্বাস্থ্যসেবা প্রদান করে। জরুরি বিভাগ, আউটডোর ও ইনডোর সেবা চালু আছে।',
                'address' => 'Shyamnagar Upazila, Satkhira',
                'phone' => '04725-75001',
                'latitude' => '22.3350',
                'longitude' => '89.1016',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা (জরুরি)', 'sunday' => '২৪ ঘণ্টা (জরুরি)', 'monday' => '২৪ ঘণ্টা (জরুরি)', 'tuesday' => '২৪ ঘণ্টা (জরুরি)', 'wednesday' => '২৪ ঘণ্টা (জরুরি)', 'thursday' => '২৪ ঘণ্টা (জরুরি)', 'friday' => '২৪ ঘণ্টা (জরুরি)']),
            ],
            [
                'category_id' => 4,
                'upazila_id' => 6,
                'title' => 'Upazila Health Complex, Kalaroa',
                'title_bn' => 'উপজেলা স্বাস্থ্য কমপ্লেক্স, কলারোয়া',
                'short_description' => 'Government health complex serving Kalaroa upazila with emergency and outpatient services.',
                'description' => 'কলারোয়া উপজেলা স্বাস্থ্য কমপ্লেক্স সরকারি স্বাস্থ্যসেবা কেন্দ্র। এখানে আউটডোর, ইনডোর, জরুরি বিভাগ ও প্রসূতি সেবা পাওয়া যায়।',
                'address' => 'Kalaroa Upazila, Satkhira',
                'phone' => '04721-75001',
                'latitude' => '22.8785',
                'longitude' => '89.0458',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা (জরুরি)', 'sunday' => '২৪ ঘণ্টা (জরুরি)', 'monday' => '২৪ ঘণ্টা (জরুরি)', 'tuesday' => '২৪ ঘণ্টা (জরুরি)', 'wednesday' => '২৪ ঘণ্টা (জরুরি)', 'thursday' => '২৪ ঘণ্টা (জরুরি)', 'friday' => '২৪ ঘণ্টা (জরুরি)']),
            ],

            // ===== SCHOOLS (category_id=5) =====
            [
                'category_id' => 5,
                'upazila_id' => 1,
                'title' => 'Satkhira Government High School',
                'title_bn' => 'সাতক্ষীরা সরকারি উচ্চ বিদ্যালয়',
                'short_description' => 'One of the oldest and most prestigious government high schools in Satkhira district.',
                'description' => 'সাতক্ষীরা সরকারি উচ্চ বিদ্যালয় সাতক্ষীরা জেলার অন্যতম প্রাচীন ও ঐতিহ্যবাহী শিক্ষা প্রতিষ্ঠান। এখানে ৬ষ্ঠ থেকে ১০ম শ্রেণি পর্যন্ত শিক্ষা কার্যক্রম পরিচালিত হয়। স্কুলটি এসএসসি পরীক্ষায় ধারাবাহিকভাবে ভালো ফলাফল করে থাকে।',
                'address' => 'Satkhira Town, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62050',
                'website' => 'https://satkhiragovthighschool.edu.bd',
                'latitude' => '22.7172',
                'longitude' => '89.0705',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '১০:০০ AM - ৪:০০ PM', 'monday' => '১০:০০ AM - ৪:০০ PM', 'tuesday' => '১০:০০ AM - ৪:০০ PM', 'wednesday' => '১০:০০ AM - ৪:০০ PM', 'thursday' => '১০:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 5,
                'upazila_id' => 1,
                'title' => 'Satkhira Government Girls High School',
                'title_bn' => 'সাতক্ষীরা সরকারি বালিকা উচ্চ বিদ্যালয়',
                'short_description' => 'Government girls high school with excellent academic track record in Satkhira town.',
                'description' => 'সাতক্ষীরা সরকারি বালিকা উচ্চ বিদ্যালয় মেয়েদের জন্য সাতক্ষীরার অন্যতম সেরা শিক্ষা প্রতিষ্ঠান। এখানে ৬ষ্ঠ থেকে ১০ম শ্রেণি পর্যন্ত পড়ানো হয়।',
                'address' => 'Satkhira Town, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62055',
                'latitude' => '22.7168',
                'longitude' => '89.0710',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '১০:০০ AM - ৪:০০ PM', 'monday' => '১০:০০ AM - ৪:০০ PM', 'tuesday' => '১০:০০ AM - ৪:০০ PM', 'wednesday' => '১০:০০ AM - ৪:০০ PM', 'thursday' => '১০:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],

            // ===== COLLEGES (category_id=6) =====
            [
                'category_id' => 6,
                'upazila_id' => 1,
                'title' => 'Satkhira Government College',
                'title_bn' => 'সাতক্ষীরা সরকারি কলেজ',
                'short_description' => 'The premier government degree college in Satkhira district with HSC and Honours programs.',
                'description' => 'সাতক্ষীরা সরকারি কলেজ জেলার প্রধান সরকারি কলেজ। এখানে একাদশ-দ্বাদশ শ্রেণি, স্নাতক (সম্মান) ও স্নাতকোত্তর (মাস্টার্স) কোর্স পরিচালিত হয়। জাতীয় বিশ্ববিদ্যালয়ের অধিভুক্ত।',
                'address' => 'College Road, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62100',
                'website' => 'https://satkhiragovtcollege.edu.bd',
                'latitude' => '22.7150',
                'longitude' => '89.0690',
                'opening_hours' => json_encode(['saturday' => '৯:০০ AM - ৪:০০ PM', 'sunday' => '৯:০০ AM - ৪:০০ PM', 'monday' => '৯:০০ AM - ৪:০০ PM', 'tuesday' => '৯:০০ AM - ৪:০০ PM', 'wednesday' => '৯:০০ AM - ৪:০০ PM', 'thursday' => '৯:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 6,
                'upazila_id' => 1,
                'title' => 'Satkhira Government Mahila College',
                'title_bn' => 'সাতক্ষীরা সরকারি মহিলা কলেজ',
                'short_description' => 'Government women\'s college with degree and masters programs in Satkhira.',
                'description' => 'সাতক্ষীরা সরকারি মহিলা কলেজ মেয়েদের উচ্চ শিক্ষার জন্য সাতক্ষীরার একটি গুরুত্বপূর্ণ প্রতিষ্ঠান। এখানে এইচএসসি, স্নাতক ও মাস্টার্স কোর্স চালু আছে।',
                'address' => 'Satkhira Town, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62200',
                'latitude' => '22.7175',
                'longitude' => '89.0698',
                'opening_hours' => json_encode(['saturday' => '৯:০০ AM - ৪:০০ PM', 'sunday' => '৯:০০ AM - ৪:০০ PM', 'monday' => '৯:০০ AM - ৪:০০ PM', 'tuesday' => '৯:০০ AM - ৪:০০ PM', 'wednesday' => '৯:০০ AM - ৪:০০ PM', 'thursday' => '৯:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 6,
                'upazila_id' => 1,
                'title' => 'Satkhira City College',
                'title_bn' => 'সাতক্ষীরা সিটি কলেজ',
                'short_description' => 'One of the well-known private colleges in Satkhira for HSC and Degree courses.',
                'description' => 'সাতক্ষীরা সিটি কলেজ সাতক্ষীরা শহরের একটি পরিচিত বেসরকারি কলেজ। এখানে উচ্চ মাধ্যমিক ও ডিগ্রি কোর্স পরিচালিত হয়।',
                'address' => 'Satkhira Town, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62300',
                'latitude' => '22.7195',
                'longitude' => '89.0720',
                'opening_hours' => json_encode(['saturday' => '৯:০০ AM - ৪:০০ PM', 'sunday' => '৯:০০ AM - ৪:০০ PM', 'monday' => '৯:০০ AM - ৪:০০ PM', 'tuesday' => '৯:০০ AM - ৪:০০ PM', 'wednesday' => '৯:০০ AM - ৪:০০ PM', 'thursday' => '৯:০০ AM - ৪:০০ PM', 'friday' => '৯:০০ AM - ৪:০০ PM']),
            ],
            [
                'category_id' => 6,
                'upazila_id' => 6,
                'title' => 'Kalaroa Government College',
                'title_bn' => 'কলারোয়া সরকারি কলেজ',
                'short_description' => 'Government college in Kalaroa upazila with HSC and Honours programs.',
                'description' => 'কলারোয়া সরকারি কলেজ কলারোয়া উপজেলার প্রধান সরকারি কলেজ। এখানে এইচএসসি ও স্নাতক (সম্মান) কোর্স পরিচালিত হয়। জাতীয় বিশ্ববিদ্যালয়ের অধিভুক্ত।',
                'address' => 'Kalaroa, Satkhira',
                'phone' => '04721-75100',
                'latitude' => '22.8750',
                'longitude' => '89.0420',
                'opening_hours' => json_encode(['saturday' => '৯:০০ AM - ৪:০০ PM', 'sunday' => '৯:০০ AM - ৪:০০ PM', 'monday' => '৯:০০ AM - ৪:০০ PM', 'tuesday' => '৯:০০ AM - ৪:০০ PM', 'wednesday' => '৯:০০ AM - ৪:০০ PM', 'thursday' => '৯:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 6,
                'upazila_id' => 1,
                'title' => 'Satkhira Government Polytechnic Institute',
                'title_bn' => 'সাতক্ষীরা সরকারি পলিটেকনিক ইনস্টিটিউট',
                'short_description' => 'Government polytechnic providing diploma engineering education in Satkhira.',
                'description' => 'সাতক্ষীরা সরকারি পলিটেকনিক ইনস্টিটিউট কারিগরি শিক্ষা বোর্ডের অধীনে ডিপ্লোমা ইঞ্জিনিয়ারিং কোর্স পরিচালনা করে। বিভিন্ন টেকনোলজিতে ৪ বছর মেয়াদী ডিপ্লোমা কোর্স চালু আছে।',
                'address' => 'Polytechnic Road, Satkhira Sadar, Satkhira',
                'phone' => '0471-62400',
                'latitude' => '22.7130',
                'longitude' => '89.0670',
                'opening_hours' => json_encode(['saturday' => '৯:০০ AM - ৪:০০ PM', 'sunday' => '৯:০০ AM - ৪:০০ PM', 'monday' => '৯:০০ AM - ৪:০০ PM', 'tuesday' => '৯:০০ AM - ৪:০০ PM', 'wednesday' => '৯:০০ AM - ৪:০০ PM', 'thursday' => '৯:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],

            // ===== GOVERNMENT OFFICES (category_id=9) =====
            [
                'category_id' => 9,
                'upazila_id' => 1,
                'title' => 'Deputy Commissioner (DC) Office, Satkhira',
                'title_bn' => 'জেলা প্রশাসকের কার্যালয়, সাতক্ষীরা',
                'short_description' => 'Office of the Deputy Commissioner of Satkhira district — the main administrative hub.',
                'description' => 'সাতক্ষীরা জেলা প্রশাসকের কার্যালয় জেলার প্রধান প্রশাসনিক কেন্দ্র। জেলা পর্যায়ের সকল সরকারি কার্যক্রম এখান থেকে সমন্বয় করা হয়। জমি রেজিস্ট্রেশন, সনদপত্র, মামলা নিষ্পত্তি সহ বিভিন্ন সেবা প্রদান করা হয়।',
                'address' => 'DC Office, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62001',
                'website' => 'https://satkhira.gov.bd',
                'facebook' => 'https://www.facebook.com/profile.php?id=100012369548896',
                'latitude' => '22.7192',
                'longitude' => '89.0745',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '৯:০০ AM - ৫:০০ PM', 'monday' => '৯:০০ AM - ৫:০০ PM', 'tuesday' => '৯:০০ AM - ৫:০০ PM', 'wednesday' => '৯:০০ AM - ৫:০০ PM', 'thursday' => '৯:০০ AM - ৫:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 9,
                'upazila_id' => 1,
                'title' => 'Superintendent of Police (SP) Office, Satkhira',
                'title_bn' => 'পুলিশ সুপারের কার্যালয়, সাতক্ষীরা',
                'short_description' => 'District police headquarters managing law and order across Satkhira district.',
                'description' => 'সাতক্ষীরা জেলা পুলিশ সুপারের কার্যালয় জেলার আইনশৃঙ্খলা রক্ষার প্রধান কেন্দ্র। সকল থানা ও ফাঁড়ির কার্যক্রম এখান থেকে তদারকি করা হয়।',
                'address' => 'SP Office, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62500',
                'latitude' => '22.7180',
                'longitude' => '89.0730',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '৯:০০ AM - ৫:০০ PM', 'monday' => '৯:০০ AM - ৫:০০ PM', 'tuesday' => '৯:০০ AM - ৫:০০ PM', 'wednesday' => '৯:০০ AM - ৫:০০ PM', 'thursday' => '৯:০০ AM - ৫:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 9,
                'upazila_id' => 1,
                'title' => 'District Judge Court, Satkhira',
                'title_bn' => 'জেলা জজ আদালত, সাতক্ষীরা',
                'short_description' => 'District court complex handling civil and criminal cases in Satkhira.',
                'description' => 'সাতক্ষীরা জেলা জজ আদালত জেলার প্রধান বিচারিক কেন্দ্র। এখানে দেওয়ানি ও ফৌজদারি মামলা পরিচালিত হয়।',
                'address' => 'Court Road, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62600',
                'latitude' => '22.7188',
                'longitude' => '89.0720',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '৯:০০ AM - ৫:০০ PM', 'monday' => '৯:০০ AM - ৫:০০ PM', 'tuesday' => '৯:০০ AM - ৫:০০ PM', 'wednesday' => '৯:০০ AM - ৫:০০ PM', 'thursday' => '৯:০০ AM - ৫:০০ PM', 'friday' => 'বন্ধ']),
            ],

            // ===== BANKS (category_id=10) =====
            [
                'category_id' => 10,
                'upazila_id' => 1,
                'title' => 'Sonali Bank Limited, Satkhira Branch',
                'title_bn' => 'সোনালী ব্যাংক লিমিটেড, সাতক্ষীরা শাখা',
                'short_description' => 'The largest state-owned commercial bank branch in Satkhira town.',
                'description' => 'সোনালী ব্যাংক লিমিটেড সাতক্ষীরা শাখা বাংলাদেশের বৃহত্তম রাষ্ট্রায়ত্ত ব্যাংকের স্থানীয় শাখা। সঞ্চয়, ঋণ, রেমিট্যান্স, পে-অর্ডার সহ সকল ব্যাংকিং সেবা পাওয়া যায়।',
                'address' => 'Main Road, Satkhira Town, Satkhira 9400',
                'phone' => '0471-62700',
                'website' => 'https://www.sonalibank.com.bd',
                'latitude' => '22.7200',
                'longitude' => '89.0740',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '১০:০০ AM - ৪:০০ PM', 'monday' => '১০:০০ AM - ৪:০০ PM', 'tuesday' => '১০:০০ AM - ৪:০০ PM', 'wednesday' => '১০:০০ AM - ৪:০০ PM', 'thursday' => '১০:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 10,
                'upazila_id' => 1,
                'title' => 'Janata Bank Limited, Satkhira Branch',
                'title_bn' => 'জনতা ব্যাংক লিমিটেড, সাতক্ষীরা শাখা',
                'short_description' => 'Major state-owned bank branch providing comprehensive banking services in Satkhira.',
                'description' => 'জনতা ব্যাংক লিমিটেড সাতক্ষীরা শাখা রাষ্ট্রায়ত্ত ব্যাংকের একটি গুরুত্বপূর্ণ শাখা। সঞ্চয়, ঋণ, বৈদেশিক মুদ্রা ও ডিজিটাল ব্যাংকিং সেবা পাওয়া যায়।',
                'address' => 'Satkhira Town, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62800',
                'website' => 'https://www.janatabank-bd.com',
                'latitude' => '22.7198',
                'longitude' => '89.0742',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '১০:০০ AM - ৪:০০ PM', 'monday' => '১০:০০ AM - ৪:০০ PM', 'tuesday' => '১০:০০ AM - ৪:০০ PM', 'wednesday' => '১০:০০ AM - ৪:০০ PM', 'thursday' => '১০:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],
            [
                'category_id' => 10,
                'upazila_id' => 1,
                'title' => 'Islami Bank Bangladesh Limited, Satkhira Branch',
                'title_bn' => 'ইসলামী ব্যাংক বাংলাদেশ লিমিটেড, সাতক্ষীরা শাখা',
                'short_description' => 'Largest private Islamic bank branch in Satkhira with Shariah-compliant banking.',
                'description' => 'ইসলামী ব্যাংক বাংলাদেশ লিমিটেড সাতক্ষীরা শাখা শরিয়াহ ভিত্তিক ব্যাংকিং সেবা প্রদান করে। সঞ্চয়, বিনিয়োগ, রেমিট্যান্স ও ডিজিটাল ব্যাংকিং সেবা চালু আছে।',
                'address' => 'Satkhira Town, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-63100',
                'website' => 'https://www.islamibankbd.com',
                'latitude' => '22.7190',
                'longitude' => '89.0738',
                'opening_hours' => json_encode(['saturday' => 'বন্ধ', 'sunday' => '১০:০০ AM - ৪:০০ PM', 'monday' => '১০:০০ AM - ৪:০০ PM', 'tuesday' => '১০:০০ AM - ৪:০০ PM', 'wednesday' => '১০:০০ AM - ৪:০০ PM', 'thursday' => '১০:০০ AM - ৪:০০ PM', 'friday' => 'বন্ধ']),
            ],

            // ===== TOURIST SPOTS (category_id=13) =====
            [
                'category_id' => 13,
                'upazila_id' => 3,
                'title' => 'Sundarbans (Satkhira Part)',
                'title_bn' => 'সুন্দরবন (সাতক্ষীরা অংশ)',
                'short_description' => 'UNESCO World Heritage Site — the largest mangrove forest in the world, accessible from Shyamnagar.',
                'description' => 'সুন্দরবন বিশ্বের বৃহত্তম ম্যানগ্রোভ বন এবং ইউনেস্কো বিশ্ব ঐতিহ্যবাহী স্থান। সাতক্ষীরার শ্যামনগর উপজেলা থেকে সুন্দরবনে প্রবেশ করা যায়। রয়েল বেঙ্গল টাইগার, চিত্রা হরিণ, কুমির, বিভিন্ন প্রজাতির পাখি ও জলজ প্রাণীর আবাসস্থল। মুন্সিগঞ্জ ও কালাবগী থেকে নৌকায় যাওয়া যায়।',
                'address' => 'Shyamnagar, Satkhira (Entry: Munshiganj/Kalabogi)',
                'phone' => '04725-75001',
                'latitude' => '22.0000',
                'longitude' => '89.2000',
                'opening_hours' => json_encode(['note' => 'বন বিভাগের অনুমতি সাপেক্ষে']),
            ],
            [
                'category_id' => 13,
                'upazila_id' => 1,
                'title' => 'DC Eco Park (Bakale), Satkhira',
                'title_bn' => 'ডিসি ইকো পার্ক (বাকালে), সাতক্ষীরা',
                'short_description' => 'A beautiful eco park developed by the district administration for family recreation.',
                'description' => 'ডিসি ইকো পার্ক সাতক্ষীরা সদর উপজেলার বাকালে অবস্থিত একটি মনোরম বিনোদন কেন্দ্র। জেলা প্রশাসনের উদ্যোগে নির্মিত এই পার্কে হাঁটার পথ, বাগান, লেক ও বসার ব্যবস্থা রয়েছে। পারিবারিক বিনোদনের জন্য আদর্শ জায়গা।',
                'address' => 'Bakale, Satkhira Sadar, Satkhira',
                'phone' => '0471-62001',
                'latitude' => '22.7250',
                'longitude' => '89.0800',
                'opening_hours' => json_encode(['saturday' => '৬:০০ AM - ৬:০০ PM', 'sunday' => '৬:০০ AM - ৬:০০ PM', 'monday' => '৬:০০ AM - ৬:০০ PM', 'tuesday' => '৬:০০ AM - ৬:০০ PM', 'wednesday' => '৬:০০ AM - ৬:০০ PM', 'thursday' => '৬:০০ AM - ৬:০০ PM', 'friday' => '৬:০০ AM - ৬:০০ PM']),
            ],
            [
                'category_id' => 13,
                'upazila_id' => 4,
                'title' => 'Sultanpur Shahi Mosque (500 years old)',
                'title_bn' => 'সুলতানপুর শাহী মসজিদ (৫০০ বছর পুরনো)',
                'short_description' => 'A 500-year-old historic mosque in Assasuni upazila, a significant archaeological site.',
                'description' => 'সুলতানপুর শাহী মসজিদ আশাশুনি উপজেলায় অবস্থিত প্রায় ৫০০ বছর পুরনো ঐতিহাসিক মসজিদ। সুলতানি আমলে নির্মিত এই মসজিদটি সাতক্ষীরার অন্যতম প্রত্নতাত্ত্বিক নিদর্শন।',
                'address' => 'Sultanpur, Assasuni, Satkhira',
                'latitude' => '22.5354',
                'longitude' => '89.1881',
                'opening_hours' => json_encode(['saturday' => 'সবসময় খোলা', 'sunday' => 'সবসময় খোলা', 'monday' => 'সবসময় খোলা', 'tuesday' => 'সবসময় খোলা', 'wednesday' => 'সবসময় খোলা', 'thursday' => 'সবসময় খোলা', 'friday' => 'সবসময় খোলা']),
            ],
            [
                'category_id' => 13,
                'upazila_id' => 5,
                'title' => 'Bhomra Land Port',
                'title_bn' => 'ভোমরা স্থলবন্দর',
                'short_description' => 'The second largest land port of Bangladesh on the India-Bangladesh border in Debhata.',
                'description' => 'ভোমরা স্থলবন্দর বাংলাদেশের দ্বিতীয় বৃহত্তম স্থলবন্দর। দেবহাটা উপজেলায় অবস্থিত এই বন্দরটি ১৯৯৬ সালে চালু হয়। ভারত-বাংলাদেশ বাণিজ্যের একটি গুরুত্বপূর্ণ কেন্দ্র।',
                'address' => 'Bhomra, Debhata, Satkhira',
                'phone' => '04723-75200',
                'latitude' => '22.8800',
                'longitude' => '88.9500',
                'opening_hours' => json_encode(['saturday' => '৮:০০ AM - ৫:০০ PM', 'sunday' => '৮:০০ AM - ৫:০০ PM', 'monday' => '৮:০০ AM - ৫:০০ PM', 'tuesday' => '৮:০০ AM - ৫:০০ PM', 'wednesday' => '৮:০০ AM - ৫:০০ PM', 'thursday' => '৮:০০ AM - ৫:০০ PM', 'friday' => '৮:০০ AM - ৫:০০ PM']),
            ],

            // ===== EMERGENCY SERVICES (category_id=11) =====
            [
                'category_id' => 11,
                'upazila_id' => 1,
                'title' => 'Fire Service & Civil Defence, Satkhira',
                'title_bn' => 'ফায়ার সার্ভিস ও সিভিল ডিফেন্স, সাতক্ষীরা',
                'short_description' => 'District fire station providing fire fighting and rescue services across Satkhira.',
                'description' => 'সাতক্ষীরা ফায়ার সার্ভিস ও সিভিল ডিফেন্স স্টেশন অগ্নিনির্বাপণ, উদ্ধার ও জরুরি সেবা প্রদান করে। ২৪ ঘণ্টা সেবা চালু। জাতীয় হটলাইন ১০২ নম্বরে কল করুন।',
                'address' => 'Fire Station Road, Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62999',
                'latitude' => '22.7175',
                'longitude' => '89.0725',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা', 'sunday' => '২৪ ঘণ্টা', 'monday' => '২৪ ঘণ্টা', 'tuesday' => '২৪ ঘণ্টা', 'wednesday' => '২৪ ঘণ্টা', 'thursday' => '২৪ ঘণ্টা', 'friday' => '২৪ ঘণ্টা', 'hotline' => '১০২']),
            ],
            [
                'category_id' => 11,
                'upazila_id' => 1,
                'title' => 'Satkhira Sadar Police Station',
                'title_bn' => 'সাতক্ষীরা সদর থানা',
                'short_description' => 'Main police station of Satkhira Sadar upazila for filing complaints and emergency assistance.',
                'description' => 'সাতক্ষীরা সদর থানা সদর উপজেলার প্রধান পুলিশ থানা। এখানে জিডি, মামলা দায়ের, অভিযোগ গ্রহণ ও আইনশৃঙ্খলা সংক্রান্ত সেবা পাওয়া যায়। জরুরি সেবার জন্য ৯৯৯ নম্বরে কল করুন।',
                'address' => 'Satkhira Sadar, Satkhira 9400',
                'phone' => '0471-62222',
                'latitude' => '22.7190',
                'longitude' => '89.0735',
                'opening_hours' => json_encode(['saturday' => '২৪ ঘণ্টা', 'sunday' => '২৪ ঘণ্টা', 'monday' => '২৪ ঘণ্টা', 'tuesday' => '২৪ ঘণ্টা', 'wednesday' => '২৪ ঘণ্টা', 'thursday' => '২৪ ঘণ্টা', 'friday' => '২৪ ঘণ্টা', 'hotline' => '৯৯৯']),
            ],
        ];

        $inserted = 0;
        $skipped = 0;

        foreach ($listings as $data) {
            // Skip if already exists (by title)
            $exists = DB::table('listings')
                ->where('title', $data['title'])
                ->orWhere('title_bn', $data['title_bn'])
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Generate unique slug
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
                'opening_hours' => $data['opening_hours'] ?? null,
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
