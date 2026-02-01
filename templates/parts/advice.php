<?php $advice = wp_kses_post(
    sprintf(
        /* translators: %1$s = first lens, %2$s = optional second lens (including 'of' and unit) */
        __('Het advies is om een %1$s%2$s objectief te gebruiken.', 'lens-calculator'),
        '<strong><span id="advice-calc-0"></span>mm</strong>',
        '<span class="advice-sep"> of <strong><span id="advice-calc-1"></span>mm</strong></span>'
    )
); ?>
<?php if ( $advice ) : ?>
<footer id="wpcl-advice">
    <div id="wpcl-advice-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <!--!Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2025 Fonticons, Inc.-->
            <path opacity=".4"
                d="M564.8 259.9l-57.2-25.15-153.78 96.11 150.68 66.25a18.74 18.74 0 0 0 24.69-9.61l45.22-102.91a18.74 18.74 0 0 0-9.61-24.69zM137.81 400H64v-32a16 16 0 0 0-16-16H16a16 16 0 0 0-16 16v128a16 16 0 0 0 16 16h32a16 16 0 0 0 16-16v-32h96a32 32 0 0 0 30-20.77l46.85-124.94-59.17-24.51z" />
            <path
                d="M4.23 183L99.57 16.13a32 32 0 0 1 39.67-13.84l378.2 166.29c24.33 9.73 27.29 43 5.08 56.85l-189.29 118.3a32 32 0 0 1-27.89 2.94L21.08 228.93A32 32 0 0 1 4.23 183z" />
        </svg></div>
    <div id="wpcl-advice-text">
        <p>
            <small><?php echo $advice; ?></small>
        </p>
    </div>
</footer>
<?php endif; ?>