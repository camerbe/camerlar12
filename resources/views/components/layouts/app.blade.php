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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://flagcdn.com" crossorigin>
        <link rel="preconnect" href="https://www.youtube.com">
        <link rel="preconnect" href="https://i.ytimg.com">
        <link rel="preconnect" href="//images.taboola.com" crossorigin="">
        <link rel="preconnect" href="//cdn.taboola.com" crossorigin="">
        <link rel="preconnect" href="//trc.taboola.com" crossorigin="">
        <link rel="preconnect" href="https://camer-be.disqus.com">

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

        <!-- =====================================================
        6. CONSENT MODE + GA4
        ===================================================== -->

        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('consent', 'default', {
                analytics_storage: 'denied',
                ad_storage: 'denied',
                ad_user_data: 'denied',
                ad_personalization: 'denied',
                wait_for_update: 500
            });

            gtag('js', new Date());

            gtag('config', 'G-K905N6XENX', {
                send_page_view: true
            });
        </script>

        <script async
                src="https://www.googletagmanager.com/gtag/js?id=G-K905N6XENX">
        </script>
        <!-- InMobi Choice. Consent Manager Tag v3.0 (for TCF 2.3) -->
        <script type="text/javascript" async=true>
            (function() {
                var host = "www.themoneytizer.com";
                var element = document.createElement('script');
                var firstScript = document.getElementsByTagName('script')[0];
                var url = 'https://cmp.inmobi.com'
                    .concat('/choice/', '6Fv0cGNfc_bw8', '/', host, '/choice.js?tag_version=V3');
                var uspTries = 0;
                var uspTriesLimit = 3;
                element.async = true;
                element.type = 'text/javascript';
                element.src = url;

                firstScript.parentNode.insertBefore(element, firstScript);

                function makeStub() {
                    var TCF_LOCATOR_NAME = '__tcfapiLocator';
                    var queue = [];
                    var win = window;
                    var cmpFrame;

                    function addFrame() {
                        var doc = win.document;
                        var otherCMP = !!(win.frames[TCF_LOCATOR_NAME]);

                        if (!otherCMP) {
                            if (doc.body) {
                                var iframe = doc.createElement('iframe');

                                iframe.style.cssText = 'display:none';
                                iframe.name = TCF_LOCATOR_NAME;
                                doc.body.appendChild(iframe);
                            } else {
                                setTimeout(addFrame, 5);
                            }
                        }
                        return !otherCMP;
                    }

                    function tcfAPIHandler() {
                        var gdprApplies;
                        var args = arguments;

                        if (!args.length) {
                            return queue;
                        } else if (args[0] === 'setGdprApplies') {
                            if (
                                args.length > 3 &&
                                args[2] === 2 &&
                                typeof args[3] === 'boolean'
                            ) {
                                gdprApplies = args[3];
                                if (typeof args[2] === 'function') {
                                    args[2]('set', true);
                                }
                            }
                        } else if (args[0] === 'ping') {
                            var retr = {
                                gdprApplies: gdprApplies,
                                cmpLoaded: false,
                                cmpStatus: 'stub'
                            };

                            if (typeof args[2] === 'function') {
                                args[2](retr);
                            }
                        } else {
                            if(args[0] === 'init' && typeof args[3] === 'object') {
                                args[3] = Object.assign(args[3], { tag_version: 'V3' });
                            }
                            queue.push(args);
                        }
                    }

                    function postMessageEventHandler(event) {
                        var msgIsString = typeof event.data === 'string';
                        var json = {};

                        try {
                            if (msgIsString) {
                                json = JSON.parse(event.data);
                            } else {
                                json = event.data;
                            }
                        } catch (ignore) {}

                        var payload = json.__tcfapiCall;

                        if (payload) {
                            window.__tcfapi(
                                payload.command,
                                payload.version,
                                function(retValue, success) {
                                    var returnMsg = {
                                        __tcfapiReturn: {
                                            returnValue: retValue,
                                            success: success,
                                            callId: payload.callId
                                        }
                                    };
                                    if (msgIsString) {
                                        returnMsg = JSON.stringify(returnMsg);
                                    }
                                    if (event && event.source && event.source.postMessage) {
                                        event.source.postMessage(returnMsg, '*');
                                    }
                                },
                                payload.parameter
                            );
                        }
                    }

                    while (win) {
                        try {
                            if (win.frames[TCF_LOCATOR_NAME]) {
                                cmpFrame = win;
                                break;
                            }
                        } catch (ignore) {}

                        if (win === window.top) {
                            break;
                        }
                        win = win.parent;
                    }
                    if (!cmpFrame) {
                        addFrame();
                        win.__tcfapi = tcfAPIHandler;
                        win.addEventListener('message', postMessageEventHandler, false);
                    }
                };

                makeStub();

                var uspStubFunction = function() {
                    var arg = arguments;
                    if (typeof window.__uspapi !== uspStubFunction) {
                        setTimeout(function() {
                            if (typeof window.__uspapi !== 'undefined') {
                                window.__uspapi.apply(window.__uspapi, arg);
                            }
                        }, 500);
                    }
                };

                var checkIfUspIsReady = function() {
                    uspTries++;
                    if (window.__uspapi === uspStubFunction && uspTries < uspTriesLimit) {
                        console.warn('USP is not accessible');
                    } else {
                        clearInterval(uspInterval);
                    }
                };

                if (typeof window.__uspapi === 'undefined') {
                    window.__uspapi = uspStubFunction;
                    var uspInterval = setInterval(checkIfUspIsReady, 6000);
                }
            })();
        </script>
        <!-- End InMobi Choice. Consent Manager Tag v3.0 (for TCF 2.3) -->

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance

        {{-- =========================================================
        14. GOOGLE ANALYTICS
        CHARGEMENT APRÈS LE CHEMIN CRITIQUE
        ========================================================= --}}

        <script
            async
            src="https://www.googletagmanager.com/gtag/js?id=G-K905N6XENX">
        </script>

        {{-- =========================================================
        15. ADSENSE
        SCRIPT ASYNCHRONE ET NON BLOQUANT
        ========================================================= --}}

        <script
            async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('analytics.ca-pub') }}"
            crossorigin="anonymous">
        </script>
        <script id="dsq-count-scr" src="https://camer-be.disqus.com/count.js" async="async"></script>
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
                <div class="w-full max-w-[970px] h-[90px] sm:h-[250px] bg-gray-200 dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center overflow-hidden shadow-sm">
                    <livewire:banner :banner="$banner"/>
                </div>
            </div>
        </div>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>

        <livewire:footer :archives="$archives"/>

        @livewireScripts
        @fluxScripts
    </body>
</html>
