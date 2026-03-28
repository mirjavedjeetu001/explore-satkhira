<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|url|max:2000',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'device_type' => 'nullable|string|max:50',
            'browser' => 'nullable|string|max:100',
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'p256dh' => $validated['keys']['p256dh'],
                'auth' => $validated['keys']['auth'],
                'device_type' => $validated['device_type'] ?? 'unknown',
                'browser' => $validated['browser'] ?? 'unknown',
                'is_active' => true,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);

        PushSubscription::where('endpoint', $request->endpoint)->delete();

        return response()->json(['success' => true]);
    }

    public function vapidPublicKey()
    {
        return response()->json([
            'publicKey' => config('services.vapid.public_key'),
        ]);
    }
}
