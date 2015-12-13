<?php

// Assume document root is one level up from vendor folder
$dest = '../../../imap_config.php';

// Make sure you don't overwrite any user files or previous installs
echo "Checking for imap_config.php in root\n";
usleep(200000);
if (!file_exists($dest)) {
    echo "Creating imap_config.php\n";
    usleep(200000);
    if (!@copy('src/imap_config.php', $dest)) {
        echo "ERROR: Failed to create imap_config.php file. please create one manually.";
    }else{
        echo "imap_config.php created successfully\n";
    }
}else{
    echo "imap_config already exists. Creation skipped.\n";
}


