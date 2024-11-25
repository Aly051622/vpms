<?php
$key = base64_encode(random_bytes(32)); // Generate a secure 256-bit key
echo "Your encryption key: " . $key;
