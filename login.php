    <?php
    // login.php
    require_once __DIR__ . '/functions.php';

    $err = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';
        $remember = !empty($_POST['remember']);

        if ($user === AUTH_USER && $pass === AUTH_PASS) {
            $_SESSION['logged'] = true;
            if ($remember) {
                $hash = hash('sha256', AUTH_USER . ':' . AUTH_PASS);
                setcookie(COOKIE_NAME, AUTH_USER . ':' . $hash, time() + COOKIE_LIFETIME, '/');
            }
            header('Location: index.php');
            exit;
        } else {
            $err = 'Username atau password salah.';
        }
    }
    ?>
    <!doctype html>
    <html lang="id">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Redbook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-white relative flex items-center justify-center overflow-hidden">

    <!-- BACKGROUND RED GRADIENT ORBS -->
    <div class="absolute w-[450px] h-[450px] bg-red-400 rounded-full blur-[140px] opacity-30 -top-20 -left-20"></div>
    <div class="absolute w-[500px] h-[500px] bg-red-600 rounded-full blur-[180px] opacity-20 bottom-0 right-0"></div>

    <!-- CARD WRAPPER WITH AMBIENT GLOW -->
    <div class="relative">
        <!-- Ambient Glow -->
        <div class="absolute -inset-4 bg-gradient-to-br from-red-300 via-red-400 to-red-600 blur-3xl opacity-30 rounded-3xl"></div>

        <!-- CARD -->
        <div class="relative w-full max-w-lg p-10 rounded-3xl shadow-2xl bg-white/90 backdrop-blur-md border border-red-100">
        
        <h1 class="text-3xl font-bold mb-6 text-center" style="color:#b91c1c;">
            Login Redbook
        </h1>

        <?php if ($err): ?>
            <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-5 text-sm">
            <?=htmlspecialchars($err)?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5">
            
            <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input 
                name="username" 
                required 
                class="block w-full rounded-xl border px-4 py-3 shadow-sm focus:ring-red-500 focus:border-red-500"
            />
            </div>

            <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input 
                type="password" 
                name="password" 
                required 
                class="block w-full rounded-xl border px-4 py-3 shadow-sm focus:ring-red-500 focus:border-red-500"
            />
            </div>

            <div class="flex items-center justify-between">
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="mr-2 accent-red-600" />
                <span class="text-sm">Remember Me</span>
            </label>
            <a href="#" class="text-sm text-red-600 hover:underline">Forgot password?</a>
            </div>

            <button 
            type="submit" 
            class="w-full py-3 rounded-xl text-white font-semibold shadow-lg hover:opacity-90"
            style="background: linear-gradient(90deg,#ef4444,#b91c1c);"
            >
            Masuk
            </button>
        </form>

        <p class="mt-6 text-xs text-gray-500 text-center">
            Use username <strong><?=htmlspecialchars(AUTH_USER)?></strong> 
            and password has been reset.
        </p>
        </div>
    </div>

    </body>

    </html>
