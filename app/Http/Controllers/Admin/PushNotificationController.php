<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushNotificationController extends Controller
{
    public function index()
    {
        $notifications = PushNotification::with('sender')
            ->orderByDesc('created_at')
            ->paginate(20);

        $totalSubscribers = PushSubscription::where('is_active', true)->count();
        $deviceStats = PushSubscription::where('is_active', true)
            ->selectRaw("device_type, COUNT(*) as count")
            ->groupBy('device_type')
            ->pluck('count', 'device_type');

        return view('admin.push-notifications.index', compact('notifications', 'totalSubscribers', 'deviceStats'));
    }

    public function create()
    {
        $totalSubscribers = PushSubscription::where('is_active', true)->count();
        return view('admin.push-notifications.create', compact('totalSubscribers'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:300',
            'url' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:1024',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('push-images', 'public');
            $imageUrl = asset('storage/' . $imageUrl);
        }

        $subscriptions = PushSubscription::where('is_active', true)->get();
        $sent = 0;
        $failed = 0;

        $payload = json_encode([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'icon' => asset('icons/icon-192x192.png'),
            'badge' => asset('icons/icon-96x96.png'),
            'url' => $validated['url'] ?? '/',
            'image' => $imageUrl,
        ]);

        foreach ($subscriptions as $sub) {
            try {
                $result = $this->sendWebPush(
                    $sub->endpoint,
                    $sub->p256dh,
                    $sub->auth,
                    $payload
                );

                if ($result === true) {
                    $sent++;
                } else {
                    $failed++;
                    if ($result === 'gone') {
                        $sub->delete();
                    }
                }
            } catch (\Exception $e) {
                $failed++;
                Log::warning('Push notification failed', [
                    'endpoint' => substr($sub->endpoint, 0, 50),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        PushNotification::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'icon' => asset('icons/icon-192x192.png'),
            'url' => $validated['url'] ?? '/',
            'image' => $imageUrl,
            'total_sent' => $sent,
            'total_failed' => $failed,
            'sent_by' => auth()->id(),
        ]);

        return redirect()->route('admin.push-notifications.index')
            ->with('success', "নোটিফিকেশন পাঠানো হয়েছে! সফল: {$sent}, ব্যর্থ: {$failed}");
    }

    public function subscribers()
    {
        $subscribers = PushSubscription::where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(50);

        $deviceStats = PushSubscription::where('is_active', true)
            ->selectRaw("device_type, COUNT(*) as count")
            ->groupBy('device_type')
            ->pluck('count', 'device_type');

        return view('admin.push-notifications.subscribers', compact('subscribers', 'deviceStats'));
    }

    public function destroy(PushNotification $pushNotification)
    {
        $pushNotification->delete();
        return redirect()->route('admin.push-notifications.index')
            ->with('success', 'নোটিফিকেশন ডিলিট করা হয়েছে।');
    }

    /**
     * Send a Web Push notification using VAPID + RFC 8291 encryption (native PHP).
     */
    private function sendWebPush(string $endpoint, string $p256dh, string $auth, string $payload): bool|string
    {
        $vapidPublicKey = config('services.vapid.public_key');
        $vapidPrivateKey = config('services.vapid.private_key');

        $parsed = parse_url($endpoint);
        $audience = $parsed['scheme'] . '://' . $parsed['host'];

        // Create VAPID JWT (ES256)
        $header = $this->base64urlEncode(json_encode(['typ' => 'JWT', 'alg' => 'ES256']));
        $jwtPayload = $this->base64urlEncode(json_encode([
            'aud' => $audience,
            'exp' => time() + 43200,
            'sub' => 'mailto:info@exploresatkhira.com',
        ]));

        $signingInput = $header . '.' . $jwtPayload;

        $privateKeyDer = $this->createEcPrivateKeyDer($vapidPublicKey, $vapidPrivateKey);
        $pem = "-----BEGIN EC PRIVATE KEY-----\n" . chunk_split(base64_encode($privateKeyDer), 64, "\n") . "-----END EC PRIVATE KEY-----";
        $key = openssl_pkey_get_private($pem);
        if (!$key) {
            throw new \RuntimeException('Failed to load VAPID private key');
        }

        openssl_sign($signingInput, $signature, $key, OPENSSL_ALGO_SHA256);
        $sig = $this->derSignatureToRaw($signature);
        $jwt = $signingInput . '.' . $this->base64urlEncode($sig);

        // Decrypt subscription keys (stored as standard base64 from browser btoa)
        $userPublicKey = base64_decode($p256dh);
        $userAuth = base64_decode($auth);

        // Encrypt payload per RFC 8291 (aes128gcm)
        $encryptedPayload = $this->encryptPayload($payload, $userPublicKey, $userAuth);

        // Send HTTP request to push service
        $headers = [
            'Authorization: vapid t=' . $jwt . ', k=' . $vapidPublicKey,
            'Content-Type: application/octet-stream',
            'Content-Encoding: aes128gcm',
            'TTL: 86400',
            'Content-Length: ' . strlen($encryptedPayload),
        ];

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $encryptedPayload,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        Log::warning('Push send failed', [
            'httpCode' => $httpCode,
            'response' => substr($response ?: '', 0, 300),
            'endpoint' => substr($endpoint, 0, 60),
        ]);

        if ($httpCode === 404 || $httpCode === 410) {
            return 'gone';
        }

        return false;
    }

    /**
     * Encrypt payload per RFC 8291 + RFC 8188 (aes128gcm content encoding).
     */
    private function encryptPayload(string $payload, string $userPublicKey, string $userAuth): string
    {
        // Generate ephemeral ECDH key pair
        $localKey = openssl_pkey_new([
            'curve_name' => 'prime256v1',
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ]);
        $localDetails = openssl_pkey_get_details($localKey);
        $localPublicKey = $this->extractUncompressedPublicKey($localDetails);

        // Compute ECDH shared secret
        $peerPem = $this->createEcPublicKeyPem(
            substr($userPublicKey, 1, 32),
            substr($userPublicKey, 33, 32)
        );
        $peerKey = openssl_pkey_get_public($peerPem);
        if (!$peerKey) {
            throw new \RuntimeException('Failed to load peer public key');
        }

        $sharedSecret = openssl_pkey_derive($peerKey, $localKey);
        if ($sharedSecret === false) {
            throw new \RuntimeException('ECDH key derivation failed');
        }

        // RFC 8291 Section 3.4 - Key derivation
        // Step 1: Derive IKM from ECDH shared secret + auth secret
        $keyInfo = "WebPush: info\0" . $userPublicKey . $localPublicKey;
        $prk = hash_hmac('sha256', $sharedSecret, $userAuth, true);
        $ikm = substr(hash_hmac('sha256', $keyInfo . chr(1), $prk, true), 0, 32);

        // Step 2: Derive CEK and nonce per RFC 8188 (aes128gcm)
        $salt = random_bytes(16);
        $prk2 = hash_hmac('sha256', $ikm, $salt, true);

        $cekInfo = "Content-Encoding: aes128gcm\0";
        $cek = substr(hash_hmac('sha256', $cekInfo . chr(1), $prk2, true), 0, 16);

        $nonceInfo = "Content-Encoding: nonce\0";
        $nonce = substr(hash_hmac('sha256', $nonceInfo . chr(1), $prk2, true), 0, 12);

        // Pad payload with delimiter byte (0x02 = final record)
        $paddedPayload = $payload . chr(2);

        // AES-128-GCM encryption
        $tag = '';
        $encrypted = openssl_encrypt($paddedPayload, 'aes-128-gcm', $cek, OPENSSL_RAW_DATA, $nonce, $tag, '', 16);

        // Build aes128gcm header: salt(16) + rs(4) + idlen(1) + keyid(65)
        $header = $salt . pack('N', 4096) . chr(65) . $localPublicKey;

        return $header . $encrypted . $tag;
    }

    private function extractUncompressedPublicKey(array $details): string
    {
        $x = str_pad($details['ec']['x'], 32, "\0", STR_PAD_LEFT);
        $y = str_pad($details['ec']['y'], 32, "\0", STR_PAD_LEFT);
        return "\x04" . $x . $y;
    }

    private function createEcPublicKeyPem(string $x, string $y): string
    {
        $point = "\x04" . $x . $y;
        // SubjectPublicKeyInfo: AlgId(21 bytes) + BIT STRING overhead(3 bytes) + point
        $der = "\x30" . chr(24 + strlen($point))
            . "\x30\x13\x06\x07\x2a\x86\x48\xce\x3d\x02\x01\x06\x08\x2a\x86\x48\xce\x3d\x03\x01\x07"
            . "\x03" . chr(strlen($point) + 1) . "\x00" . $point;
        return "-----BEGIN PUBLIC KEY-----\n" . chunk_split(base64_encode($der), 64, "\n") . "-----END PUBLIC KEY-----";
    }

    private function createEcPrivateKeyDer(string $publicKeyB64url, string $privateKeyB64url): string
    {
        $privBytes = $this->base64urlDecode($privateKeyB64url);
        $privBytes = str_pad($privBytes, 32, "\x00", STR_PAD_LEFT);

        $oidP256 = "\x06\x08\x2a\x86\x48\xce\x3d\x03\x01\x07";

        $sequence = "\x02\x01\x01";  // version 1
        $sequence .= "\x04\x20" . $privBytes;  // privateKey OCTET STRING (32 bytes)
        $sequence .= "\xa0" . chr(strlen($oidP256)) . $oidP256;  // parameters [0] namedCurve

        return "\x30" . chr(strlen($sequence)) . $sequence;
    }

    private function derSignatureToRaw(string $der): string
    {
        $pos = 2;
        $rLen = ord($der[$pos + 1]);
        $r = substr($der, $pos + 2, $rLen);
        $pos = $pos + 2 + $rLen;
        $sLen = ord($der[$pos + 1]);
        $s = substr($der, $pos + 2, $sLen);

        $r = ltrim($r, "\x00");
        $s = ltrim($s, "\x00");
        $r = str_pad($r, 32, "\x00", STR_PAD_LEFT);
        $s = str_pad($s, 32, "\x00", STR_PAD_LEFT);

        return $r . $s;
    }

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64urlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', (4 - strlen($data) % 4) % 4));
    }
}
