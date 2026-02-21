<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>তথ্য বাতিল</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1a3c34 0%, #28a745 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">
                                <span style="color: #ffc107;">🌿</span> Explore Satkhira
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 14px;">
                                সাতক্ষীরা জেলার সকল তথ্য এক প্ল্যাটফর্মে
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="width: 80px; height: 80px; background-color: #f8d7da; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <span style="font-size: 40px;">❌</span>
                                </div>
                                <h2 style="color: #dc3545; margin: 0; font-size: 24px;">আপনার তথ্য বাতিল হয়েছে</h2>
                            </div>
                            
                            <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                প্রিয় <strong>{{ $listing->user->name ?? 'ব্যবহারকারী' }}</strong>,
                            </p>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                দুঃখিত! আপনার জমা দেওয়া তথ্যটি <strong style="color: #dc3545;">বাতিল</strong> করা হয়েছে। নিচে বাতিলের কারণ উল্লেখ করা হল:
                            </p>
                            
                            <!-- Listing Info -->
                            <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 25px 0; border: 1px solid #e9ecef;">
                                <h3 style="color: #1a3c34; margin: 0 0 15px 0; font-size: 18px;">
                                    📋 তথ্যের বিবরণ:
                                </h3>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="color: #666; padding: 8px 0; font-size: 14px; width: 100px;">শিরোনাম:</td>
                                        <td style="color: #333; padding: 8px 0; font-size: 14px; font-weight: bold;">{{ $listing->title_bn ?? $listing->title }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #666; padding: 8px 0; font-size: 14px;">ক্যাটাগরি:</td>
                                        <td style="color: #333; padding: 8px 0; font-size: 14px;">{{ $listing->category->name_bn ?? $listing->category->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #666; padding: 8px 0; font-size: 14px;">উপজেলা:</td>
                                        <td style="color: #333; padding: 8px 0; font-size: 14px;">{{ $listing->upazila_id ? ($listing->upazila->name_bn ?? $listing->upazila->name ?? 'N/A') : 'সকল উপজেলা' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Rejection Reason -->
                            <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; margin: 25px 0; border-radius: 0 8px 8px 0;">
                                <h3 style="color: #721c24; margin: 0 0 10px 0; font-size: 16px;">
                                    ⚠️ বাতিলের কারণ:
                                </h3>
                                <p style="color: #721c24; margin: 0; font-size: 15px; line-height: 1.8;">
                                    {{ $reason }}
                                </p>
                            </div>
                            
                            <!-- Action Box -->
                            <div style="background-color: #e7f3ff; border-left: 4px solid #0d6efd; padding: 20px; margin: 25px 0; border-radius: 0 8px 8px 0;">
                                <h3 style="color: #084298; margin: 0 0 10px 0; font-size: 16px;">
                                    💡 কি করবেন?
                                </h3>
                                <p style="color: #084298; margin: 0; font-size: 14px; line-height: 1.8;">
                                    বাতিলের কারণ অনুযায়ী তথ্য সংশোধন করে আবার জমা দিতে পারেন। আপনার ড্যাশবোর্ডে গিয়ে তথ্যটি সম্পাদনা করুন এবং পুনরায় জমা দিন।
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 35px 0;">
                                <a href="https://exploresatkhira.com/dashboard/listings" style="display: inline-block; background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);">
                                    ✏️ তথ্য সম্পাদনা করুন
                                </a>
                            </div>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                সাতক্ষীরা জেলার তথ্য সংরক্ষণে আপনার অবদানের জন্য আন্তরিক ধন্যবাদ। সঠিক তথ্য দিয়ে আবার জমা দিন।
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-radius: 0 0 10px 10px; border-top: 1px solid #e9ecef;">
                            <p style="color: #6c757d; font-size: 13px; margin: 0 0 10px 0;">
                                এই ইমেইল স্বয়ংক্রিয়ভাবে পাঠানো হয়েছে। অনুগ্রহ করে এই ইমেইলে উত্তর দেবেন না।
                            </p>
                            <p style="color: #6c757d; font-size: 13px; margin: 0;">
                                © {{ date('Y') }} Explore Satkhira | সাতক্ষীরা, বাংলাদেশ
                            </p>
                            <p style="margin: 15px 0 0 0;">
                                <a href="https://exploresatkhira.com" style="color: #28a745; text-decoration: none; font-size: 13px;">
                                    🌐 exploresatkhira.com
                                </a>
                            </p>
                            <p style="color: #adb5bd; font-size: 11px; margin: 15px 0 0 0; border-top: 1px solid #e9ecef; padding-top: 15px;">
                                Developer: Mir Javed Jeetu
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
