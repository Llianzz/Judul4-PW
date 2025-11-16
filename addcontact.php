<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$contactsFile = "data.json";
$contacts = json_decode(file_get_contents($contactsFile), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $telepon = trim($_POST["telepon"]);

    if ($nama == "") $errors[] = "Nama tidak boleh kosong";
    if (!preg_match('/^[0-9]+$/', $telepon)) $errors[] = "Nomor telepon harus angka";

    if (empty($errors)) {
        $contacts[] = ["nama" => $nama, "telepon" => $telepon];
        file_put_contents($contactsFile, json_encode($contacts, JSON_PRETTY_PRINT));
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kontak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-red-700 to-yellow-400 min-h-screen">

<div class="max-w-lg mx-auto mt-10 bg-white/20 backdrop-blur-lg p-8 rounded-xl shadow-xl border border-yellow-400">

    <h2 class="text-2xl font-bold text-yellow-300 mb-5">Tambah Kontak</h2>

    <?php foreach ($errors as $e): ?>
        <p class="text-red-900 mb-2"><?php echo $e; ?></p>
    <?php endforeach; ?>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama"
            class="w-full px-4 py-2 mb-3 border border-yellow-400 bg-white/40 rounded-lg">

        <input type="text" name="telepon" placeholder="Telepon"
            class="w-full px-4 py-2 mb-3 border border-yellow-400 bg-white/40 rounded-lg">

        <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg">
            Simpan
        </button>
    </form>

</div>
</body>
</html>
