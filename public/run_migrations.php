<?php

// Load the Laravel application
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

// Bootstrap the console kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

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
        .info { color: #0066cc; }
        .warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffeeba; }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Migration Tool</h1>
        
        <div class="warning">
            <strong>SECURITY WARNING:</strong> Please delete this file (<code>public/run_migrations.php</code>) immediately after use. Leaving it accessible allows anyone to run migrations on your database.
        </div>

        <?php
        $action = $_GET['action'] ?? 'menu';
        
        if ($action === 'menu') {
            echo '<h3>Chagua Action:</h3>';
            echo '<a href="?action=migrate" class="btn">Run All Migrations</a>';
            echo '<a href="?action=fix_customer_requests" class="btn btn-success">Fix customer_requests Table (Add table_id, waiter_id)</a>';
            echo '<a href="?action=status" class="btn">Check Migration Status</a>';
            echo '<a href="?action=show_columns" class="btn">Show customer_requests Columns</a>';
        }
        
        elseif ($action === 'show_columns') {
            try {
                echo "<h3>Columns in customer_requests table:</h3>";
                $columns = Schema::getColumnListing('customer_requests');
                echo "<pre>";
                foreach ($columns as $column) {
                    $type = DB::selectOne("SHOW COLUMNS FROM customer_requests WHERE Field = ?", [$column]);
                    echo "• {$column} ({$type->Type})" . ($type->Null === 'YES' ? ' [nullable]' : ' [required]') . "\n";
                }
                echo "</pre>";
                
                // Check specific columns
                $hasTableId = in_array('table_id', $columns);
                $hasWaiterId = in_array('waiter_id', $columns);
                
                echo "<h4>Status:</h4>";
                echo $hasTableId ? "<p class='success'>✓ table_id column EXISTS</p>" : "<p class='error'>✗ table_id column MISSING</p>";
                echo $hasWaiterId ? "<p class='success'>✓ waiter_id column EXISTS</p>" : "<p class='error'>✗ waiter_id column MISSING</p>";
                
            } catch (\Exception $e) {
                echo "<h3 class='error'>Error:</h3>";
                echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
            }
            echo '<br><a href="?" class="btn">← Back</a>';
        }
        
        elseif ($action === 'status') {
            try {
                echo "<h3>Migration Status:</h3>";
                Artisan::call('migrate:status');
                $output = Artisan::output();
                echo "<pre>$output</pre>";
            } catch (\Exception $e) {
                echo "<h3 class='error'>Error:</h3>";
                echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
            }
            echo '<br><a href="?" class="btn">← Back</a>';
        }
        
        elseif ($action === 'fix_customer_requests') {
            try {
                echo "<h3>Updating customer_requests table...</h3>";
                $messages = [];
                
                // Check if columns already exist
                $hasTableId = Schema::hasColumn('customer_requests', 'table_id');
                $hasWaiterId = Schema::hasColumn('customer_requests', 'waiter_id');
                
                if ($hasTableId && $hasWaiterId) {
                    echo "<p class='info'>✓ Columns table_id and waiter_id already exist. No changes needed.</p>";
                } else {
                    Schema::table('customer_requests', function (Blueprint $table) use ($hasTableId, $hasWaiterId, &$messages) {
                        // Make table_number nullable
                        $table->string('table_number')->nullable()->change();
                        $messages[] = "✓ Made table_number nullable";
                        
                        // Add table_id if not exists
                        if (!$hasTableId) {
                            $table->foreignId('table_id')->nullable()->after('table_number')->constrained()->nullOnDelete();
                            $messages[] = "✓ Added table_id column";
                        }
                        
                        // Add waiter_id if not exists
                        if (!$hasWaiterId) {
                            $table->foreignId('waiter_id')->nullable()->after('table_id')->constrained('users')->nullOnDelete();
                            $messages[] = "✓ Added waiter_id column";
                        }
                    });
                    
                    foreach ($messages as $msg) {
                        echo "<p class='success'>$msg</p>";
                    }
                }
                
                echo "<h3 class='success'>✓ customer_requests table updated successfully!</h3>";
                
            } catch (\Exception $e) {
                echo "<h3 class='error'>Error:</h3>";
                echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
            echo '<br><a href="?" class="btn">← Back</a>';
        }
        
        elseif ($action === 'migrate') {
            try {
                echo "<p>Running <code>php artisan migrate --force</code>...</p>";
                
                Artisan::call('migrate', ['--force' => true]);
                $output = Artisan::output();
                
                if (empty($output) || str_contains($output, 'Nothing to migrate')) {
                    echo "<pre>No migrations to run (Nothing to migrate).</pre>";
                } else {
                    echo "<pre>$output</pre>";
                }
                
                echo "<h3 class='success'>Migration command executed successfully.</h3>";
                
            } catch (\Exception $e) {
                echo "<h3 class='error'>Error Executing Migration:</h3>";
                echo "<pre>" . htmlspecialchars($e->getMessage()) . "\n\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
            echo '<br><a href="?" class="btn">← Back</a>';
        }
        ?>
    </div>
</body>
</html>
