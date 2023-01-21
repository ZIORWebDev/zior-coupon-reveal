<?php
get_header();
 ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        $template_id    = get_option( 'zior_couponreveal_store_page_id' );
        $store_template = get_post( $template_id );
        echo apply_filters( 'the_content', $store_template->post_content );
        ?>
    </main>
</div>
<?php
get_footer();