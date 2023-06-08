<?php

namespace Plugin\WPMedia;

class Helper
{
    
    public function render_html($template, $data = array()) {
        $output = NULL;
        if (file_exists($template)) {
            if($data){
                extract($data);
            }
            ob_start();
            include $template;
            $output = ob_get_clean();
        }
        return $output;
    }
}