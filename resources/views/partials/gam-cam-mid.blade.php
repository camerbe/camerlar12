<div id="div-gpt-ad-1793799610062-0"></div>

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
            // Desktop (≥ 1024px)
            .addSize([1024, 0], [
                [300, 600],
                [336, 280]
            ])
            // Mobile / petits écrans (< 1024px)
            .addSize([375, 0], [

                [336, 280]
            ])
            
            .build();

        googletag.defineSlot(
            '/10680407/CAM_MID',
            [
                [300, 600],
                [300, 350],
                [336, 280]
            ],
            'div-gpt-ad-1793799610062-0'
        )
            .defineSizeMapping(mapping)
            .addService(googletag.pubads());

        googletag.display('div-gpt-ad-1793799610062-0');
    });
</script>
