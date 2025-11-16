<?php
// delete.php
require_once __DIR__ . '/functions.php';
require_login();

$id = $_GET['id'] ?? '';
if ($id === '') {
    header('Location: index.php');
    exit;
}
$contacts = load_contacts();
$found = false;
foreach ($contacts as $i => $c) {
    if (isset($c['id']) && $c['id'] === $id) {
        array_splice($contacts, $i, 1);
        $found = true;
        break;
    }
}
if ($found) save_contacts($contacts);
header('Location: index.php');
exit;
