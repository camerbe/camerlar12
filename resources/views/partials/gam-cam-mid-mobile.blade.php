<div id="div-gpt-ad-1793801008417-0"></div>

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
            /*Desktop (≥ 1024px)
            .addSize([1024, 0], [
                [300, 600]
            ])*/
            // Mobile / petits écrans (< 1024px)
            .addSize([0, 0], [
                [320, 480],
                [336, 280]
            ])
            .build();

        googletag.defineSlot(
            '/10680407/CAMER_MID_MOBILE',
            [
                [300, 600],
                [320, 480],
                [336, 280]
            ],
            'div-gpt-ad-1793801008417-0'
        )
            .defineSizeMapping(mapping)
            .addService(googletag.pubads());

        googletag.display('div-gpt-ad-1793801008417-0');
    });
</script>
