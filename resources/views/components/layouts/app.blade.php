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
    'ampcanonical' => '',
    'isJson4article' => false,
    'isJson4listItem' => false,
    'isVideo' => false,
    'rub' => '',
    'sousrub' => '',
    'breadcumbUrl' => '',
    'wordCount' => 0,
    'hit' => 0,
    'info'=>'',
    'jld'=>[],
    'listElements'=>[],
    'listItemVideos'=>[],
    'rssFeeds' => [],
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',


])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >

    <head>
        {{-- =========================================================
        1. ADSENSE
        SCRIPT ASYNCHRONE ET NON BLOQUANT
        ========================================================= --}}
        <script
            async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('analytics.ca-pub') }}"
            crossorigin="anonymous">
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="{{$robots}}">
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
            :ampcanonical="$ampcanonical"
            :isJson4article="$isJson4article ?? false"
            :isJson4listItem="$isJson4listItem ?? false"
            :isVideo="$isVideo ?? false"
            :rub="$rub"
            :sousrub="$sousrub"
            :breadcumbUrl="$breadcumbUrl"
            :wordCount="$wordCount"
            :hit="$hit"
            :info="$info"
            :jld="$jld"
            :listElements="$listElements"
            :listItemVideos="$listItemVideos"
        />

        <!-- Verif Bing -->
        <meta name="msvalidate.01" content="E86FAE75C1BC7CFCBB2EAAB5142E02CF" />
        <!-- Verif Yandex -->
        <meta name="yandex-verification" content="a20383ed17ecd649" />
        <meta property="fb:app_id" content="{{config('analytics.facebook-app-id')}}" />
        <meta property="article:publisher" content="https://www.facebook.com/camerbe-394597600634385/" />
        <meta name="verify-v1" content="MRadPlAbO06GhRhOMdGKSiCc1m0mUcejbeTJPGvopvc=" />
        <meta name="p:domain_verify" content="840f5095656ce6d0dc34abd7bd6c4d04"/>
        <meta name="google-site-verification" content="g8L53Ci5UcjypCM3idcDeMwfd98oHphUZ1amL0jCSXE" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <!-- Polices Google -->
        <link rel="dns-prefetch" href="//partner.googleadservices.com">
        <link rel="dns-prefetch" href="//tpc.googlesyndication.com">
        <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">
        <link rel='dns-prefetch' href='//www.googletagmanager.com' />
        <link rel='dns-prefetch' href='//fonts.googleapis.com' />

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://flagcdn.com" crossorigin>
        <link rel="preconnect" href="https://www.youtube.com">
        <link rel="preconnect" href="https://i.ytimg.com">
        <link rel="preconnect" href="//images.taboola.com" crossorigin="">
        <link rel="preconnect" href="//cdn.taboola.com" crossorigin="">
        <link rel="preconnect" href="//trc.taboola.com" crossorigin="">


        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
        <!-- ===================================================== -->
        <!-- FLUX RSS (Discovery)                                  -->
        <!-- ===================================================== -->
        @foreach($rssFeeds as $feed)
            <link rel="alternate" type="application/rss+xml" title="{{ $feed['title'] }}" href="{{ $feed['url'] }}">
        @endforeach
        <!-- =====================================================
        6. CONSENT MODE + GA4
        ===================================================== -->
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        @livewireStyles
        @fluxAppearance
        @vite(['resources/css/app.css', 'resources/js/app.js'])



        {{-- =========================================================
        14. GOOGLE ANALYTICS
        CHARGEMENT APRÈS LE CHEMIN CRITIQUE
        ========================================================= --}}

        <script
            async
            src="https://www.googletagmanager.com/gtag/js?id={{config('analytics.googletagmanager-id')}}">
        </script>



        {{-- TABOOLA--}}
        <script type="text/javascript">
            window._taboola = window._taboola || [];
            _taboola.push({article:'auto'});
            !function (e, f, u, i) {
                if (!document.getElementById(i)){
                    e.async = 1;
                    e.src = u;
                    e.id = i;
                    f.parentNode.insertBefore(e, f);
                }
            }(document.createElement('script'),
                document.getElementsByTagName('script')[0],
                '//cdn.taboola.com/libtrc/camerbelgique/loader.js',
                'tb_loader_script');
            if(window.performance && typeof window.performance.mark == 'function')
            {window.performance.mark('tbl_ic');}
        </script>

    </head>
    <body class="bg-gray-50 text-gray-900 dark:bg-dark-bg dark:text-gray-100 font-sans antialiased transition-colors duration-200">

        <livewire:header/>
        <!-- ========================================== -->
        <!-- EMPLACEMENT : GRANDE BANNIÈRE (Billboard)  -->
        <!-- ========================================== -->
        <div class="w-full bg-gray-100 dark:bg-dark-surface py-4 border-b border-gray-200 dark:border-dark-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
                <div class="w-full max-w-[970px] h-full sm:h-[250px] bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center overflow-hidden shadow-sm">
                    <livewire:banner :banner="$banner"/>
                    @include('partials.adsense-display')
                </div>
            </div>
        </div>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>

        <livewire:footer :archives="$archives"/>
        @include('partials.googletagmanager')


        @stack('scripts')
        @livewireScripts
        @fluxScripts
        <script>
            lucide.createIcons();

            // Ré-exécuter Lucide après chaque mise à jour du DOM par Livewire
            document.addEventListener('livewire:navigated', () => {
                lucide.createIcons();
            });

            document.addEventListener('livewire:init', () => {
                Livewire.hook('morph.updated', ({ el, component }) => {
                    lucide.createIcons();
                });
            });
        </script>


    </body>
</html>
