<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Aria Training')); ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <!-- Styles / Scripts -->
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <!-- Fallback styles if vite is not running (dev only) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Instrument Sans', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    <?php endif; ?>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-zinc-950 dark:text-zinc-100 antialiased transition-colors duration-300">

    <!-- Navbar -->
    <nav class="w-full py-6 px-6 lg:px-12 flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <!-- Logo Icon -->
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">A</div>
            <span class="font-bold text-xl tracking-tight">Aria Training</span>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="toggleTheme()" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-zinc-800 transition-colors" aria-label="Toggle Theme">
                <!-- Sun Icon -->
                <svg id="sun-icon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <!-- Moon Icon (Classic Crescent) -->
                <svg id="moon-icon" class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 24 24"><path d="M21.4,13.7C20.6,13.9,19.8,14,19,14c-5,0-9-4-9-9c0-0.8,0.1-1.6,0.3-2.4c-4.8,0.9-8.3,5.2-8.3,10.3C2,18.6,6.4,23,11.9,23c5.1,0,9.4-3.5,10.3-8.3C21.9,14.4,21.6,14.1,21.4,13.7z"/></svg>
            </button>
            <?php if(Route::has('login')): ?>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(url('/dashboard')); ?>" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors ml-2">
                            Cerrar Sesión
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Iniciar Sesión</a>
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="hidden sm:inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-indigo-500/20">
                            Registrarse
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content: Bento Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6 auto-rows-[minmax(180px,auto)]">
            
            <!-- Hero Card (Span 2x2) -->
            <!-- Hero Card (Span 2x2) -->
            <div class="col-span-1 md:col-span-3 lg:col-span-2 row-span-2 bg-white dark:bg-zinc-900 rounded-3xl p-8 lg:p-12 flex flex-col justify-center items-start shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-zinc-800 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -mr-16 -mt-16 transition-all duration-700 group-hover:bg-indigo-500/20"></div>
                
                <div class="relative z-10">
                    <h1 class="text-4xl lg:text-6xl font-bold tracking-tight mb-6 text-gray-900 dark:text-white leading-tight">
                        Conecta. <br>
                        <span class="text-indigo-600 dark:text-indigo-500">Entrena.</span> <br>
                        Evoluciona.
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-zinc-400 mb-8 max-w-md leading-relaxed">
                        Gestión profesional para entrenadores. Resultados claros para atletas.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="<?php echo e(route('register')); ?>" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:-translate-y-0.5">
                            Empezar Ahora
                        </a>
                        <a href="#features" class="px-8 py-3.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-900 dark:text-white font-semibold rounded-xl transition-all">
                            Saber más
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 1: Centralización -->
            <!-- Card 1: Centralización -->
            <div class="col-span-1 md:col-span-3 lg:col-span-2 bg-indigo-600 rounded-3xl p-8 flex flex-col justify-between text-white shadow-lg shadow-indigo-900/20 relative overflow-hidden group transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-600/30">
                <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                </div>
                <div>
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Adiós al Excel</h3>
                    <p class="text-indigo-100">Olvídate de archivos dispersos. Todo tu ecosistema fitness centralizado en una sola plataforma robusta.</p>
                </div>
            </div>

            <!-- Card 2: Entrenadores -->
            <!-- Card 2: Entrenadores -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Entrenadores</h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400">Diseña. Asigna. Supervisa. Ahorra horas de planificación semanal.</p>
            </div>

            <!-- Card 3: Atletas -->
            <!-- Card 3: Atletas -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Atletas</h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400">Tu rutina en el bolsillo. Claridad total sobre lo que toca entrenar hoy.</p>
            </div>

            <!-- Card 4: Administradores -->
            <!-- Card 4: Administradores -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Gimnasios</h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400">Gestión integral de staff, equipamiento y métricas de sede.</p>
            </div>

            <!-- Card 5: Reportes -->
            <!-- Card 5: Reportes -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 bg-zinc-900 dark:bg-zinc-800 rounded-3xl p-8 flex flex-col justify-between relative overflow-hidden group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10">
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Reportes</h3>
                    <p class="text-sm text-zinc-400">Métricas detalladas para decisiones inteligentes.</p>
                </div>
                <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-blue-600/10 to-transparent"></div>
            </div>

            <!-- Card 6: Seguridad (New) -->
            <!-- Card 6: Seguridad (New) -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Seguridad</h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400">Tus datos protegidos con los más altos estándares.</p>
            </div>

            <!-- Card 7: Multi-dispositivo (New) -->
            <!-- Card 7: Multi-dispositivo (New) -->
            <div class="col-span-1 md:col-span-1 lg:col-span-1 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-indigo-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Acceso Total</h3>
                <p class="text-sm text-gray-500 dark:text-zinc-400">Desde cualquier lugar y dispositivo, sin límites.</p>
            </div>
        </div>
    </main>

    <!-- FAQ & CTA Section -->
    <section class="max-w-7xl mx-auto px-6 py-12 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-start">
            
            <!-- Left: FAQ -->
            <div x-data="{ active: null }">
                <h2 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">Preguntas Frecuentes</h2>
                <div class="space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
                        <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left flex justify-between items-center bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                            <span class="font-medium text-gray-900 dark:text-white">¿Es gratis para los atletas?</span>
                            <svg class="w-5 h-5 transform transition-transform duration-200" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="active === 1" x-collapse class="bg-gray-50 dark:bg-zinc-900/50 px-6 py-4 text-gray-600 dark:text-zinc-400 text-sm border-t border-gray-200 dark:border-zinc-800">
                            Sí, la cuenta de atleta es completamente gratuita. Solo necesitas que tu entrenador te invite o registrarte para buscar uno.
                        </div>
                    </div>
                    <!-- FAQ Item 2 -->
                    <div class="border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
                        <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left flex justify-between items-center bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                            <span class="font-medium text-gray-900 dark:text-white">¿Tienen aplicación móvil?</span>
                            <svg class="w-5 h-5 transform transition-transform duration-200" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="active === 2" x-collapse class="bg-gray-50 dark:bg-zinc-900/50 px-6 py-4 text-gray-600 dark:text-zinc-400 text-sm border-t border-gray-200 dark:border-zinc-800">
                            Actualmente somos una Web App optimizada (PWA), por lo que puedes instalarla en tu inicio y funciona como una app nativa, sin ocupar espacio extra.
                        </div>
                    </div>
                    <!-- FAQ Item 3 -->
                    <div class="border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
                        <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left flex justify-between items-center bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                            <span class="font-medium text-gray-900 dark:text-white">¿Puedo gestionar varios gimnasios?</span>
                            <svg class="w-5 h-5 transform transition-transform duration-200" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="active === 3" x-collapse class="bg-gray-50 dark:bg-zinc-900/50 px-6 py-4 text-gray-600 dark:text-zinc-400 text-sm border-t border-gray-200 dark:border-zinc-800">
                            Absolutamente. Nuestra cuenta de Administrador permite la gestión multi-sede con reportes unificados.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: CTA Block -->
            <div class="bg-indigo-600 rounded-3xl p-8 lg:p-12 text-white shadow-2xl shadow-indigo-900/20 relative overflow-hidden transform transition-all duration-500 hover:scale-[1.02]">
                <div class="relative z-10">
                    <h3 class="text-3xl font-bold mb-4">¿Listo para empezar?</h3>
                    <p class="text-indigo-100 mb-8 text-lg">Únete a cientos de entrenadores que ya han optimizado su tiempo y resultados.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="<?php echo e(route('register')); ?>" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-colors text-center">
                            Crear Cuenta Gratis
                        </a>
                        <a href="#" class="px-6 py-3 bg-indigo-700 text-white font-medium rounded-xl hover:bg-indigo-800 transition-colors text-center border border-indigo-500">
                            Contactar Ventas
                        </a>
                    </div>
                </div>
                <!-- Decorative Circles -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-indigo-900/20 rounded-full blur-3xl"></div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-6 py-8 border-t border-gray-200 dark:border-zinc-800 mt-12">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left whitespace-nowrap">
                <p class="text-sm text-gray-500 dark:text-zinc-500">© <?php echo e(date('Y')); ?> Aria Training. Todos los derechos reservados.</p>
            </div>
            <div class="flex gap-6 justify-center">
                <a href="#" class="text-sm text-gray-500 hover:text-gray-900 dark:text-zinc-500 dark:hover:text-white transition-colors">Privacidad</a>
                <a href="#" class="text-sm text-gray-500 hover:text-gray-900 dark:text-zinc-500 dark:hover:text-white transition-colors">Términos</a>
                <a href="#" class="text-sm text-gray-500 hover:text-gray-900 dark:text-zinc-500 dark:hover:text-white transition-colors">Contacto</a>
            </div>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Check local storage or system preference on load
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            // Default to dark as requested
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\aria-training\resources\views/welcome.blade.php ENDPATH**/ ?>