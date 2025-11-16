<?php
// functions.php
require_once __DIR__ . '/config.php';
session_start();

// ================================
// 1️⃣ CEK LOGIN + REMEMBER ME
// ================================
function is_logged_in(): bool {
    // Jika session ada → langsung OK
    if (!empty($_SESSION['logged']) && $_SESSION['logged'] === true) {
        return true;
    }

    // Jika ada cookie remember me
    if (!empty($_COOKIE[COOKIE_NAME])) {

        $val = $_COOKIE[COOKIE_NAME];

        // Format cookie: username:hash
        [$user, $hash] = array_pad(explode(':', $val, 2), 2, '');

        // Validasi username
        if ($user !== AUTH_USER) return false;

        // Hash yang seharusnya
        $validHash = hash('sha256', AUTH_USER . ':' . AUTH_PASS);

        // Bandingkan hash cookie dengan hash server
        if (hash_equals($validHash, $hash)) {
            // Auto login: buat session
            $_SESSION['logged'] = true;
            return true;
        }
    }

    return false;
}

// =================================
// 2️⃣ REDIRECT JIKA BELUM LOGIN
// =================================
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// =================================
// 3️⃣ SIMPAN COOKIE REMEMBER ME
// =================================
function set_remember_me() {
    $hash = hash('sha256', AUTH_USER . ':' . AUTH_PASS);
    $value = AUTH_USER . ':' . $hash;

    // Set cookie 30 hari
    setcookie(
        COOKIE_NAME,
        $value,
        time() + (86400 * 30),
        '/',
        '',
        false,
        true
    );
}

// =================================
// 4️⃣ HAPUS COOKIE REMEMBER ME
// =================================
function clear_remember_me() {
    setcookie(COOKIE_NAME, '', time() - 3600, '/');
}

// =================================
// 5️⃣ LOAD CONTACTS
// =================================
function load_contacts(): array {
    if (!file_exists(DATA_FILE)) {
        file_put_contents(DATA_FILE, json_encode([]));
    }
    $json = file_get_contents(DATA_FILE);
    $data = json_decode($json, true);
    if (!is_array($data)) $data = [];
    return $data;
}

// =================================
// 6️⃣ SIMPAN CONTACTS
// =================================
function save_contacts(array $contacts): bool {
    $json = json_encode(array_values($contacts), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return (bool) file_put_contents(DATA_FILE, $json, LOCK_EX);
}

// =================================
// 7️⃣ AMBIL KONTAK BY ID
// =================================
function get_contact_by_id(string $id) {
    $contacts = load_contacts();
    foreach ($contacts as $c) {
        if (isset($c['id']) && $c['id'] === $id) return $c;
    }
    return null;
}

// =================================
// 8️⃣ VALIDASI DATA KONTAK
// =================================
function validate_contact_data(array $data): array {
    $errors = [];

    // Nama
    $name = trim($data['name'] ?? '');
    if ($name === '') $errors['name'] = 'Nama harus diisi.';
    elseif (mb_strlen($name) < 2) $errors['name'] = 'Nama minimal 2 karakter.';

    // Email (opsional)
    $email = trim($data['email'] ?? '');
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Format email tidak valid.';

    // Telepon
    $phone = trim($data['phone'] ?? '');
    if ($phone === '') $errors['phone'] = 'Nomor telepon harus diisi.';
    elseif (!preg_match('/^[0-9+\-\s]{4,20}$/', $phone))
        $errors['phone'] = 'Nomor telepon tidak valid.';

    // Alamat (opsional)
    $addr = trim($data['address'] ?? '');
    if (mb_strlen($addr) > 500)
        $errors['address'] = 'Alamat terlalu panjang.';

    return $errors;
}

// =================================
// 9️⃣ GENERATE ID UNIK
// =================================
function generate_id(): string {
    return bin2hex(random_bytes(8)) . '-' . time();
}
