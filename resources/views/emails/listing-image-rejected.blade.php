<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>বিজ্ঞাপন বাতিল</title>
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
                                <h2 style="color: #dc3545; margin: 0; font-size: 24px;">আপনার বিজ্ঞাপন/ছবি বাতিল হয়েছে</h2>
                            </div>
                            
                            <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                প্রিয় <strong>{{ $listingImage->user->name ?? 'ব্যবহারকারী' }}</strong>,
                            </p>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                দুঃখিত! আপনার জমা দেওয়া বিজ্ঞাপন/ছবিটি <strong style="color: #dc3545;">বাতিল</strong> করা হয়েছে। নিচে বাতিলের কারণ উল্লেখ করা হল:
                            </p>
                            
                            <!-- Image Info -->
                            <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 25px 0; border: 1px solid #e9ecef;">
                                <h3 style="color: #1a3c34; margin: 0 0 15px 0; font-size: 18px;">
                                    📋 বিবরণ:
                                </h3>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="color: #666; padding: 8px 0; font-size: 14px; width: 100px;">শিরোনাম:</td>
                                        <td style="color: #333; padding: 8px 0; font-size: 14px; font-weight: 600;">{{ $listingImage->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #666; padding: 8px 0; font-size: 14px;">ধরন:</td>
                                        <td style="color: #333; padding: 8px 0; font-size: 14px;">{{ App\Models\ListingImage::getTypes()[$listingImage->type] ?? $listingImage->type }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #666; padding: 8px 0; font-size: 14px;">তালিকা:</td>
                                        <td style="color: #333; padding: 8px 0; font-size: 14px;">{{ $listingImage->listing->title ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Rejection Reason -->
                            <div style="background-color: #fff3cd; border-radius: 8px; padding: 20px; margin: 25px 0; border-left: 4px solid #ffc107;">
                                <h3 style="color: #856404; margin: 0 0 10px 0; font-size: 16px;">
                                    ⚠️ বাতিলের কারণ:
                                </h3>
                                <p style="color: #856404; font-size: 14px; margin: 0; line-height: 1.6;">
                                    {{ $reason }}
                                </p>
                            </div>

                            @if($listingImage->image)
                            <div style="text-align: center; margin: 25px 0;">
                                <p style="color: #666; font-size: 14px; margin-bottom: 10px;">বাতিলকৃত ছবি:</p>
                                <img src="{{ asset('storage/' . $listingImage->image) }}" 
                                     alt="{{ $listingImage->title }}" 
                                     style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); opacity: 0.7;">
                            </div>
                            @endif
                            
                            <!-- Info -->
                            <div style="background-color: #e8f5e9; border-radius: 8px; padding: 20px; margin: 25px 0;">
                                <h3 style="color: #1a3c34; margin: 0 0 10px 0; font-size: 16px;">
                                    💡 কি করবেন?
                                </h3>
                                <ul style="color: #555; font-size: 14px; margin: 0; padding-left: 20px; line-height: 1.8;">
                                    <li>বাতিলের কারণ অনুযায়ী আপনার ছবি/বিজ্ঞাপন সংশোধন করুন</li>
                                    <li>নতুন ছবি আপলোড করুন</li>
                                    <li>প্রশ্ন থাকলে আমাদের সাথে যোগাযোগ করুন</li>
                                </ul>
                            </div>
                            
                            <!-- CTA -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('dashboard.listings.images', $listingImage->listing->slug ?? '') }}" 
                                   style="display: inline-block; background: linear-gradient(135deg, #28a745 0%, #1a3c34 100%); color: #ffffff; text-decoration: none; padding: 14px 35px; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                                    নতুন ছবি আপলোড করুন
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-radius: 0 0 10px 10px; border-top: 1px solid #e9ecef;">
                            <p style="color: #666; font-size: 14px; margin: 0 0 10px 0;">
                                ধন্যবাদসহ,<br><strong style="color: #1a3c34;">Explore Satkhira Team</strong>
                            </p>
                            <p style="color: #999; font-size: 12px; margin: 15px 0 0 0;">
                                © {{ date('Y') }} Explore Satkhira. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
