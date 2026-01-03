<?php

// Load the Laravel application
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

// Bootstrap the console kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Migration Runner</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f0f0; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        pre { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 4px; overflow-x: auto; }
        .success { color: green; }
        .error { color: red; }
        .warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffeeba; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Migration Tool</h1>
        
        <div class="warning">
            <strong>SECURITY WARNING:</strong> Please delete this file (<code>public/run_migrations.php</code>) immediately after use. Leaving it accessible allows anyone to run migrations on your database.
        </div>

        <?php
        try {
            echo "<p>Running <code>php artisan migrate --force</code>...</p>";
            
            // Run the migration command
            // --force is required to run in production mode
            Artisan::call('migrate', ['--force' => true]);
            
            // Get and display the output
            $output = Artisan::output();
            
            if (empty($output)) {
                echo "<pre>No migrations to run (Nothing to migrate).</pre>";
            } else {
                echo "<pre>$output</pre>";
            }
            
            echo "<h3 class='success'>Migration command executed successfully.</h3>";
            
        } catch (\Exception $e) {
            echo "<h3 class='error'>Error Executing Migration:</h3>";
            echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }
        ?>
    </div>
</body>
</html>
