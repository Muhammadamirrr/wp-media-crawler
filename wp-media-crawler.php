<?php
/**
 * @package WP Media Crawl
 */
/*
Plugin Name: WP Media Crawl
Plugin URI: https://wp-media.me
Description: The Website Crawler and SEO Analyzer plugin for administrators to analyze their website's internal linking structure and improve their SEO rankings.
Version: 1.0
License: GPLv2 or laters
Author: WP Media
Author URI: contact@wp-media.me
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use Plugin\WPMedia\DB;
use Plugin\WPMedia\Crawler;

add_action(
	'admin_menu',
	'wp_media_crawl',
	9, 0
);

function wp_media_crawl() {
	add_menu_page("Homepage Crawler", "Homepage Crawler", 0, "wp-media-list", "wp_media_render_admin_page");
    add_submenu_page("wp-media-list", "Crawl Now", "Crawl Now", 0, "wp_media_crawl_now", "wp_media_render_crawl_schedule");
}

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

function wp_media_activate() {
	$obj = new DB();
	$obj->pluginActivate();
}
register_activation_hook( __FILE__, 'wp_media_activate' );

function wp_media_deactivate() {
	$obj = new DB();
	$obj->plugindeactivate();
}
register_deactivation_hook( __FILE__, 'wp_media_deactivate' );

function wp_media_uninstall() {
	$obj = new DB();
	$obj->pluginuninstall();
}
register_uninstall_hook( __FILE__, 'wp_media_uninstall' );

function wp_media_render_admin_page() {
	$crawlerObj = new Crawler();
	$list = $crawlerObj->getList();
}

function wp_media_render_crawl_schedule() {
	$crawlerObj = new Crawler();
	$list = $crawlerObj->crawl();
}

function wp_media_crawl_event_callback() {
	$obj = new Crawler();
	$obj->crawl();
}
add_action('wp_media_crawl_event', 'wp_media_crawl_event_callback');

add_shortcode('sitemap', 'wp_media_display_sitemap');

function wp_media_display_sitemap($atts) {
    $sitemap_path = WP_CONTENT_DIR . '/sitemap.html';

    if (file_exists($sitemap_path)) {
        $sitemap_content = file_get_contents($sitemap_path);
        return $sitemap_content;
    }

    return 'Sitemap not found.';
}
