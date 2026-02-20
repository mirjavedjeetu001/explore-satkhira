<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাকাউন্ট অনুমোদিত</title>
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
                                <div style="width: 80px; height: 80px; background-color: #d4edda; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <span style="font-size: 40px;">🎉</span>
                                </div>
                                <h2 style="color: #1a3c34; margin: 0; font-size: 24px;">অভিনন্দন! আপনার অ্যাকাউন্ট অনুমোদিত হয়েছে!</h2>
                            </div>
                            
                            <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                প্রিয় <strong>{{ $user->name }}</strong>,
                            </p>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                আমরা আনন্দের সাথে জানাচ্ছি যে আপনার Explore Satkhira অ্যাকাউন্টটি <strong style="color: #28a745;">অনুমোদিত</strong> হয়েছে! এখন আপনি আমাদের প্ল্যাটফর্মের সকল সুবিধা উপভোগ করতে পারবেন।
                            </p>
                            
                            <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px 20px; margin: 25px 0; border-radius: 0 5px 5px 0;">
                                <p style="color: #155724; margin: 0; font-size: 14px;">
                                    <strong>✅ আপনি এখন যা করতে পারবেন:</strong>
                                </p>
                                <ul style="color: #155724; margin: 10px 0 0 0; padding-left: 20px; font-size: 14px;">
                                    <li>নতুন তথ্য (লিস্টিং) যোগ করুন</li>
                                    <li>আপনার প্রোফাইল সম্পাদনা করুন</li>
                                    <li>অন্যান্য তথ্যতে মন্তব্য করুন</li>
                                    <li>আপনার ড্যাশবোর্ড পরিচালনা করুন</li>
                                </ul>
                            </div>
                            
                            <div style="text-align: center; margin: 35px 0;">
                                <a href="https://exploresatkhira.com/login" style="display: inline-block; background: linear-gradient(135deg, #28a745 0%, #1a3c34 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                                    🚀 এখনই লগইন করুন
                                </a>
                            </div>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                সাতক্ষীরা জেলার তথ্য সংরক্ষণে আপনার অবদান অত্যন্ত মূল্যবান। আপনার যেকোনো সাহায্যের জন্য আমাদের সাথে যোগাযোগ করুন।
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
