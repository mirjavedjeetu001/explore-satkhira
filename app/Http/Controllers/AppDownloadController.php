<?php

namespace App\Http\Controllers;

use App\Models\AppDownload;
use Illuminate\Http\Request;

class AppDownloadController extends Controller
{
    public function show()
    {
        $downloadCount = AppDownload::count();
        return view('frontend.app-download', compact('downloadCount'));
    }

    public function download(Request $request)
    {
        $apkPath = public_path('Explore-Satkhira.apk');
        if (!file_exists($apkPath)) {
            abort(404, 'APK file not found');
        }

        AppDownload::create([
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            'platform' => $this->detectPlatform($request->userAgent() ?? ''),
        ]);

        return response()->download($apkPath, 'Explore-Satkhira.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }

    private function detectPlatform(string $ua): string
    {
        if (preg_match('/android/i', $ua)) return 'Android';
        if (preg_match('/iphone|ipad/i', $ua)) return 'iOS';
        if (preg_match('/windows/i', $ua)) return 'Windows';
        if (preg_match('/macintosh/i', $ua)) return 'Mac';
        if (preg_match('/linux/i', $ua)) return 'Linux';
        return 'Unknown';
    }
}
