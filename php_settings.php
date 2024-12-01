<?php
echo "<h3>Loaded PHP Extensions:</h3>";
print_r(get_loaded_extensions());

echo "<h3>PHP Version:</h3>";
echo phpversion();

echo "<h3>Maximum Upload File Size:</h3>";
echo ini_get('upload_max_filesize');

echo "<h3>Maximum POST Size:</h3>";
echo ini_get('post_max_size');

echo "<h3>Execution Time Limit:</h3>";
echo ini_get('max_execution_time');

echo "<h3>Memory Limit:</h3>";
echo ini_get('memory_limit');
?>
