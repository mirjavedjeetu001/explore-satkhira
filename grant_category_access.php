<?php
// Grant access to category 16 (Residential Hotel) to all users with approved categories
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$userIds = [2, 4, 5, 6];
$adminId = 1;
$now = now();
$count = 0;

foreach ($userIds as $userId) {
    $exists = DB::table('user_category_permissions')
        ->where('user_id', $userId)
        ->where('category_id', 16)
        ->exists();
    
    if (!$exists) {
        DB::table('user_category_permissions')->insert([
            'user_id' => $userId,
            'category_id' => 16,
            'is_approved' => true,
            'approved_by' => $adminId,
            'approved_at' => $now,
            'created_at' => $now,
            'updated_at' => $now
        ]);
        $count++;
    }
}

echo "Granted access to {$count} users for Residential Hotel category\n";
