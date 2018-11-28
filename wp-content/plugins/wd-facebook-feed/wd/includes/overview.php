<?php
    if ( !defined( 'ABSPATH' ) ) {
        exit;
    }

    class DoradoWebOverview{
        ////////////////////////////////////////////////////////////////////////////////////////
        // Events                                                                             //
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        // Constants                                                                          //
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        // Variables                                                                          //
        ////////////////////////////////////////////////////////////////////////////////////////
        public $config ; 
        private $tabs = array(); 
        
        ////////////////////////////////////////////////////////////////////////////////////////
        // Constructor & Destructor                                                           //
        ////////////////////////////////////////////////////////////////////////////////////////
        public function __construct( $config = array() ) {
            $this->config = $config; 
            $wd_options =  $this->config;        
            $this->tabs = array(
                'welcome' => array(
                    'name'    =>  __( "Welcome", $wd_options->prefix ),
                    'view'    => array( $this, 'wd_overview_welcome' )
                ),
                'user_guide' => array(
                    'name'    =>  __( "User Guide", $wd_options->prefix ),
                    'view'    => array( $this, 'wd_overview_user_guide' )
                ),
                'deals' => array(
                    'name'    =>  __( 'Deals', $wd_options->prefix ),
                    'view'    => array( $this, 'wd_overview_deals' )
                ),
                'support' => array(
                    'name'    =>  __( "Support", $wd_options->prefix ),
                    'view'    => array( $this, 'wd_overview_support' )
                ),
                'https://web-dorado.com/support/submit-your-idea.html' => array(
                    'name'    =>  __( "Submit Your  Idea", $wd_options->prefix ),
                    'view'    => false
                ),
                'https://wordpress.org/support/plugin/' . $wd_options->plugin_wordpress_slug => array(
                    'name'    =>  __( "Forum", $wd_options->prefix ),
                    'view'    => false
                )
            );
            
         

        }
        ////////////////////////////////////////////////////////////////////////////////////////
        // Public Methods                                                                     //
        ////////////////////////////////////////////////////////////////////////////////////////
        public function display_overview_page(){
            $wd_options =  $this->config; 
            $tabs = $this->tabs;
            $start_using_url = "";
            if(!empty($this->config->custom_post)) {
                if (strpos($this->config->custom_post, 'post_type', 0) !== false) {
                    $start_using_url = admin_url($this->config->custom_post);
                } else {
                    $start_using_url = menu_page_url($this->config->custom_post, false);
                }
            }
            if(!empty($this->config->start_using_url)){
              $start_using_url = $this->config->start_using_url;
            }
            require_once( $wd_options->wd_dir_templates . "/display_overview.php" );
        }
        public function wd_overview_welcome(){
            $wd_options =  $this->config; 
            //http://api.wordpress.org/plugins/info/1.0/wd-google-maps
            require_once( $wd_options->wd_dir_templates . "/display_overview_welcome.php" );
        } 
        public function wd_overview_user_guide(){
            $wd_options =  $this->config; 
            $user_guide = $wd_options->user_guide;
            require_once( $wd_options->wd_dir_templates . "/display_overview_user_guide.php" );
        }
        public function wd_overview_deals(){
           
            $wd_options =  $this->config; 
            $plugins = array(
                "form-maker" => array(
                  'title'    => 'Form Maker',
                  'text'     => __( 'Wordpress form builder plugin', $wd_options->prefix ),
                  'content'  => __( 'Form Maker is a modern and advanced tool for creating WordPress forms easily and fast.', $wd_options->prefix ),
                  'href'     => 'https://web-dorado.com/files/fromFormMaker.php'
                ),
                "photo-gallery" => array(
                  'title'    => 'Photo Gallery',
                  'text'     => __( 'WordPress Photo Gallery plugin', $wd_options->prefix ),
                  'content'  => __( 'Photo Gallery is a fully responsive WordPress Gallery plugin with advanced functionality.', $wd_options->prefix ),
                  'href'     => 'https://web-dorado.com/products/wordpress-photo-gallery-plugin.html'
                ),
                "event-calendar-wd" => array(
                  'title'    => 'Event Calendar WD',
                  'text'     => __( 'WordPress calendar plugin', $wd_options->prefix ),
                  'content'  => __( 'Organize and publish your events in an easy and elegant way using Event Calendar WD.', $wd_options->prefix ),
                  'href'     => 'https://web-dorado.com/products/wordpress-event-calendar-wd.html'
                ),
                "wd-google-maps" => array(
                  'title'    => 'WD Google Maps',
                  'text'     => __( 'WD Google Maps plugin', $wd_options->prefix ),
                  'content'  => __( 'Google Maps WD is an intuitive tool for creating Google maps with advanced markers, custom layers and overlays for   your website.', $wd_options->prefix ),
                  'href'     => 'https://web-dorado.com/products/wordpress-google-maps-plugin.html'
                ),            
                "slider-wd" => array(
                  'title'    => 'Slider WD',
                  'text'     => __( 'WordPress slider plugin', $wd_options->prefix ),
                  'content'  => __( 'Create responsive, highly configurable sliders with various effects for your WordPress site.', $wd_options->prefix ),
                  'href'     => 'https://web-dorado.com/products/wordpress-slider-plugin.html'
                ), 
                "spider-event-calendar" => array(
                  'title'    => 'Spider Calendar',
                  'text'     => __( 'WordPress event calendar plugin', $wd_options->prefix ),
                  'content'  => __( 'Spider Event Calendar is a highly configurable product which allows you to have multiple organized events.', $wd_options->prefix ),
                  'href'     => 'https://web-dorado.com/products/wordpress-calendar.html'
                ), 
                "wd-instagram-feed" => array(
                  'title'    => 'Instagram Feed WD',
                  'text'     => __( 'WordPress Instagram Feed plugin', $wd_options->prefix ),
                  'content'  => __( 'WD Instagram Feed is a user-friendly tool for displaying user or hashtag-based feeds on your website.', $wd_options->prefix ),
                  'href'     => 'https://web-dorado.com/products/wordpress-instagram-feed-wd.html'
                ),            
            );
            unset($plugins[$wd_options->plugin_wordpress_slug]) ; 
            
            // foreach ( $plugins as $wp_slug => &$plugin ){
                // $wp_data = $this->
            //remote_get($wp_slug);
                // $plugin["downloaded"] = $wp_data["downloaded"];
                // $plugin["rating"] = $wp_data["rating"];
            // } 
            
            $themes = array(
                 "business_elite" => array(
                  'title'    => 'Business Elite Theme',
                  'href'     => 'https://web-dorado.com/wordpress-themes/business-elite.html'
                ),
                "portfolio_gallery" => array(
                  'title'    => 'Portfolio Gallery Theme',
                  'href'     => 'https://web-dorado.com/wordpress-themes/portfolio-gallery.html'
                ),
                "sauron" => array(
                  'title'    => 'Sauron Theme',
                  'href'     => 'https://web-dorado.com/wordpress-themes/sauron.html'
                ),
                 "business_world" => array(
                  'title'    => 'Business World Theme',
                  'href'     => 'https://web-dorado.com/wordpress-themes/business-world.html'
                ),            
            );

            require_once( $wd_options->wd_dir_templates . "/display_overview_deals.php" );
        }
        public function wd_overview_support(){
            $wd_options =  $this->config; 
            global $wpdb;
            $server_info = array();

            // Get PHP Version
            $server_info["Operating System"] = PHP_OS . " (" . ( PHP_INT_SIZE * 8 ) . ")"; 
            $server_info["PHP Version"] = PHP_VERSION; 
            $server_info["Server"] = $_SERVER["SERVER_SOFTWARE"]; 

            // Get MYSQL Version
            $sql_version = $wpdb->get_var( "SELECT VERSION() AS version" );
            $server_info["MySQL Version"] = $sql_version;

            // GET SQL Mode
            $mysqlinfo = $wpdb->get_results( "SHOW VARIABLES LIKE 'sql_mode'" );
            if ( is_array( $mysqlinfo ) ) 
                $sql_mode = $mysqlinfo[0]->Value;
            if ( empty( $sql_mode ) ) 
                $sql_mode = __( 'Not set', $wd_options->prefix );
            $server_info["SQL Mode"] = $sql_mode;
                
            // Get PHP allow_url_fopen
            if( ini_get( 'allow_url_fopen' ) )  
                $allow_url_fopen = __( 'On', $wd_options->prefix );
            else 
                $allow_url_fopen = __( 'Off', $wd_options->prefix );
            $server_info["PHP Allow URL fopen"] = $allow_url_fopen;

            // Get PHP Max Upload Size
            if (function_exists('wp_max_upload_size')) 
                $upload_max = strval(round((int) wp_max_upload_size() / (1024 * 1024))) . 'M';
            else if(ini_get('upload_max_filesize')) 
                $upload_max = ini_get('upload_max_filesize');
            else 
                $upload_max = __('N/A', $wd_options->prefix);

            $server_info["PHP Max Upload Size"] = $upload_max;

            // Get PHP Output buffer Size
            if( ini_get( 'pcre.backtrack_limit' ) ) 
             $backtrack_limit = ini_get( 'pcre.backtrack_limit' );
            else 
                $backtrack_limit = __( 'N/A', $wd_options->prefix );
            $server_info["PCRE Backtracking Limit"] = $backtrack_limit;

            // Get PHP Max Post Size
            if( ini_get( 'post_max_size' ) ) 
                $post_max = ini_get( 'post_max_size' );
            else 
                $post_max = __( 'N/A', $wd_options->prefix );
            $server_info["PHP Max Post Size"] = $post_max;

            // Get PHP Max execution time
            if( ini_get( 'max_execution_time' ) ) 
                $max_execute = ini_get( 'max_execution_time' );
            else 
                $max_execute = __( 'N/A', $wd_options->prefix );
            $server_info["PHP Max Script Execute Time"] = $max_execute;


            // Get PHP Memory Limit
            if( ini_get( 'memory_limit' ) ) 
                $memory_limit = ini_get( 'memory_limit' );
            else 
                $memory_limit = __( 'N/A', $wd_options->prefix );
            $server_info["PHP Memory Limit"] = $memory_limit;

            // Get actual memory_get_usage
            if ( function_exists( 'memory_get_usage' ) ) 
                $memory_usage = round( memory_get_usage() / 1024 / 1024, 2 ) . __( ' MByte', $wd_options->prefix );
            else 
                $memory_usage = __( 'N/A', $wd_options->prefix );
            $server_info["Memory usage"] = $memory_usage;

            // required for EXIF read
            if ( is_callable( 'exif_read_data' ) ) 
                $exif = __( 'Yes', $wd_options->prefix ). " (V" . substr( phpversion( 'exif' ), 0, 4 ) . ")" ;
            else 
                $exif = __( 'No', $wd_options->prefix );
            $server_info["PHP Exif support"] = $exif;

            // required for meta data
            if ( is_callable( 'iptcparse' ) ) 
                $iptc = __( 'Yes', $wd_options->prefix );
            else 
                $iptc = __( 'No', $wd_options->prefix );
            $server_info["PHP IPTC support"] = $iptc;

            // required for meta data
            if ( is_callable( 'xml_parser_create' ) ) 
                $xml = __( 'Yes', $wd_options->prefix );
            else 
                $xml = __( 'No', $wd_options->prefix );
            $server_info["PHP XML support"] = $xml;

            $gd_info = array( "GD support" => __( 'No', $wd_options->prefix ) );
            if( function_exists( "gd_info" ) ){
                $gd_info = array();
                foreach( gd_info() as $key => $val ){
                    if( is_bool($val) ){
                        $gd_info[$key] = $val ? __( 'Yes', $wd_options->prefix ) : __( 'No', $wd_options->prefix );
                    }
                    else{
                        $gd_info[$key] = $val;
                    }
                }
            }

            require_once( $wd_options->wd_dir_templates . "/display_overview_support.php" );
        }

        public function overview_styles() {
            $wd_options =  $this->config; 
            $version = get_option( $wd_options->prefix . "_version" );
            wp_enqueue_style( $wd_options->prefix . '_overview_css', $wd_options->wd_url_css . '/overview.css', array(), $version );

        }
        public function overview_scripts() {
            $wd_options =  $this->config; 
            $version = get_option( $wd_options->prefix . "_version" );
            wp_enqueue_script( $wd_options->prefix . '_overview_js',  $wd_options->wd_url_js . '/overview.js', array(),  $version );

        }    
        ////////////////////////////////////////////////////////////////////////////////////////
        // Getters & Setters                                                                  //
        ////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////
        // Private Methods                                                                    //
        ////////////////////////////////////////////////////////////////////////////////////////
        private function remote_get($plugin_wp_slug){
            $request = wp_remote_get(" http://api.wordpress.org/plugins/info/1.0/" . $plugin_wp_slug); 
            $data = array();
            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
                $body = unserialize($request['body']);
                $data["downloaded"] = $body->downloaded;
                $ratings = $body->ratings;
                if( $ratings[5] == 0 && $ratings[4] == 0 && $ratings[3] == 0 && $ratings[2] == 0 && $ratings[1] == 0){
                    $data["rating"] = 100;
                }
                else{
                    $data["rating"] = round( ( ( $ratings[5] * 5 + $ratings[4] * 4 + $ratings[3] * 3 + $ratings[2] * 2 + $ratings[1] * 1 ) / $body->num_ratings ) , 1 );
                    
                    $data["rating"] = round( ( $data["rating"] / 5 ) * 100 );
                }
                return $data;
            }
            return false;
           
        }
        ////////////////////////////////////////////////////////////////////////////////////////
        // Listeners                                                                          //
        ////////////////////////////////////////////////////////////////////////////////////////
        
    }  