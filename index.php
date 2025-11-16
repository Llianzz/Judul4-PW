<?php
// index.php
require_once __DIR__ . '/functions.php';
require_login();

$contacts = load_contacts();
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Kontak</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen">

 <!-- NAVBAR -->
<header class="bg-white shadow-sm">
    <div class="container mx-auto px-8">
        <div class="flex items-center justify-between py-4">
            
            <!-- LOGO + text dikasih gap -->
            <div class="flex items-center space-x-4">
                <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-lg">+</span>
                </div>
                <span class="text-xl font-semibold text-red-700">Redbook</span>
            </div>

            <!-- NAVIGATION -->
            <nav class="flex items-center space-x-6">
                <a href="index.php" class="text-red-700 hover:text-red-900">Contact</a>
                <a href="form.php" class="bg-gradient-to-r from-red-600 to-red-800 text-white px-4 py-2 rounded-lg hover:opacity-90">
                    Add Contact
                </a>
                <a href="logout.php" class="border border-red-400 text-red-600 px-4 py-2 rounded-lg hover:bg-red-50">
                    Log Out
                </a>
            </nav>
        </div>
    </div>
</header>

<!-- HERO / LANDING -->
<section class="bg-gradient-to-b from-red-700 to-red-600 text-white py-32">
    <div class="container mx-auto px-8 text-center">
        <h1 class="text-8xl font-extrabold mb-4">
            Expand Your Connections
        </h1>

        <p class="text-lg opacity-90 mb-6">
            Discover a seamless way to store, organize, and grow your digital connections.
        </p>

        <a href="contacts.php"
           class="bg-white text-red-700 px-6 py-3 rounded-lg font-semibold hover:bg-red-100">
            Let's Begin
        </a>
    </div>
</section>


  <!-- DAFTAR KONTAK -->
  <main id="kontak" class="p-6 max-w-5xl mx-auto mt-20 mb-20">
    <!-- ALERT SUCCESS -->
  <?php if (isset($_GET['success']) && $_GET['success'] === 'added'): ?>
    <div class="mb-6 p-4 rounded-xl text-center font-semibold 
                bg-red-50 border border-red-300 text-red-700 
                shadow-lg">
      Contact successfully added!
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
    <div class="mb-6 p-4 rounded-xl text-center font-semibold 
                bg-red-50 border border-red-300 text-red-700 
                shadow-lg">
      Contact successfully updated!
    </div>
  <?php endif; ?>

    <!-- Ambient Red Glow Box -->
    <div class="relative">
      <div class="absolute -inset-2 bg-red-400 blur-2xl opacity-30 rounded-3xl"></div>

      <div class="relative bg-white rounded-2xl shadow-xl p-6  text-center">
        <h3 class="text-2xl font-bold text-red-700 mb-6">Contact Book</h3>

        <?php if (empty($contacts)): ?>
          <div class="text-gray-500">Belum ada kontak. 
            <a href="form.php" class="text-red-600 font-medium">Tambah sekarang</a>.
          </div>

        <?php else: ?>
          <div class="overflow-x-auto rounded-xl border border-red-100 shadow">
            <table class="min-w-full text-sm">
              <thead class="bg-red-50 text-red-700 font-semibold">
                <tr>
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Email</th>
                  <th class="px-4 py-3">Number</th>
                  <th class="px-4 py-3">Address</th>
                  <th class="px-4 py-3">Action</th>
                </tr>
              </thead>

              <tbody class="divide-y">
                <?php foreach($contacts as $c): ?>
                  <tr class="hover:bg-red-50/40 transition">
                    <td class="px-4 py-3 font-medium"><?=htmlspecialchars($c['name'])?></td>
                    <td class="px-4 py-3"><?=htmlspecialchars($c['email'] ?? '-')?></td>
                    <td class="px-4 py-3"><?=htmlspecialchars($c['phone'])?></td>
                    <td class="px-4 py-3"><?=nl2br(htmlspecialchars($c['address'] ?? '-'))?></td>

                    <td class="px-4 py-3 flex gap-2">
                      <a href="form.php?id=<?=urlencode($c['id'])?>" 
                        class="text-xs px-3 py-1 rounded-lg border border-red-400 text-red-700 hover:bg-red-100">
                        Edit
                      </a>

                      <a href="delete.php?id=<?=urlencode($c['id'])?>" 
                         onclick="return confirm('Hapus kontak ini?');" 
                         class="text-xs px-3 py-1 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        Delete
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>

            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

</body>
</html>
