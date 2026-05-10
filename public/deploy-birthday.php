<?php
/**
 * Birthday Feature Deployment Script
 * 
 * This script handles the complete deployment of the Birthday feature
 * Upload this file to /public_html/exploresatkhira.com/deploy-birthday.php
 * Then access: https://exploresatkhira.com/deploy-birthday.php
 * 
 * DELETE THIS FILE AFTER DEPLOYMENT IS COMPLETE
 */

// Security check - only run from localhost or with token
$allowed_hosts = ['127.0.0.1', 'localhost', '::1'];
$token = $_GET['token'] ?? '';
$valid_token = md5('satkhira_birthday_deploy_' . date('Y-m-d'));

if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowed_hosts) && $token !== $valid_token) {
    http_response_code(403);
    die("Access Denied");
}

set_time_limit(300); // 5 minutes

echo "<pre style='background: #1e1e1e; color: #00ff00; padding: 20px; font-family: monospace;'>";
echo "🚀 Birthday Feature Deployment\n";
echo "===============================\n\n";

$base_path = dirname(__FILE__);
$app_path = $base_path . '/app';

// Helper function for file operations
function copy_file($source, $destination, $create_dirs = true) {
    if (!file_exists($source)) {
        echo "❌ Source file missing: $source\n";
        return false;
    }
    
    $dest_dir = dirname($destination);
    
    if ($create_dirs && !is_dir($dest_dir)) {
        if (!mkdir($dest_dir, 0755, true)) {
            echo "❌ Failed to create directory: $dest_dir\n";
            return false;
        }
        echo "📁 Created directory: $dest_dir\n";
    }
    
    if (copy($source, $destination)) {
        echo "✅ Copied: " . basename($source) . "\n";
        return true;
    } else {
        echo "❌ Failed to copy: $source\n";
        return false;
    }
}

$files_to_copy = [
    // Models
    '/tmp/satkhira-prod-deploy/BirthdayCard.php' => $app_path . '/Models/BirthdayCard.php',
    '/tmp/satkhira-prod-deploy/BirthdayCardComment.php' => $app_path . '/Models/BirthdayCardComment.php',
    
    // Controllers
    '/tmp/satkhira-prod-deploy/BirthdayCardController.php' => $app_path . '/Http/Controllers/BirthdayCardController.php',
    '/tmp/satkhira-prod-deploy/BirthdayController.php' => $app_path . '/Http/Controllers/Admin/BirthdayController.php',
    
    // Console Command
    '/tmp/satkhira-prod-deploy/GenerateBirthdayCards.php' => $app_path . '/Console/Commands/GenerateBirthdayCards.php',
    
    // Migrations
    '/tmp/satkhira-prod-deploy/migrations/2026_05_10_000001_add_birthday_to_users_table.php' => $base_path . '/database/migrations/2026_05_10_000001_add_birthday_to_users_table.php',
    '/tmp/satkhira-prod-deploy/migrations/2026_05_10_000002_create_birthday_cards_table.php' => $base_path . '/database/migrations/2026_05_10_000002_create_birthday_cards_table.php',
    '/tmp/satkhira-prod-deploy/migrations/2026_05_10_000003_create_birthday_card_comments_table.php' => $base_path . '/database/migrations/2026_05_10_000003_create_birthday_card_comments_table.php',
    
    // Views
    '/tmp/satkhira-prod-deploy/views/birthday-cards/show.blade.php' => $base_path . '/resources/views/birthday-cards/show.blade.php',
    '/tmp/satkhira-prod-deploy/views/birthday-cards/todays.blade.php' => $base_path . '/resources/views/birthday-cards/todays.blade.php',
    '/tmp/satkhira-prod-deploy/views/admin/birthdays/index.blade.php' => $base_path . '/resources/views/admin/birthdays/index.blade.php',
    '/tmp/satkhira-prod-deploy/views/admin/birthdays/edit.blade.php' => $base_path . '/resources/views/admin/birthdays/edit.blade.php',
    '/tmp/satkhira-prod-deploy/views/admin/birthdays/todays.blade.php' => $base_path . '/resources/views/admin/birthdays/todays.blade.php',
];

echo "\n📤 Step 1: Copying Files\n";
echo "------------------------\n";
$copied = 0;
foreach ($files_to_copy as $source => $destination) {
    if (copy_file($source, $destination)) {
        $copied++;
    }
}
echo "\n✅ Copied $copied files\n";

echo "\n🔧 Step 2: Running Migrations\n";
echo "------------------------------\n";

// Load Laravel
try {
    $app = require_once $base_path . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    
    // Run migrations
    $status = $kernel->call('migrate', ['--force' => true]);
    
    if ($status === 0) {
        echo "✅ Migrations completed successfully\n";
    } else {
        echo "⚠️ Migration command returned status: $status\n";
    }
} catch (Exception $e) {
    echo "⚠️ Error running migrations: " . $e->getMessage() . "\n";
}

echo "\n🧹 Step 3: Clearing Cache\n";
echo "-------------------------\n";

try {
    $kernel->call('cache:clear');
    echo "✅ Cache cleared\n";
    
    $kernel->call('view:clear');
    echo "✅ Views cleared\n";
    
    $kernel->call('optimize:clear');
    echo "✅ Optimization cache cleared\n";
} catch (Exception $e) {
    echo "⚠️ Error clearing cache: " . $e->getMessage() . "\n";
}

echo "\n✅ DEPLOYMENT COMPLETE!\n\n";
echo "📋 Remaining Manual Steps:\n";
echo "1. Update routes/web.php - add birthday routes\n";
echo "2. Update HomeController.php - add sync call\n";
echo "3. Update home.blade.php - add birthday section\n";
echo "4. Update profile.blade.php - add birthday field\n";
echo "\nSee BIRTHDAY_DEPLOYMENT_GUIDE.md for exact code changes\n\n";
echo "⚠️ DELETE THIS FILE: deploy-birthday.php\n";
echo "🚀 Feature is ready! Visit https://exploresatkhira.com/birthday-cards/todays\n";

echo "</pre>";
?>
