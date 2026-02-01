<?php $disclaimer = apply_filters( 'wpcl_disclaimer_text', wp_kses_post(
    sprintf(
        __('%1$sDisclaimer:%2$s De resultaten van deze lenscalculator zijn %3$suitsluitend bedoeld ter informatie%4$s. De berekeningen zijn gebaseerd op theoretische formules en standaard sensorafmetingen. De werkelijke prestaties kunnen variëren afhankelijk van omgevingsfactoren, lensontwerp, camera-instellingen en andere factoren. De aanbevelingen moeten worden beschouwd als %5$sindicatieve richtlijnen%6$s en niet als garantie voor specifieke resultaten. Test en controleer altijd de prestaties van de lens in praktijksituaties voordat u aankoop- of gebruiksbeslissingen neemt.', 'lens-calculator'),
        '<strong>', '</strong>',
        '<strong>', '</strong>',
        '<strong>', '</strong>'
    )
    ));
?>
<?php if ( $disclaimer ) : ?>
<footer id="wpcl-disclaimer">
    <div id="wpcl-disclaimer-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <!--!Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2025 Fonticons, Inc.-->
            <path opacity=".4"
                d="M569.52 440L329.58 24c-18.44-32-64.69-32-83.16 0L6.48 440c-18.42 31.94 4.64 72 41.57 72h479.89c36.87 0 60.06-40 41.58-72zM288 448a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm38.24-238.41l-12.8 128A16 16 0 0 1 297.52 352h-19a16 16 0 0 1-15.92-14.41l-12.8-128A16 16 0 0 1 265.68 192h44.64a16 16 0 0 1 15.92 17.59z" />
            <path
                d="M310.32 192h-44.64a16 16 0 0 0-15.92 17.59l12.8 128A16 16 0 0 0 278.48 352h19a16 16 0 0 0 15.92-14.41l12.8-128A16 16 0 0 0 310.32 192zM288 384a32 32 0 1 0 32 32 32 32 0 0 0-32-32z" />
        </svg>
    </div>
    <div id="wpcl-disclaimer-text">
        <p><small><?php echo $disclaimer; ?></small></p>
    </div>
</footer>
<?php endif; ?>