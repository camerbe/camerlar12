<div id="div-gpt-ad-1793753581942-0" style="min-height:250px"></div>

<script>
    googletag.cmd.push(function () {
        googletag.pubads().setTargeting('section', @json($section));
        googletag.pubads().setTargeting('lang', @json($lang));
        googletag.pubads().setTargeting('pagetype', @json($pagetype));

        const mapping = googletag.sizeMapping()
            // Desktop (≥ 1024px)
            .addSize([1024, 0], [
                [300, 250],
                [300, 600],
                [160, 600]
            ])
            // Tablette (768-1023px) : ajout du 300×600 si la colonne est assez haute
            .addSize([768, 0], [
                [300, 250],
                [300, 600]
            ])
            // Mobile : sidebar masquée
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
