<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="appHtml" class="{{ session('darkMode', 'false') === 'true' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900 text-black dark:text-white">
@include('layouts.partials.header')

<div class="container mx-auto py-8 px-4">
    @yield('content')
</div>

@include('layouts.partials.footer')

<script>
    function toggleTheme() {
        const appHtml = document.getElementById('appHtml');
        const isDarkMode = appHtml.classList.toggle('dark');
        localStorage.setItem('darkMode', isDarkMode ? 'true' : 'false');

        const themeButton = document.getElementById('theme-toggle-icon');
        themeButton.textContent = isDarkMode ? '🌙' : '🌞';
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem('darkMode') === 'true') {
            document.getElementById('appHtml').classList.add('dark');
            document.getElementById('theme-toggle-icon').textContent = '🌙';
        }
    });
</script>
</body>
</html>
