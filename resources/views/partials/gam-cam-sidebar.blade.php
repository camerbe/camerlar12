<div id="div-gpt-ad-1793753581942-0" class="bg-white dark:bg-dark-surface p-6 rounded-xl border border-gray-100 dark:border-dark-border shadow-sm"></div>

<script>
    googletag.cmd.push(function () {
        googletag.pubads().setTargeting(
            'section', @json($section)
        );
        googletag.pubads().setTargeting(
            'lang', @json($lang)
        );

        googletag.pubads().setTargeting(
            'pagetype', @json($pagetype)
        );
        const mapping = googletag.sizeMapping()
            .addSize([1024, 0], [
                [300, 250],
                [300, 600],
                [160, 600]
            ])
            .addSize([768, 0], [
                [300, 250]
            ])
            .addSize([0, 0], [])
            .build();

        googletag.defineSlot(
            '/10680407/CAM_SIDEBAR',
            [
                [300, 250],
                [300, 600],
                [160, 600]
            ],
            'div-gpt-ad-1793753581942-0'
        )
            .defineSizeMapping(mapping)
            .addService(googletag.pubads());


        googletag.display('div-gpt-ad-1793753581942-0');
    });
</script>
