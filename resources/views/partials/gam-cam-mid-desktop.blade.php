
<style>
    .pub-mid { width:300px; max-width:100%; margin:16px auto; min-height:250px; }
    @media (min-width: 768px) {
        .pub-mid { float:right; margin:4px 0 12px 20px; }
    }
    @media (min-width: 1024px) {
        .pub-mid { min-height:600px; }
    }
    .pub-mid iframe { width:auto !important; aspect-ratio:auto !important; margin:0 !important; border-radius:0 !important; }
</style>
<div class="pub-mid">
    <div id="div-gpt-ad-1793793364353-0"></div>
</div>
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
            ///Desktop (≥ 1024px)
            .addSize([1024, 0], [
                [300, 600],
                [300, 250],
                [336, 280]
            ])
            .addSize([0, 0], [])
            .build();

    googletag.defineSlot(
        '/10680407/CAM_MID_DESKTOP',
        [
            [300, 600],
            [300, 250],
            [336, 280]
        ],
        'div-gpt-ad-1793793364353-0'
    )
    .defineSizeMapping(mapping)
    .addService(googletag.pubads());
    googletag.pubads().addEventListener('slotRenderEnded', function (e) {
        if (e.slot === slot && e.isEmpty) {
                document.getElementById('wrap-gpt-1793793364353').style.display = 'none';
        }
    });

    googletag.display('div-gpt-ad-1793793364353-0');
    });
</script>
