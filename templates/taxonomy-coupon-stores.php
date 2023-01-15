<?php
get_header();
 ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        echo apply_filters( 'the_content', '
        <!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"coupons","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":null},"namespace":"zior/coupons"} -->
        <div class="wp-block-query"><!-- wp:post-template -->
        <!-- wp:post-featured-image {"pull_featured_image_from_stores":true} /-->

        <!-- wp:post-title /-->

        <!-- wp:post-content /-->
        <!-- /wp:post-template -->

        <!-- wp:query-pagination -->
        <!-- wp:query-pagination-previous /-->

        <!-- wp:query-pagination-numbers /-->

        <!-- wp:query-pagination-next /-->
        <!-- /wp:query-pagination -->

        <!-- wp:query-no-results -->
        <!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
        <!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
        </div>
        <!-- /wp:query -->');
        ?>
    </main>
</div>
<?php
get_footer();