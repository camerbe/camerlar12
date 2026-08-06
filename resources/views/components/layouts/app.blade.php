@props([
    'title' => config('app.name'),
    'description' => '',
    'image' => '',
    'image_width' => '300',
    'image_height' => '300',
    'image_type' => '',
    'keyword' => '',
    'modified_time' => '',
    'published_time' => '',
    'section' => '',
    'author' => '',
    'source' => '',
    'publisher' => '',
    'canonical' => '',
    'georegion' => '',
    'geoplacename' => '',
    'hashtags' => [],
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns:livewire="http://www.w3.org/1999/html">--}}
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="max-image-preview:large">
        <x-meta
            :title="$title ?? config('app.name')"
            :description="$description ?? ''"
            :image="$image ?? ''"
            :image_width="$image_width?? '300'"
            :image_height="$image_height?? '300'"
            :image_type="$image_type?? '300'"
            :keyword="$keyword?? ''"
            :modified_time="$modified_time?? ''"
            :published_time="$published_time?? ''"
            :section="$section?? ''"
            :author="$author?? ''"
            :source="$source?? ''"
            :publisher="$publisher?? ''"
            :canonical="$canonical?? ''"
            :georegion="$georegion?? ''"
            :geoplacename="$geoplacename?? ''"
            :hashtags="$hashtags ?? []"
        />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <!-- Polices Google -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://flagcdn.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">


        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.onPageExpired((response, message) => {
                    // Force le rafraîchissement pour recréer un jeton CSRF valide
                    window.location.reload();
                });
            });
        </script>

    </head>
    <body class="bg-gray-50 text-gray-900 dark:bg-dark-bg dark:text-gray-100 font-sans antialiased transition-colors duration-200">

        <livewire:header/>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            {{ $slot }}
        </main>
        <livewire:footer/>

        @livewireScripts
        @fluxScripts
    </body>
</html>
