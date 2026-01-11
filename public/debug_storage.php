<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Storage Debugger</h1>";

$publicStorage = __DIR__ . '/storage';
$target = __DIR__ . '/../storage/app/public';

echo "<h2>Paths</h2>";
echo "Public Storage Link: " . $publicStorage . "<br>";
echo "Target Directory: " . $target . "<br>";

echo "<h2>Status</h2>";

if (file_exists($publicStorage)) {
    echo "Public storage exists.<br>";
    if (is_link($publicStorage)) {
        echo "It is a SYMLINK.<br>";
        echo "Link target: " . readlink($publicStorage) . "<br>";
    } elseif (is_dir($publicStorage)) {
        echo "It is a DIRECTORY (Not a symlink). This might be the problem if it's empty.<br>";
    } else {
        echo "It is a FILE.<br>";
    }
} else {
    echo "Public storage does NOT exist.<br>";
}

echo "<h2>Permissions</h2>";
echo "Current User: " . get_current_user() . "<br>";
echo "Script Owner: " . get_current_user() . "<br>";

if (file_exists($publicStorage)) {
    echo "Perms: " . substr(sprintf('%o', fileperms($publicStorage)), -4) . "<br>";
}

echo "<h2>Test Image Access</h2>";
// Check the specific file mentioned
$testFile = 'menu_images/8RsxuRCQBJeTsqTQDnYbUe7ZeVX4fkR74LN9FoE0.jpg';
$fullPath = $publicStorage . '/' . $testFile;

echo "Checking: " . $fullPath . "<br>";

if (file_exists($fullPath)) {
    echo "File EXISTS.<br>";
    echo "File Perms: " . substr(sprintf('%o', fileperms($fullPath)), -4) . "<br>";
} else {
    echo "File NOT FOUND.<br>";
}

echo "<h2>Attempting Fix (Symlink)</h2>";
if (isset($_GET['fix'])) {
    if (file_exists($publicStorage)) {
        if (is_link($publicStorage)) {
            echo "Unlinking existing symlink...<br>";
            unlink($publicStorage);
        } elseif (is_dir($publicStorage)) {
            echo "Found a directory at public/storage. Renaming it to storage_backup...<br>";
            rename($publicStorage, $publicStorage . '_backup_' . time());
        }
    }
    
    // Create symlink
    // Note: On some shared hosts, relative paths work better
    $relativeTarget = '../storage/app/public';
    if (symlink($relativeTarget, $publicStorage)) {
        echo "Symlink created successfully!<br>";
    } else {
        echo "Failed to create symlink. Check permissions or disable_functions.<br>";
    }
} else {
    echo "<a href='?fix=1'>Click here to attempt to fix the symlink</a>";
}
