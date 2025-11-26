<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">Bienvenido, {{ Auth::user()->name }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Gestiona todo el ecosistema de Aria Training desde aquí.</p>
            </div>

            <!-- Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[minmax(180px,auto)]">
                
                <!-- Card: Usuarios (Emerald/Orange style) -->
                <a href="{{ route('admin.usuarios.index') }}" class="col-span-1 md:col-span-2 lg:col-span-2 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-orange-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/10 rounded-full blur-2xl -mr-8 -mt-8 transition-all duration-500 group-hover:bg-orange-500/20"></div>
                    
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Gestión de Usuarios</h3>
                            <p class="text-gray-500 dark:text-zinc-400">Administra atletas, entrenadores y administradores. Asigna roles y permisos.</p>
                        </div>
                        <div class="mt-4 flex items-center text-orange-600 dark:text-orange-400 font-medium">
                            <span>Acceder</span>
                            <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- Card: Ejercicios (Purple style) -->
                <a href="{{ route('admin.ejercicios.index') }}" class="col-span-1 md:col-span-1 lg:col-span-1 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-purple-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Ejercicios</h3>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Biblioteca de movimientos y videos.</p>
                </a>

                <!-- Card: Equipos (Blue style) -->
                <a href="{{ route('admin.equipos.index') }}" class="col-span-1 md:col-span-1 lg:col-span-1 bg-white dark:bg-zinc-900 rounded-3xl p-8 border border-gray-100 dark:border-zinc-800 hover:border-blue-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Equipos</h3>
                    <p class="text-sm text-gray-500 dark:text-zinc-400">Inventario de máquinas y material.</p>
                </a>

                <!-- Card: Auditoría (Red/Teal style - Using Teal for Audit) -->
                <a href="{{ route('admin.auditoria.index') }}" class="col-span-1 md:col-span-2 lg:col-span-4 bg-zinc-900 dark:bg-zinc-800 rounded-3xl p-8 flex flex-row items-center justify-between relative overflow-hidden group transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-500/10">
                    <div class="relative z-10 flex items-center gap-6">
                        <div class="w-12 h-12 bg-teal-500/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-1">Auditoría del Sistema</h3>
                            <p class="text-sm text-zinc-400">Registro detallado de seguridad y cambios.</p>
                        </div>
                    </div>
                    <div class="relative z-10">
                         <svg class="w-6 h-6 text-zinc-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-teal-600/10 to-transparent"></div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
