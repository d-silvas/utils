<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

class Template {
    public $ci;
    public $css = array();
    public $js  = array();
    private $base_local_dir = "";

    public function __construct() {
        $this->ci =& get_instance();
        // Need local path for file_exists()
        $this->base_local_dir = getcwd();
    }

    public function css ($css = []) {
        if (!is_array($css)) $this->css = array($css);
        $this->css = array_map(array($this, "get_css_tag"), $this->css);
    }

    public function js ($js = []) {
        if (!is_array($js)) $this->js = array($js);
        $this->js = array_map(array($this, "get_js_tag"), $this->js);
    }

    public function load ($views = [], $data = null) {
        $page = isset($data["page"]) ? $data["page"] : "";
        $user_type = isset($data["user_type"]) ? $data["user_type"] : "normal";

        // Common css, js
        array_push($this->css, $this->get_css_tag(COMMON_CSS));
        array_push($this->js, $this->get_js_tag(COMMON_JS));

        // Add page CSS / JS if they exist
        if ($page !== "") {
            $page_css_file = $this->base_local_dir . "\\css\\pages\\" . $data["page"] . ".css";
            $page_js_file  = $this->base_local_dir . "\\js\\pages\\" . $data["page"] . ".js";
            if (file_exists($page_css_file)) array_push($this->css, $this->get_css_tag("pages/" . $data["page"] . ".css"));
            if (file_exists($page_js_file)) array_push($this->js, $this->get_js_tag("pages/" . $data["page"] . ".js"));
        }

        // Header - CSS
        $this->ci->load->view("header", array("css" => $this->css, "page" => $page, "user_type" => $user_type));
        
        // Views
        if (!is_array($views) || count($views) > 0) $views = array($views);
        foreach($views as $view) {
            $this->ci->load->view($view, $data);
        }

        // Footer - JS
        $this->ci->load->view("footer", array("js" => $this->js, "page" => $page, "user_type" => $user_type));
    }

    // These functions retrieve the full uri for the file
    private function add_css_path ($file_name) {
        return base_url("css/".$file_name);
    }
    private function add_js_path ($file_name) {
        return base_url("js/".$file_name);
    }

    // These functions build the corresponding HTML elements for css/js files, calling
    //  the previous functions to retrieve the full uri of the files
    private function get_css_tag ($file_name) {
        return "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . $this->add_css_path($file_name) . "\">";
    }
    private function get_js_tag ($file_name) {
        return "<script type=\"text/javascript\" src=\"" . $this->add_js_path($file_name) . "\"></script>";
    }
}

// CONTROLLER
// $this->template->css(HIGHCHARTS_CSS);
// $this->template->js([HIGHCHARTS_JS, "datatables.js"]);
// $this->template->load('vacancies', $data);

// VIEW (header / footer)
// <#php
// foreach($css as $css_tag):
//     echo $css_tag . "\n\t";
// endforeach;
// #>
// <#php
// foreach($js as $js_tag):
//     echo $js_tag . "\n\t";
// endforeach;
// #>