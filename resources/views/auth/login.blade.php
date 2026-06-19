<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Admin Comiquería Aguilar</title>
    <meta name="description" content="Panel de administración de Comiquería Aguilar">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-dot-pattern {
            background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <!-- Dot pattern -->
    <div class="bg-dot-pattern absolute inset-0 opacity-50"></div>
    <!-- Red glow top-right -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-sm relative z-10">

        <!-- Brand mark -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-11 h-11 bg-red-600 rounded-xl flex items-center justify-center shadow-md mb-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 class="text-lg font-black text-gray-900 tracking-tight">Comiquería <span class="text-red-600">Aguilar</span></h1>
            <p class="text-[10px] text-gray-400 uppercase tracking-[0.18em] font-semibold mt-1">Panel de Administración</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h2 class="text-base font-bold text-gray-900 mb-1">Bienvenido</h2>
            <p class="text-xs text-gray-400 mb-7">Ingresa tus credenciales para continuar</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <p class="text-xs text-red-700 font-semibold">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">
                        Correo Electrónico
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" required autofocus
                           placeholder="admin@comiqueria.com"
                           class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/10 focus:bg-white transition-all">
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">
                        Contraseña
                    </label>
                    <input type="password" id="password" name="password"
                           required placeholder="••••••••"
                           class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/10 focus:bg-white transition-all">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember"
                               class="w-3.5 h-3.5 rounded border-gray-300 accent-red-600">
                        <span class="text-xs text-gray-500 select-none">Recordarme</span>
                    </label>
                </div>

                <button type="submit" id="login-button"
                        class="w-full py-3 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold text-sm rounded-xl transition-all shadow-sm shadow-red-600/20 active:scale-[0.99]">
                    Ingresar al Panel
                </button>
            </form>
        </div>

        <p class="text-center text-[10px] text-gray-400 uppercase tracking-widest font-semibold mt-6">
            © {{ date('Y') }} Comiquería Aguilar
        </p>
    </div>
</body>
</html>
