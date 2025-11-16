<?php
// form.php
require_once __DIR__ . '/functions.php';
require_login();

$errors = [];
$old = ['name'=>'','email'=>'','phone'=>'','address'=>''];
$editing = false;
$id = $_GET['id'] ?? null;

if ($id) {
    $contact = get_contact_by_id($id);
    if (!$contact) {
        header('Location: index.php');
        exit;
    }
    $editing = true;
    $old = array_merge($old, $contact);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
    ];
    $errors = validate_contact_data($old);
    if (empty($errors)) {
        $contacts = load_contacts();
        if (!empty($_POST['id'])) {
            // edit
            foreach ($contacts as &$c) {
                if ($c['id'] === $_POST['id']) {
                    $c['name'] = $old['name'];
                    $c['email'] = $old['email'];
                    $c['phone'] = $old['phone'];
                    $c['address'] = $old['address'];
                    break;
                }
            }
            save_contacts($contacts);
            header('Location: index.php?success=updated');
            exit;
        } else {
            // tambah
            $new = [
                'id' => generate_id(),
                'name' => $old['name'],
                'email' => $old['email'],
                'phone' => $old['phone'],
                'address' => $old['address'],
                'created_at' => date('c')
            ];
            $contacts[] = $new;
            save_contacts($contacts);
            header('Location: index.php?success=added');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $editing ? 'Edit Kontak' : 'Tambah Kontak' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center py-20">

  <div class="relative max-w-3xl w-full px-6">

   <div class="absolute w-[450px] h-[450px] bg-red-400 rounded-full blur-[140px] opacity-30 -top-20 -left-20"></div>
  <div class="absolute w-[500px] h-[500px] bg-red-600 rounded-full blur-[180px] opacity-20 bottom-0 right-0"></div>

    <!-- Ambient Glow -->
    <div class="absolute -inset-3 bg-gradient-to-r from-red-500 to-red-700 blur-2xl opacity-30 rounded-3xl"></div>

    <!-- FORM CARD -->
    <div class="relative bg-white rounded-3xl shadow-xl p-10 border border-red-200">

      <!-- TOMBOL KEMBALI -->
      <a href="index.php" 
         class="inline-block mb-6 px-4 py-2 bg-red-50 border border-red-300 text-red-700 rounded-xl hover:bg-red-100 transition">
         ← Back
      </a>

      <h2 class="text-3xl font-bold mb-6 text-red-700 text-center">
        <?= $editing ? 'Edit Contact' : 'Add New Contact' ?>
      </h2>

      <?php if (!empty($errors)): ?>
        <div class="mb-6">
          <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-xl">
            <ul class="list-disc pl-5 space-y-1">
              <?php foreach($errors as $e): ?>
                <li><?=htmlspecialchars($e)?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-5" onsubmit="return clientValidate();">
        <?php if ($editing): ?>
          <input type="hidden" name="id" value="<?=htmlspecialchars($old['id'])?>" />
        <?php endif; ?>

        <!-- INPUTS -->
        <div>
          <label class="block text-sm font-semibold mb-1">Name *</label>
          <input name="name" id="name" 
                 value="<?=htmlspecialchars($old['name'])?>" 
                 required 
                 class="block w-full rounded-xl border px-4 py-2.5" />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1">Email</label>
          <input name="email" type="email" id="email" 
                 value="<?=htmlspecialchars($old['email'])?>" 
                 class="block w-full rounded-xl border px-4 py-2.5" />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1">Number *</label>
          <input name="phone" id="phone" required
                 value="<?=htmlspecialchars($old['phone'])?>" 
                 class="block w-full rounded-xl border px-4 py-2.5" />
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1">Address</label>
          <textarea name="address" id="address" rows="4"
                    class="block w-full rounded-xl border px-4 py-2.5"><?=htmlspecialchars($old['address'])?></textarea>
        </div>

        <!-- BUTTONS -->
        <div class="flex gap-4 pt-4 justify-end">
  <button type="submit" 
          class="px-5 py-3 rounded-xl text-white font-semibold 
                 bg-gradient-to-r from-red-600 to-red-800 hover:opacity-90 transition">
    <?= $editing ? 'Save Changes' : 'Add Contact' ?>
  </button>

  <a href="index.php" 
     class="px-5 py-3 rounded-xl border border-gray-300 bg-gray-50 hover:bg-gray-100 transition">
     Cancel
  </a>
</div>


      </form>
    </div>
  </div>

</body>

</html>
