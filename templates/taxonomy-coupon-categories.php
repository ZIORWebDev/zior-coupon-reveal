<?php
get_header();
 ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        $template_id       = get_option( 'zior_couponreveal_category_page_id' );
        $category_template = get_post( $template_id );
        echo wp_kses_post( apply_filters( 'the_content', $category_template->post_content ),  );
        ?>
    </main>
</div>
<?php
get_footer();