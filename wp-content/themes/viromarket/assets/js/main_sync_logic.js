    function fillSlider() {
        if (!$slider1.length || !$slider2.length) return;

        const val1 = parseInt($slider1.val());
        const val2 = parseInt($slider2.val());
        const min = parseInt($slider1.attr('min')) || 0;
        const max = parseInt($slider1.attr('max')) || 10000;
        
        const percent1 = ((val1 - min) / (max - min)) * 100;
        const percent2 = ((val2 - min) / (max - min)) * 100;

        $sliderTrack.css('background', `linear-gradient(to right, #e5e7eb ${percent1}%, #60d3b4 ${percent1}%, #60d3b4 ${percent2}%, #e5e7eb ${percent2}%)`);

        // Only update external inputs if they are not currently being focused/edited
        if (!$priceFrom.is(':focus')) $priceFrom.val(val1);
        if (!$priceTo.is(':focus')) $priceTo.val(val2);
    }

    if ($slider1.length && $slider2.length) {
        $slider1.on('input', fillSlider);
        $slider2.on('input', fillSlider);

        // Sync inputs back to sliders
        $priceFrom.on('input', function() {
            let val = parseInt($(this).val());
            if (!isNaN(val) && val >= 0 && val <= parseInt($slider2.val()) - minGap) {
                $slider1.val(val);
                fillSlider();
            }
        });

        $priceTo.on('input', function() {
            let val = parseInt($(this).val());
            let maxVal = parseInt($slider1.attr('max'));
            if (!isNaN(val) && val >= parseInt($slider1.val()) + minGap && val <= maxVal) {
                $slider2.val(val);
                fillSlider();
            }
        });

        fillSlider(); // Initial state
    }
