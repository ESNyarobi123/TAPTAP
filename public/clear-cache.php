<?php
// DELETE THIS FILE AFTER USE FOR SECURITY!

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Clearing Laravel Caches...</h2>";

try {
    $kernel->call('cache:clear');
    echo "✅ Application cache cleared<br>";
    
    $kernel->call('config:clear');
    echo "✅ Config cache cleared<br>";
    
    $kernel->call('route:clear');
    echo "✅ Route cache cleared<br>";
    
    $kernel->call('view:clear');
    echo "✅ View cache cleared<br>";
    
    // Clear OPcache if available
    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "✅ OPcache cleared<br>";
    }
    
    echo "<br><h3 style='color: green;'>All caches cleared successfully!</h3>";
    echo "<p><strong>⚠️ DELETE THIS FILE NOW!</strong></p>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
