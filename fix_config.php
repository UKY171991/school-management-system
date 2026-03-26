<?php

$file = 'config/adminlte.php';
$content = file_get_contents($file);

// Remove all __(' and ') patterns
$content = preg_replace("/__\('([^']+)'\)/", "'$1'", $content);
$content = preg_replace('/__\("([^"]+)"\)/', '"$1"', $content);

file_put_contents($file, $content);

echo "Fixed config file!\n";
