<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Savoire</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-trix-styles />
</head>

<body class="h-screen bg-neutral-50 overflow-y-auto scrollbar-hide">
    <nav class="bg-white border-b snap-start">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-neutral-900">
                        Savoire
                    </a>
                </div>
    
                <!-- Navigation Links -->
                <div class="flex space-x-4">
                    <a href="/create" class="px-3 py-2 rounded-md text-sm font-medium text-neutral-900 hover:bg-neutral-100 hover:text-neutral-900 transition-colors">
                        Create
                    </a>
                    <a href="/copy-cat" class="px-3 py-2 rounded-md text-sm font-medium text-neutral-900 hover:bg-neutral-100 hover:text-neutral-900 transition-colors">
                        Copy Cat
                    </a>
                    <a href="/history" class="px-3 py-2 rounded-md text-sm font-medium text-neutral-900 hover:bg-neutral-100 hover:text-neutral-900 transition-colors">
                        History
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="p-4 md:p-6 lg:p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="space-y-2 mb-3">
                <h1 class="text-3xl font-bold">Create LinkedIn Post</h1>
                <p class="text-neutral-500">
                    Transform your ideas into engaging LinkedIn content with AI assistance
                </p>
            </div>
            <livewire:editor-page />
        </div>
    </div>
</body>

</html>
