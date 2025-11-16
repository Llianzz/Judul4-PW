<?php
// config.php
// Username & password preset (ganti jika perlu)
define('AUTH_USER', 'admin');
define('AUTH_PASS', 'password123'); // ubah ini sesuai kebutuhan

// Cookie config
define('COOKIE_NAME', 'contact_app_auth');
define('COOKIE_LIFETIME', 60*60*24*7); // 7 hari

// File data
define('DATA_FILE', __DIR__ . '/contacts.json');
