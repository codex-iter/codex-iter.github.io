<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    class DoradoWebConfig {
        public static $instance; 

        public $prefix = null;          
        public $wd_plugin_id = null;          
        public $plugin_title = null;          
        public $plugin_wordpress_slug = null;          
        public $plugin_dir = null;                   
        public $plugin_main_file = null;          
        public $description = null;                   
        public $plugin_features = null;          
        public $user_guide = null;          
        public $overview_welcome_image = null;          
        public $video_youtube_id = null;          
        public $plugin_wd_url = null;          
        public $plugin_wd_demo_link = null;                  
        public $plugin_wd_addons_link = null;          
        public $plugin_wizard_link = null;          
        public $after_subscribe = null;          
        public $plugin_menu_title = null;          
        public $plugin_menu_icon = null;
        public $wd_dir = null;
        public $wd_dir_includes = null;
        public $wd_dir_templates = null;
        public $wd_dir_assets = null;
        public $wd_url_css = null;
        public $wd_url_js = null;
        public $wd_url_img = null;
        public $deactivate = null;
        public $subscribe = null;
        public $custom_post = null;
        public $menu_capability = null;
        public $menu_position = null;
        public $start_using_url = null;

        public function set_options( $options ){

            if(isset( $options["prefix"] )) {
                $this->prefix = $options["prefix"];
            }
            if(isset( $options["wd_plugin_id"] )) {
                $this->wd_plugin_id =  $options["wd_plugin_id"];
            }
            if(isset( $options["plugin_title"] )) {
                $this->plugin_title =  $options["plugin_title"];
            }  
            if(isset( $options["plugin_wordpress_slug"] )) {
                $this->plugin_wordpress_slug =  $options["plugin_wordpress_slug"];
            } 
            if(isset( $options["plugin_dir"] )) {
                $this->plugin_dir =  $options["plugin_dir"];
            }
            if(isset( $options["plugin_main_file"] )) {
                $this->plugin_main_file =  $options["plugin_main_file"];
            }           
            
            if(isset( $options["description"] )) {
                $this->description =  $options["description"];
            } 
            if(isset( $options["plugin_features"] )) {
                $this->plugin_features =  $options["plugin_features"];
            } 
            if(isset( $options["user_guide"] )) {
                $this->user_guide =  $options["user_guide"];
            } 
            if(isset( $options["video_youtube_id"] )) {
                $this->video_youtube_id =  $options["video_youtube_id"];
            } 
            if(isset( $options["overview_welcome_image"] )) {
                $this->overview_welcome_image =  $options["overview_welcome_image"];
            }            
            if(isset( $options["plugin_wd_url"] )) {
                $this->plugin_wd_url =  $options["plugin_wd_url"];
            } 
            if(isset( $options["plugin_wd_demo_link"] )) {
                $this->plugin_wd_demo_link =  $options["plugin_wd_demo_link"];
            }             
            if(isset( $options["plugin_wd_addons_link"] )) {
                $this->plugin_wd_addons_link =  $options["plugin_wd_addons_link"];
            }
            if(isset( $options["plugin_wizard_link"] )) {
                $this->plugin_wizard_link =  $options["plugin_wizard_link"];
            } 
            if(isset( $options["after_subscribe"] )) {
                $this->after_subscribe =  $options["after_subscribe"];
            } 
            if(isset( $options["plugin_menu_title"] )) {
                $this->plugin_menu_title =  $options["plugin_menu_title"];
            } 
            if(isset( $options["plugin_menu_icon"] )) {
                $this->plugin_menu_icon =  $options["plugin_menu_icon"];
            } 
            if(isset( $options["deactivate"] )) {
                $this->deactivate =  $options["deactivate"];
            } 
            if(isset( $options["subscribe"] )) {
                $this->subscribe =  $options["subscribe"];
            }
            if(isset( $options["custom_post"] )) {
                $this->custom_post =  $options["custom_post"];
            }
            if(isset( $options["menu_capability"] )) {
                $this->menu_capability =  $options["menu_capability"];
            } 
            if(isset( $options["menu_position"] )) {
                $this->menu_position =  $options["menu_position"];
            }
            if(isset( $options["start_using_url"] )) {
                $this->start_using_url =  $options["start_using_url"];
            }

            // directories
            $this->wd_dir = dirname( $this->plugin_main_file ) . '/wd'; 
            $this->wd_dir_includes = $this->wd_dir . '/includes'; 
            $this->wd_dir_templates = $this->wd_dir . '/templates'; 
            $this->wd_dir_assets = $this->wd_dir . '/assets'; 
            $this->wd_url_css = plugins_url( plugin_basename( $this->wd_dir ) ) . '/assets/css'; 
            $this->wd_url_js = plugins_url( plugin_basename( $this->wd_dir ) ) .  '/assets/js'; 
            $this->wd_url_img = plugins_url( plugin_basename( $this->wd_dir ) ) .  '/assets/img';
        }


    }



