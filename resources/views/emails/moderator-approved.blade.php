<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>উপজেলা মডারেটর নিযুক্তি</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">
                                <span>🛡️</span> Explore Satkhira
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 14px;">
                                উপজেলা মডারেটর নিযুক্তি
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #f39c12, #e67e22); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <span style="font-size: 50px;">🎉</span>
                                </div>
                                <h2 style="color: #e67e22; margin: 0; font-size: 26px;">অভিনন্দন!</h2>
                                <p style="color: #666; font-size: 16px; margin: 10px 0 0 0;">আপনি উপজেলা মডারেটর হিসেবে নিযুক্ত হয়েছেন!</p>
                            </div>
                            
                            <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                প্রিয় <strong style="color: #e67e22;">{{ $user->name }}</strong>,
                            </p>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                আমরা অত্যন্ত আনন্দের সাথে জানাচ্ছি যে আপনাকে <strong>Explore Satkhira</strong> পোর্টালে 
                                <strong style="color: #e67e22;">{{ $user->upazila->name_bn ?? $user->upazila->name ?? 'আপনার উপজেলার' }}</strong> 
                                জন্য <strong>উপজেলা মডারেটর</strong> হিসেবে নিযুক্ত করা হয়েছে!
                            </p>
                            
                            <div style="background: linear-gradient(135deg, #fff3cd, #ffeaa7); border-left: 4px solid #f39c12; padding: 20px; margin: 25px 0; border-radius: 0 10px 10px 0;">
                                <p style="color: #856404; margin: 0 0 15px 0; font-size: 16px; font-weight: bold;">
                                    🛡️ আপনার নতুন দায়িত্বসমূহ:
                                </p>
                                <ul style="color: #856404; margin: 0; padding-left: 20px; font-size: 14px; line-height: 2;">
                                    <li>{{ $user->upazila->name_bn ?? $user->upazila->name ?? 'আপনার উপজেলার' }} সকল ক্যাটাগরিতে তথ্য যোগ করতে পারবেন</li>
                                    <li>আপনার এলাকার তথ্য সঠিকভাবে আপডেট রাখতে সাহায্য করবেন</li>
                                    <li>নতুন তথ্য যোগ করে পোর্টালকে সমৃদ্ধ করবেন</li>
                                    <li>স্থানীয় জনগণকে পোর্টাল সম্পর্কে জানাতে সাহায্য করবেন</li>
                                </ul>
                            </div>
                            
                            <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px 20px; margin: 25px 0; border-radius: 0 5px 5px 0;">
                                <p style="color: #155724; margin: 0; font-size: 14px;">
                                    <strong>✅ বিশেষ সুবিধা:</strong> আপনি এখন {{ $user->upazila->name_bn ?? $user->upazila->name ?? 'আপনার উপজেলার' }} সকল ক্যাটাগরিতে তথ্য যোগ করতে পারবেন - কোন আলাদা অনুমোদনের প্রয়োজন নেই!
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 35px 0;">
                                <a href="https://exploresatkhira.com/dashboard" style="display: inline-block; background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(243, 156, 18, 0.4);">
                                    🚀 ড্যাশবোর্ডে যান
                                </a>
                            </div>
                            
                            <div style="background-color: #f8f9fa; border-radius: 10px; padding: 20px; margin: 25px 0; text-align: center;">
                                <p style="color: #666; font-size: 14px; margin: 0;">
                                    আপনার প্রোফাইল এখন <strong>"আমাদের সম্পর্কে"</strong> পেজের 
                                    <strong style="color: #e67e22;">উপজেলা মডারেটরস</strong> সেকশনে দেখা যাচ্ছে!
                                </p>
                                <a href="https://exploresatkhira.com/about" style="color: #e67e22; font-size: 13px;">
                                    দেখুন →
                                </a>
                            </div>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                সাতক্ষীরা জেলার উন্নয়নে আপনার অবদান অত্যন্ত মূল্যবান। আমরা আপনার সাথে কাজ করতে পেরে গর্বিত।
                            </p>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8;">
                                শুভকামনা সহ,<br>
                                <strong style="color: #1a3c34;">Explore Satkhira টিম</strong>
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
                                <a href="https://exploresatkhira.com" style="color: #f39c12; text-decoration: none; font-size: 13px;">
                                    🌐 exploresatkhira.com
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
