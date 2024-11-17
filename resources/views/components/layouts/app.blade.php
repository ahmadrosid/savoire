<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Savoire' }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite([
        'resources/css/app.css', 
        'resources/js/app.js',
        'resources/js/mingle.svelte.js'
    ])
    <x-trix-styles />
</head>

<body class="h-screen bg-gray-50 overflow-y-auto scrollbar-hide">
    <nav class="bg-white border-b snap-start">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-900">
                        Savoire
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex space-x-4">
                    @if (auth()->user()->is_admin)
                        <a href="/admin/templates"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                            Templates
                        </a>
                    @endif
                    <a href="/create"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        Create
                    </a>
                    <a href="/copy-cat"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        Copy Cat
                    </a>
                    <a href="/history"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                        History
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="p-4 md:p-6 lg:p-8">
        <div class="max-w-4xl mx-auto">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
