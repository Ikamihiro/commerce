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
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])

    <x-embed-styles />
</head>

<body class="font-sans antialiased">
    <div class="relative min-h-screen bg-gray-100">
        <nav x-data="{ open: false }">
            <div class="bg-white text-gray-700 border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-28">
                    <div class="flex justify-between w-full">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('landing') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <!-- Redes Sociais -->
                        <div class="hidden sm:flex sm:items-center space-x-4">
                            <a href="https://www.facebook.com/faculdade.feics"
                                class="text-gray-500 hover:text-blue-500">
                                <i class="ri-facebook-circle-line ri-xl"></i>
                            </a>
                            <a href="https://www.instagram.com/faculdade.feics"
                                class="text-gray-500 hover:text-blue-500">
                                <i class="ri-instagram-line ri-xl"></i>
                            </a>
                            <a href="https://www.youtube.com/@faculdadefeics" class="text-gray-500 hover:text-blue-500">
                                <i class="ri-youtube-line ri-xl"></i>
                            </a>

                            <!-- Telefone -->
                            <a href="#" class="flex items-center space-x-2 text-gray-500 hover:text-blue-500">
                                <i class="ri-phone-line"></i>
                                <span class="text-sm">(65) 99659-0085</span>
                            </a>

                            <!-- Email -->
                            <a href="mailto:feics@feics.com.br"
                                class="flex items-center space-x-2 text-gray-500 hover:text-blue-500">
                                <i class="ri-mail-line"></i>
                                <span class="text-sm">feics@feics.com.br</span>
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
                        <a href="{{ route('landing') }}" class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2">
                            Página Inicial
                        </a>
                        <a href="{{ route('courses.index') }}" class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2">
                            Cursos
                        </a>
                        <a href="{{ route('contact.index') }}" class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2">
                            Contato
                        </a>
                        <a href="https://ava.floodlle.com"
                            class="block w-full ps-3 pe-4 py-2 block w-full ps-3 pe-4 py-2">
                            Espaço do Aluno
                        </a>
                    </div>
                </div>
            </div>
            <div class="hidden md:block bg-blue-500 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between w-full h-20">
                        <div class="space-x-8 -my-px ms-10 flex">
                            <a href="{{ route('landing') }}" class="inline-flex items-center px-1 pt-1">
                                Página Inicial
                            </a>
                            <a href="{{ route('courses.index') }}" class="inline-flex items-center px-1 pt-1">
                                Cursos
                            </a>
                            <a href="{{ route('contact.index') }}" class="inline-flex items-center px-1 pt-1">
                                Contato
                            </a>
                            <a href="https://ava.floodlle.com" class="inline-flex items-center px-1 pt-1">
                                Espaço do Aluno
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
