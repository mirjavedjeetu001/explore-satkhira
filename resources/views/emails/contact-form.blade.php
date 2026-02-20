<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নতুন Contact বার্তা</title>
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
                                নতুন Contact বার্তা এসেছে
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="width: 80px; height: 80px; background-color: #e3f2fd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <span style="font-size: 40px;">📩</span>
                                </div>
                                <h2 style="color: #1a3c34; margin: 0; font-size: 24px;">নতুন বার্তা!</h2>
                            </div>
                            
                            <div style="background-color: #f8f9fa; border-radius: 10px; padding: 25px; margin-bottom: 25px;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                                            <strong style="color: #6c757d;">👤 নাম:</strong>
                                        </td>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef; text-align: right;">
                                            <span style="color: #1a3c34; font-weight: 600;">{{ $contact->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                                            <strong style="color: #6c757d;">📧 ইমেইল:</strong>
                                        </td>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef; text-align: right;">
                                            <a href="mailto:{{ $contact->email }}" style="color: #28a745; text-decoration: none;">{{ $contact->email }}</a>
                                        </td>
                                    </tr>
                                    @if($contact->phone)
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                                            <strong style="color: #6c757d;">📱 ফোন:</strong>
                                        </td>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef; text-align: right;">
                                            <a href="tel:{{ $contact->phone }}" style="color: #28a745; text-decoration: none;">{{ $contact->phone }}</a>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                                            <strong style="color: #6c757d;">📝 বিষয়:</strong>
                                        </td>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #e9ecef; text-align: right;">
                                            <span style="color: #1a3c34; font-weight: 600;">{{ $contact->subject }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0;">
                                            <strong style="color: #6c757d;">🕐 সময়:</strong>
                                        </td>
                                        <td style="padding: 10px 0; text-align: right;">
                                            <span style="color: #6c757d;">{{ $contact->created_at->format('d M, Y - h:i A') }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 25px 0; border-radius: 0 8px 8px 0;">
                                <p style="color: #856404; margin: 0 0 10px 0; font-weight: 600;">
                                    💬 বার্তা:
                                </p>
                                <p style="color: #333; margin: 0; line-height: 1.8; white-space: pre-wrap;">{{ $contact->message }}</p>
                            </div>
                            
                            <div style="text-align: center; margin: 35px 0;">
                                <a href="https://exploresatkhira.com/admin/contacts/{{ $contact->id }}" style="display: inline-block; background: linear-gradient(135deg, #28a745 0%, #1a3c34 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                                    👀 বার্তা দেখুন
                                </a>
                            </div>
                            
                            <div style="background-color: #d4edda; border-radius: 5px; padding: 15px 20px; margin: 25px 0;">
                                <p style="color: #155724; margin: 0; font-size: 14px;">
                                    💡 Admin panel থেকে এই বার্তায় সরাসরি reply করতে পারবেন।
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-radius: 0 0 10px 10px; border-top: 1px solid #e9ecef;">
                            <p style="color: #6c757d; font-size: 13px; margin: 0 0 10px 0;">
                                এই ইমেইল স্বয়ংক্রিয়ভাবে পাঠানো হয়েছে।
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
