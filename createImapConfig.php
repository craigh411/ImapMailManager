<?php

// Assume document root is one level up from vendor folder
$dest = 'imap_config/config.php';

// Make sure you don't overwrite any user files or previous installs
echo "Checking for existing config file \n";
usleep(200000);

if (!is_dir('imap_config')) {
    mkdir('imap_config');
}

if (!file_exists($dest)) {
    echo "Creating imap_config/config.php\n";
    usleep(200000);
    $configFile = dirname(__FILE__).'\config.php';
    if (!@copy($configFile, $dest)) {
        echo "ERROR: Failed to create imap_config/config.php file. please create one manually.";
    } else {
        echo "imap_config/config.php created successfully\n";
    }
} else {
    echo "imap_config/config.php already exists. Creation skipped.\n";
}



