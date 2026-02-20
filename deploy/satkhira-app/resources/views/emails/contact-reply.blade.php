<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re: {{ $contact->subject }}</title>
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
                                আপনার বার্তার উত্তর
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="width: 80px; height: 80px; background-color: #d4edda; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <span style="font-size: 40px;">✉️</span>
                                </div>
                                <h2 style="color: #1a3c34; margin: 0; font-size: 24px;">আপনার বার্তার উত্তর</h2>
                            </div>
                            
                            <p style="color: #333; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                                প্রিয় <strong>{{ $contact->name }}</strong>,
                            </p>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                আপনার বার্তার জন্য ধন্যবাদ। নিচে আমাদের উত্তর দেওয়া হলো:
                            </p>
                            
                            <!-- Original Message -->
                            <div style="background-color: #f8f9fa; border-left: 4px solid #6c757d; padding: 15px 20px; margin: 25px 0; border-radius: 0 5px 5px 0;">
                                <p style="color: #6c757d; margin: 0 0 10px 0; font-size: 12px; text-transform: uppercase;">
                                    📝 আপনার আসল বার্তা:
                                </p>
                                <p style="color: #6c757d; margin: 0 0 5px 0; font-size: 14px;">
                                    <strong>বিষয়:</strong> {{ $contact->subject }}
                                </p>
                                <p style="color: #6c757d; margin: 0; font-size: 14px; white-space: pre-wrap;">{{ $contact->message }}</p>
                            </div>
                            
                            <!-- Reply -->
                            <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin: 25px 0; border-radius: 0 8px 8px 0;">
                                <p style="color: #155724; margin: 0 0 10px 0; font-size: 12px; text-transform: uppercase;">
                                    💬 আমাদের উত্তর:
                                </p>
                                <p style="color: #155724; margin: 0; font-size: 15px; line-height: 1.8; white-space: pre-wrap;">{{ $replyMessage }}</p>
                            </div>
                            
                            <p style="color: #555; font-size: 15px; line-height: 1.8; margin-bottom: 20px;">
                                আরও কোনো প্রশ্ন থাকলে আমাদের সাথে যোগাযোগ করতে দ্বিধা করবেন না।
                            </p>
                            
                            <div style="text-align: center; margin: 35px 0;">
                                <a href="https://exploresatkhira.com/contact" style="display: inline-block; background: linear-gradient(135deg, #28a745 0%, #1a3c34 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                                    🌐 ওয়েবসাইট ভিজিট করুন
                                </a>
                            </div>
                            
                            <p style="color: #555; font-size: 14px; line-height: 1.6; margin-top: 30px;">
                                শুভেচ্ছান্তে,<br>
                                <strong style="color: #1a3c34;">Explore Satkhira টিম</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-radius: 0 0 10px 10px; border-top: 1px solid #e9ecef;">
                            <p style="color: #6c757d; font-size: 13px; margin: 0 0 10px 0;">
                                এই ইমেইলের উত্তর দেওয়া হলে আমরা তা পাবো।
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
