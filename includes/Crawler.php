<?php

namespace Plugin\WPMedia;

use Plugin\WPMedia\DB;
use Plugin\WPMedia\Helper;

class Crawler
{
    private $home_url;

    public function __construct() {
        $this->home_url = home_url();
    }
    public function getList(){
        $dbObj = new DB();
        $list = $dbObj->getSeoLinks();

        $renderer = new Helper();
        $html = $renderer->render_html(plugin_dir_path( __FILE__ ) .'../views/admin-list.html', $list);
        echo $html;
    }
    public function scheduleCrawl(){
        wp_schedule_event(time(), 'hourly', 'wp_media_crawl_event');
    }


    public function crawl(){

        $dbObj = new DB();

        $start_url = $this->home_url;

        $dbObj->truncateTable();

        $results = array();

        $this->crawl_page($start_url, $results);

        $dbObj->store_results($results);

        $this->getList();

        $this->scheduleCrawl();

        $this->create_sitemap_html($results);
    }

    private function crawl_page($url, &$results) {
        $html = file_get_contents($url);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);

        $dom->loadHTML($html);

        $anchors = $dom->getElementsByTagName('a');
        foreach ($anchors as $anchor) {
            $href = $anchor->getAttribute('href');
            if ($href == "" || strpos($href, home_url()) === false) {
                continue;
            }
            $results[] = $href;
        }
    }

    function create_sitemap_html($crawl_results) {
        $sitemap_path = WP_CONTENT_DIR . '/sitemap.html';
        if (file_exists($sitemap_path)) {
            unlink($sitemap_path);
        }
        $sitemap_content = $this->generate_sitemap_html($crawl_results);

        $sitemap_path = WP_CONTENT_DIR . '/sitemap.html';
        file_put_contents($sitemap_path, $sitemap_content);
    }
    
    function generate_sitemap_html($crawl_results) {
        $sitemap_content = '<ul>';

        foreach ($crawl_results as $result) {
            $sitemap_content .= '<li><a href="' . esc_url($result) . '">' . esc_html($result) . '</a></li>';
        }
    
        $sitemap_content .= '</ul>';
    
        $html_content = '<!DOCTYPE html>
        <html>
        <head>
            <title>Sitemap</title>
        </head>
        <body>
            <h1>Sitemap</h1>
            ' . $sitemap_content . '
        </body>
        </html>';
    
        return $html_content;
    }

}