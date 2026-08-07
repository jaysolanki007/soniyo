<!DOCTYPE html>
<html lang="en" class="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login · SoNiYo</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/soniyo-emblem.svg') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<style>body{font-family:'Jost',sans-serif}.serif{font-family:'Cormorant Garamond',serif}</style>
</head>
<body class="min-h-screen bg-[#0a0908] text-stone-200 flex items-center justify-center p-6"
      style="background-image:radial-gradient(circle at 30% 20%,rgba(212,175,55,.08),transparent 40%),radial-gradient(circle at 80% 80%,rgba(212,175,55,.06),transparent 45%)">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <img src="{{ asset('assets/soniyo-logo.png') }}" onerror="this.onerror=null;this.src='{{ asset('assets/soniyo-emblem.svg') }}'" class="h-28 w-auto mx-auto mb-3" alt="SoNiYo">
      <p class="text-[11px] tracking-[0.35em] uppercase text-stone-500">Salon Management Suite</p>
    </div>

    <div class="bg-gradient-to-b from-[#1c1813] to-[#13100c] border border-gold/20 rounded-2xl p-8 shadow-2xl">
      <h1 class="serif text-3xl text-stone-100 mb-1">Welcome back</h1>
      <p class="text-sm text-stone-500 mb-6">Sign in to your admin dashboard.</p>

      @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-[11px] uppercase tracking-[0.16em] text-gold/80 mb-2">Email or Username</label>
          <input type="text" name="login" value="{{ old('login') }}" required autofocus
                 class="w-full px-4 py-3 rounded-lg bg-[#0e0c0a] border border-white/10 focus:border-gold focus:outline-none text-stone-100"
                 placeholder="admin@soniyo.com">
        </div>
        <div>
          <label class="block text-[11px] uppercase tracking-[0.16em] text-gold/80 mb-2">Password</label>
          <input type="password" name="password" required
                 class="w-full px-4 py-3 rounded-lg bg-[#0e0c0a] border border-white/10 focus:border-gold focus:outline-none text-stone-100"
                 placeholder="••••••••">
        </div>
        <label class="flex items-center gap-2 text-sm text-stone-400">
          <input type="checkbox" name="remember" class="rounded bg-transparent border-white/20 text-gold focus:ring-gold"> Remember me
        </label>
        <button type="submit"
                class="w-full py-3 rounded-lg font-medium tracking-[0.15em] uppercase text-sm text-ink-900 bg-gradient-to-r from-gold-soft via-gold to-gold-deep hover:opacity-90 transition">
          Sign In
        </button>
      </form>
    </div>

    <p class="text-center text-xs text-stone-600 mt-6">Demo: admin@soniyo.com / password</p>
  </div>
</body>
</html>
