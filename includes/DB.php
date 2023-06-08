<?php

namespace Plugin\WPMedia;

class DB
{
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'seo_links';
    }

    public function pluginActivate(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            url VARCHAR(255) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function plugindeactivate(){

        global $wpdb;

        $sql = "TRUNCATE TABLE $this->table_name";
        $wpdb->query($sql);
    }

    public function pluginuninstall(){

        global $wpdb;

        $sql = "DROP TABLE IF EXISTS $this->table_name";
        $wpdb->query($sql);
    }

    public function getSeoLinks(){

        global $wpdb;
        $result = $wpdb->get_results ("select * FROM $this->table_name");
        return $result;
    }

    public function truncateTable(){

        global $wpdb;

        $sql = "TRUNCATE TABLE $this->table_name";
        $wpdb->query($sql);
    }

    public function store_results($results){

        global $wpdb;
        $wpdb->show_errors();
        $data = array();
        foreach ($results as $result) {
            $data = array(
                'url' => $result,
            );
            $wpdb->insert($this->table_name, $data);
        }
    }
}