<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Savoire | {{ $title ?? null }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-trix-styles />
</head>

<body class="h-screen bg-gray-50 overflow-y-auto scrollbar-hide">
    <nav class="bg-white border-b snap-start">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-900">
                        Savoire
                    </a>
                </div>
    
                <!-- Navigation Links -->
                <div class="flex items-center space-x-4">
                    <a href="/create" class="px-3 py-2 rounded-md text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        Create
                    </a>
                    <a href="/copy-cat" class="px-3 py-2 rounded-md text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        Copy Cat
                    </a>
                    @auth
                        <a href="{{ route('history') }}" class="text-gray-600 hover:text-gray-900">History</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-600 hover:text-gray-900">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div x-show="open" 
                                @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1"
                                style="display: none;">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <div class="p-4 md:p-6 lg:p-8">
        <div class="max-w-4xl mx-auto">
            {{ $content ?? null }}
        </div>
    </div>
</body>

</html>
