<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin &mdash; PT. Radika Bintang Nusantara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { primary: '#416423', 'primary-dark': '#2A421A', secondary: '#A5E17D' },
                fontFamily: { sans: ['Roboto', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
            } },
        };
    </script>
</head>
<body class="font-sans min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-dark via-primary to-[#1a2a10] p-4">
    <div class="w-full max-w-md">
        <div class="text-center text-white mb-8">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-white text-primary text-xl font-extrabold">RBN</div>
            <h1 class="mt-4 text-2xl font-bold">Admin Panel</h1>
            <p class="text-sm text-white/70">PT. Radika Bintang Nusantara</p>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-2xl">
            <h2 class="text-xl font-bold text-gray-800">Masuk</h2>
            <p class="mt-1 text-sm text-gray-500">Gunakan akun admin untuk mengelola produk.</p>

            <form method="POST" action="{{ route('admin.login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary @error('password') border-red-400 @enderror">
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
                    Ingat saya
                </label>

                <button type="submit" class="w-full rounded-lg bg-primary py-3 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                    Masuk
                </button>
            </form>

            <a href="{{ route('home') }}" class="mt-6 block text-center text-xs text-gray-500 hover:text-primary">&larr; Kembali ke website</a>
        </div>
    </div>
</body>
</html>
