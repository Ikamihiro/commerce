<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav x-data="{ open: false }">
            <div class="bg-white text-gray-700 border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-28">
                    <div class="flex justify-between items-center">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <div class="hidden sm:flex items-center">
                            <a href="{{ route('landing') }}"
                                class="block ps-3 pe-4 py-2 block ps-3 pe-4 py-2 @if (request()->routeIs('dashboard')) text-blue-500 @endif">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.courses.index') }}"
                                class="block ps-3 pe-4 py-2 block ps-3 pe-4 py-2 @if (request()->routeIs('admin.courses.index')) text-blue-500 @endif">
                                Cursos
                            </a>
                            <a href="{{ route('admin.modules.index') }}"
                                class="block ps-3 pe-4 py-2 block ps-3 pe-4 py-2 @if (request()->routeIs('admin.modules.index')) text-blue-500 @endif">
                                Módulos
                            </a>
                            <a href="{{ route('admin.orders.index') }}"
                                class="block ps-3 pe-4 py-2 block ps-3 pe-4 py-2 @if (request()->routeIs('admin.orders.index')) text-blue-500 @endif">
                                Pedidos
                            </a>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="open = ! open"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1 border-t border-gray-100">
                        <a href="{{ route('dashboard') }}"
                            class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2 @if (request()->routeIs('dashboard')) text-blue-500 @endif">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.courses.index') }}"
                            class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2 @if (request()->routeIs('admin.courses.index')) text-blue-500 @endif">
                            Cursos
                        </a>
                        <a href="{{ route('admin.modules.index') }}"
                            class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2 @if (request()->routeIs('admin.modules.index')) text-blue-500 @endif">
                            Módulos
                        </a>
                        <a href="{{ route('admin.orders.index') }}"
                            class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2 @if (request()->routeIs('admin.orders.index')) text-blue-500 @endif">
                            Pedidos
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
