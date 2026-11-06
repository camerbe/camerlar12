<div class="pb-3">
    <div id="div-gpt-ad-1793799610062-0" style="min-height:250px"></div>
</div>


<script>
    googletag.cmd.push(function () {
        googletag.pubads().setTargeting('section', @json($section));
        googletag.pubads().setTargeting('lang', @json($lang));
        googletag.pubads().setTargeting('pagetype', @json($pagetype));

        const mapping = googletag.sizeMapping()
            // Desktop (≥ 1024px)
            .addSize([1024, 0], [
                [300, 600],
                [300, 250],
                [336, 280]
            ])
            // Mobile / petits écrans (≥ 375px)
            .addSize([375, 0], [
                [300, 250],
                [336, 280]
            ])
            // Très petits écrans (< 375px)
            .addSize([0, 0], [
                [300, 250]
            ])
            .build();

        googletag.defineSlot(
            '/10680407/CAM_MID',
            [
                [300, 600],
                [300, 250],
                [336, 280]
            ],
            'div-gpt-ad-1793799610062-0'
        )
            .defineSizeMapping(mapping)
            .addService(googletag.pubads());

        // Lazy loading : doit être appelé AVANT enableServices()
        googletag.pubads().enableLazyLoad({
            fetchMarginPercent: 200,   // lance la requête à 2 viewports de distance
            renderMarginPercent: 100,  // affiche la pub à 1 viewport de distance
            mobileScaling: 2.0         // marges doublées sur mobile
        });

        googletag.enableServices(); // une seule fois par page
        googletag.display('div-gpt-ad-1793799610062-0');
    });
</script>
