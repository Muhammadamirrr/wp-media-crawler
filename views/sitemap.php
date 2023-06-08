<?php
/*
 * Template Name: Sitemap Page
 */
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="entry-content">
            <?php
            $uploads_dir = wp_upload_dir();
            $sitemap_url = $uploads_dir['baseurl'] . '/sitemap.html';

            echo '<iframe src="' . esc_url($sitemap_url) . '" frameborder="0" width="100%" height="600"></iframe>';
            ?>
        </div>
    </main>
</div>

<?php
get_footer();
?>