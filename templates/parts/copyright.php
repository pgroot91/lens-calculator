<?php $copyright = apply_filters( 'wpcl_footer_text', 
    wp_kses_post(
        sprintf(
            __('De %1$sCCTV Lens Calculator%2$s is ontwikkeld door %3$sPatrick Groot%4$s', 'lens-calculator'),
            '<a href="https://wordpress.org/plugins/lens-calculator/" target="_blank">', '</a>',
            '<a href="https://github.com/pgroot91">', '</a>',
        )
    ) 
); ?>
<?php if ( $copyright ) : ?>
<small class="wpcl-copyright">
    <?php echo $copyright; ?>
</small>
<?php endif; ?>