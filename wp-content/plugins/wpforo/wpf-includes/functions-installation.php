<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

function do_wpforo_activation($network_wide){
    if ( is_multisite() && $network_wide ) {
        global $wpdb;

        $old_blogid = $wpdb->blogid;
        $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
        foreach ($blogids as $blogid){
            switch_to_blog($blogid);
            wpforo_activation();
        }
        switch_to_blog($old_blogid);
    }else{
        wpforo_activation();
    }
}

function do_wpforo_deactivation($network_wide){
    if ( is_multisite() && $network_wide ) {
        global $wpdb;

        $old_blogid = $wpdb->blogid;
        $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
        foreach ($blogids as $blogid){
            switch_to_blog($blogid);
            wpforo_deactivation();
        }
        switch_to_blog($old_blogid);
    }else{
        wpforo_deactivation();
    }
}

function wpforo_activation(){
    if( ! wpforo_is_admin() ) return;
    if( ! current_user_can( 'activate_plugins' ) ) return;

    add_option('wpforo_default_groupid', WPF()->usergroup->default->default_groupid);

    require( WPFORO_DIR . '/wpf-includes/install-sql.php' );
    foreach( $wpforo_sql as $sql ) if( FALSE === @WPF()->db->query($sql) ) {
        @WPF()->db->query( preg_replace('#)[\r\n\t\s]*ENGINE.*$#isu', ')', $sql) );
    }

    WPF()->member->synchronize_users(100);
    WPF()->member->init_current_user();

    add_option( 'wpforo_count_per_page', 10 );

    ###################################################################
    // General Options ////////////////////////////////////////////////
    wpforo_update_options( 'wpforo_general_options', WPF()->default->general_options );

    ###################################################################
    // Forums /////////////////////////////////////////////////////////
    wpforo_update_options( 'wpforo_forum_options', WPF()->forum->default->options );

    ##################################################################
    // Topics & Posts ////////////////////////////////////////////////
    wpforo_update_options( 'wpforo_post_options', WPF()->post->default->options );

    #################################################################
    // Features /////////////////////////////////////////////////////
    wpforo_update_options( 'wpforo_features', WPF()->default->features );

    #################################################################
    // API //////////////////////////////////////////////////////////
    wpforo_update_options( 'wpforo_api_options', WPF()->default->features );

    #################################################################
    // Theme & Style ////////////////////////////////////////////////
    wpforo_update_options( 'wpforo_style_options', WPF()->tpl->default->style );
    wpforo_update_options( 'wpforo_theme_options', WPF()->tpl->default->options );

    #################################################################
    // Members //////////////////////////////////////////////////////
    $exlude = array('rating_title_ug', 'rating_badge_ug');
    wpforo_update_options( 'wpforo_member_options', WPF()->member->default->options, $exlude);

    #################################################################
    // Subscribe Options ////////////////////////////////////////////
    wpforo_update_options( 'wpforo_subscribe_options', WPF()->sbscrb->default->options );

    #################################################################
    // Tool Options - Antispam ///////////////////////////////////////
    wpforo_update_options( 'wpforo_tools_antispam', WPF()->default->tools_antispam);

    #################################################################
    // Tool Options - Cleanup ///////////////////////////////////////
    wpforo_update_options( 'wpforo_tools_cleanup', WPF()->default->tools_cleanup);

    #################################################################
    // Tool Options - Misc ///////////////////////////////////////
    wpforo_update_options( 'wpforo_tools_misc', WPF()->default->tools_misc);

    #################################################################
    // Forum Navigation and Menu ////////////////////////////////////
    $menu_name = wpforo_phrase('wpForo Navigation', false, 'orig');
    $menu_location = 'wpforo-menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    if(!$menu_exists){
        $id = array();
        $menu_id = wp_create_nav_menu($menu_name);
        $id['wpforo-home'] = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  wpforo_phrase('Forums', false),
            'menu-item-classes' => 'wpforo-home',
            'menu-item-url' => '/%wpforo-home%/',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 0));

        $id['wpforo-members'] = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  wpforo_phrase('Members', false),
            'menu-item-classes' => 'wpforo-members',
            'menu-item-url' => '/%wpforo-members%/',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 0));

        $id['wpforo-recent'] = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  wpforo_phrase('Recent Posts', false),
            'menu-item-classes' => 'wpforo-recent',
            'menu-item-url' => '/%wpforo-recent%/',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 0));

        $id['wpforo-profile'] =  wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  wpforo_phrase('My Profile', false),
            'menu-item-classes' => 'wpforo-profile',
            'menu-item-url' => '/%wpforo-profile-home%/',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 0));

        if(isset($id['wpforo-profile']) && $id['wpforo-profile']){
            $id['wpforo-profile-account'] =  wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  wpforo_phrase('Account', false),
                    'menu-item-classes' => 'wpforo-profile-account',
                    'menu-item-url' => '/%wpforo-profile-account%/',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $id['wpforo-profile'],
                    'menu-item-position' => 1)
            );
            $id['wpforo-profile-activity'] =  wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  wpforo_phrase('Activity', false),
                    'menu-item-classes' => 'wpforo-profile-activity',
                    'menu-item-url' => '/%wpforo-profile-activity%/',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $id['wpforo-profile'],
                    'menu-item-position' => 1)
            );
            $id['wpforo-profile-subscriptions'] =  wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  wpforo_phrase('Subscriptions', false),
                    'menu-item-classes' => 'wpforo-profile-subscriptions',
                    'menu-item-url' => '/%wpforo-profile-subscriptions%/',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $id['wpforo-profile'],
                    'menu-item-position' => 2)
            );
        }

        $id['wpforo-register'] =  wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  wpforo_phrase('Register', false),
            'menu-item-classes' => 'wpforo-register',
            'menu-item-url' => '/%wpforo-register%/',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 0));

        $id['wpforo-login'] =  wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  wpforo_phrase('Login', false),
            'menu-item-classes' => 'wpforo-login',
            'menu-item-url' => '/%wpforo-login%/',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 0));

        $id['wpforo-logout'] =  wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  wpforo_phrase('Logout', false),
            'menu-item-classes' => 'wpforo-logout',
            'menu-item-url' => '/%wpforo-logout%/',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 0));

        if( !has_nav_menu( $menu_location ) ){
            $locations = get_theme_mod('nav_menu_locations');
            if(empty($locations)) $locations = array();
            $locations[$menu_location] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }

    #################################################################
    // Access Sets //////////////////////////////////////////////////
    $cans_n = array('vf'  => 0, 'ct'  => 0, 'vt'  => 0, 'et'  => 0, 'dt' => 0,
        'cr'  => 0, 'vr'  => 0, 'er'  => 0, 'dr'  => 0, 'tag' => 0,
        'eot' => 0, 'eor' => 0, 'dot' => 0,	'dor' => 0, 'sb' => 0,
        'l'   => 0, 'r'   => 0, 's'   => 0, 'au'  => 0,
        'p'   => 0, 'op' => 0, 'vp' => 0, 'sv'  => 0,
        'osv'  => 0, 'v'  => 0, 'a' => 0, 'va' => 0,
        'at'  => 0, 'oat' => 0, 'aot'=> 0, 'cot' => 0, 'mt' => 0, 'ccp' => 0, 'cvp' => 0, 'cvpr' => 0 );
    $cans_r = array('vf'  => 1, 'ct'  => 0, 'vt'  => 1, 'et'  => 0, 'dt' => 0,
        'cr'  => 0, 'vr'  => 1, 'er'  => 0, 'dr' => 0, 'tag' => 0,
        'eot' => 0, 'eor' => 0, 'dot' => 0,	'dor' => 0, 'sb' => 1,
        'l'   => 0, 'r'   => 0, 's'   => 0, 'au'  => 0,
        'p'   => 0, 'op' => 0, 'vp' => 0, 'sv'  => 0,
        'osv' => 0, 'v' => 0, 'a' => 0, 'va' => 1,
        'at'  => 0, 'oat' => 0, 'aot'=> 0, 'cot' => 0, 'mt' => 0, 'ccp' => 0, 'cvp' => 0, 'cvpr' => 1 );
    $cans_s = array('vf'  => 1, 'ct'  => 1, 'vt'  => 1, 'et'  => 0, 'dt' => 0,
        'cr'  => 1, 'vr'  => 1, 'er'  => 0, 'dr' => 0, 'tag' => 1,
        'eot' => 1, 'eor' => 1, 'dot' => 1,	'dor' => 1, 'sb' => 1,
        'l'   => 1, 'r'   => 1, 's'   => 0, 'au'  => 0,
        'p'   => 0, 'op' => 1, 'vp' => 0, 'sv'  => 0,
        'osv' => 1, 'v' => 1, 'a' => 1, 'va' => 1,
        'at'  => 0, 'oat' => 1, 'aot'=> 1, 'cot' => 0, 'mt' => 0, 'ccp' => 1, 'cvp' => 1, 'cvpr' => 1 );
    $cans_m =  array('vf'  => 1, 'ct'  => 1, 'vt'  => 1, 'et'  => 1, 'dt' => 1,
        'cr'  => 1, 'vr'  => 1, 'er'  => 1, 'dr' => 1, 'tag' => 1,
        'eot' => 1, 'eor' => 1, 'dot' => 1,	'dor' => 1, 'sb' => 1,
        'l'   => 1, 'r'   => 1, 's'   => 1, 'au'  => 1,
        'p'   => 1, 'op' => 1, 'vp' => 1, 'sv'  => 1,
        'osv'  => 1, 'v' => 1, 'a' => 1, 'va' => 1,
        'at'  => 1, 'oat' => 1, 'aot'=> 1, 'cot' => 1, 'mt' => 1, 'ccp' => 1, 'cvp' => 1, 'cvpr' => 1 );
    $cans_a =  array('vf'  => 1, 'ct'  => 1, 'vt'  => 1, 'et'  => 1, 'dt' => 1,
        'cr'   => 1, 'vr'  => 1, 'er'  => 1, 'dr'  => 1, 'tag' => 1,
        'eot'  => 1, 'eor' => 1, 'dot' => 1, 'dor' => 1, 'sb' => 1,
        'l'    => 1, 'r'   => 1, 's'   => 1, 'au'  => 1,
        'p'   => 1, 'op' => 1, 'vp' => 1, 'sv'  => 1,
        'osv' => 1, 'v'   => 1, 'a' => 1, 'va' => 1,
        'at'   => 1, 'oat' => 1, 'aot'=> 1, 'cot' => 1, 'mt'  => 1, 'ccp' => 1, 'cvp' => 1, 'cvpr' => 1 );

    //Add new Accesses in this array to add those in custom Accesses created by forum admin
    $cans_default = array( 'sb' => 1, 'au'  => 1, 'p' => 0, 'op' => 1,
                            'vp' => 0,'ccp' => 0, 'cvp' => 0, 'cvpr' => 1,
                                'aot'=> 1, 'tag' => 1 );

    $sql = "SELECT * FROM `".WPF()->tables->accesses."`";
    $accesses = WPF()->db->get_results($sql, ARRAY_A);
    if( empty($accesses) ){

        $cans_n = serialize($cans_n);
        $cans_r = serialize($cans_r);
        $cans_s = serialize($cans_s);
        $cans_m = serialize($cans_m);
        $cans_a = serialize($cans_a);

        $sql = "INSERT IGNORE INTO `".WPF()->tables->accesses."` 
			(`access`, `title`, cans) VALUES	
			('no_access', 'No access', '". $cans_n ."'),
			('read_only', 'Read only access', '". $cans_r ."'),
			('standard', 'Standard access', '". $cans_s ."'),
			('moderator', 'Moderator access', '".$cans_m."'),
			('full', 'Full access', '".$cans_a."')";

        WPF()->db->query( $sql );
    }else{
        foreach($accesses as $access){
            $current = unserialize($access['cans']);
            if( strtolower($access['access']) == 'no_access' ){ $default = $cans_n; }
            elseif( strtolower($access['access']) == 'read_only' ){ $default = $cans_r; }
            elseif( strtolower($access['access']) == 'standard' ) { $default = $cans_s; }
            elseif( strtolower($access['access']) == 'moderator' ) { $default = $cans_m; }
            elseif( strtolower($access['access']) == 'full' ) { $default = $cans_a; }
            else { $default = $cans_default; }
            if( !empty($default) ){
                $data_update = array_merge($default, $current);
                if( !empty($data_update) ){
                    $data_update = serialize($data_update);
                    WPF()->db->query("UPDATE `".WPF()->tables->accesses."` SET `cans` = '" . WPF()->db->_real_escape($data_update) . "' WHERE `accessid` = " . intval($access['accessid']) );
                }
            }
        }
    }


    #################################################################
    // Usergroup ////////////////////////////////////////////////////
    $cans_admin = array('mf'    => '1', 'ms' => '1', 'mt' => '1', 'mp' => '1', 'mth' => '1', 'vm'   => '1', 'aum'   => '1', 'em' => '1', 'vmg' => '1', 'aup' => '1', 'vmem' => '1', 'view_stat' => '1', 'vprf' => '1', 'vpra' => '1', 'vprs' => '1',
        'bm'    => '1', 'dm'    => '1', 'upa'  => '1', 'ups'  => '1', 'va'   => '1',
        'vmu'   => '1', 'vmm'  => '1', 'vmt'  => '1', 'vmct' => '1',
        'vmr'   => '1', 'vmw'  => '1', 'vmsn' => '1', 'vmrd' => '1',
        'vml'  => '1', 'vmo'  => '1',
        'vms'   => '1', 'vmam' => '1', 'vwpm' => '1');
    $cans_moder = array('mf'    => '0', 'ms' => '0', 'mt' => '0', 'mp' => '0', 'mth' => '0', 'vm'   => '0', 'aum'   => '1', 'em' => '0', 'vmg' => '0', 'aup' => '1', 'vmem' => '1', 'view_stat' => '1', 'vprf' => '1', 'vpra' => '1', 'vprs' => '1',
        'bm'    => '1', 'dm'    => '1', 'upa'  => '1', 'ups'  => '1', 'va'   => '1',
        'vmu'   => '0', 'vmm'  => '1', 'vmt'  => '1', 'vmct' => '1',
        'vmr'   => '1', 'vmw'  => '1', 'vmsn' => '1', 'vmrd' => '1',
        'vml'  => '1', 'vmo'  => '1',
        'vms'   => '1', 'vmam' => '1', 'vwpm' => '1');
    $cans_reg = array(  'mf'    => '0', 'ms' => '0', 'mt' => '0', 'mp' => '0', 'mth' => '0', 'vm'   => '0', 'aum'   => '0', 'em' => '0', 'vmg' => '0', 'aup' => '1', 'vmem' => '1', 'view_stat' => '1', 'vprf' => '1', 'vpra' => '1', 'vprs' => '0',
        'bm'    => '0', 'dm'    => '0', 'upa'  => '1', 'ups'  => '1', 'va'   => '1',
        'vmu'   => '0', 'vmm'  => '0', 'vmt'  => '1', 'vmct' => '1',
        'vmr'   => '1', 'vmw'  => '1', 'vmsn' => '1', 'vmrd' => '1',
        'vml'  => '1', 'vmo'  => '1',
        'vms'   => '1', 'vmam' => '1', 'vwpm' => '1');
    $cans_guest = array('mf' 	=> '0', 'ms' => '0', 'mt' => '0', 'mp' => '0', 'mth' => '0', 'vm'	=> '0', 'aum'   => '0', 'em' 	=> '0', 'vmg'	=> '0', 'aup'	=> '0', 'vmem'	=> '1', 'view_stat' => '1', 'vprf' => '1', 'vpra' => '1', 'vprs' => '0',
        'bm' => '0', 'dm'	=> '0', 'upa'	=> '0', 'ups'	=> '0', 'va'	=> '1',
        'vmu'	=> '0', 'vmm'	=> '0', 'vmt'	=> '1', 'vmct'	=> '1',
        'vmr'	=> '1', 'vmw'	=> '0', 'vmsn'	=> '1', 'vmrd'	=> '1',
        'vml'	=> '1', 'vmo'	=> '1',
        'vms'   => '1', 'vmam'	=> '1', 'vwpm'	=> '0');
    $cans_customer = array('mf'    => '0', 'ms' => '0', 'mt' => '0', 'mp' => '0', 'mth' => '0', 'vm'   => '0', 'aum'   => '0', 'em' => '0', 'vmg' => '0', 'aup' => '0', 'vmem' => '1', 'view_stat' => '1', 'vprf' => '1', 'vpra' => '1', 'vprs' => '0',
        'bm' => '0',  'dm'    => '0', 'upa'  => '1', 'ups'  => '1', 'va'   => '1',
        'vmu'   => '0', 'vmm'  => '0', 'vmt'  => '1', 'vmct' => '1',
        'vmr'   => '1', 'vmw'  => '1', 'vmsn' => '1', 'vmrd' => '1',
        'vml'  => '1', 'vmo'  => '1',
        'vms'   => '1', 'vmam' => '1', 'vwpm' => '1');

    //Add new Cans in this array to add those in custom Usergroup created by forum admin
    $cans_defaults = array( 'mf' => '0', 'ms' => '0', 'mt' => '0', 'mp' => '0', 'mth' => '0', 'vmem' => 1, 'view_stat' => '1', 'vprf' => 1 );

    $sql = "SELECT * FROM `".WPF()->tables->usergroups."`";
    if( !$usergroups = WPF()->db->get_results($sql, ARRAY_A) ){
        WPF()->usergroup->add('Admin', $cans_admin, '', 'administrator', 'full', '#FF3333', 1, 0);
        WPF()->usergroup->add('Moderator', $cans_moder, '', 'editor', 'moderator', '#0066FF', 1, 0);
        WPF()->usergroup->add('Registered', $cans_reg, '', 'subscriber', 'standard', '', 1, 1);
        WPF()->usergroup->add('Guest', $cans_guest, '', '', 'read_only', '#222222', 0, 0);
        WPF()->usergroup->add('Customer', $cans_customer, '', 'customer', 'standard', '#993366', 1, 1);
    }
    else{
        foreach($usergroups as $usergroup){
            $default = array();
            $current = unserialize($usergroup['cans']);
            if( strtolower($usergroup['name']) == 'admin' ) $default = $cans_admin;
            elseif( strtolower($usergroup['name']) == 'moderator' ) $default = $cans_moder;
            elseif( strtolower($usergroup['name']) == 'registered' ) $default = $cans_reg;
            elseif( strtolower($usergroup['name']) == 'guest' ) $default = $cans_guest;
            elseif( strtolower($usergroup['name']) == 'customer' ) $default = $cans_customer;
            else { $default = $cans_defaults; }
            if( !empty($default) ){
                $data_update = array_merge($default, $current);
                if( !empty($data_update) ){
                    $data_update = serialize($data_update);
                    WPF()->db->query("UPDATE `".WPF()->tables->usergroups."` SET `cans` = '" . WPF()->db->_real_escape($data_update) . "' WHERE `groupid` = " . intval($usergroup['groupid']) );
                }
            }
        }
    }
    $sql = "SELECT COUNT(*) FROM `".WPF()->tables->forums."`";
    $count = WPF()->db->get_var($sql);
    if(!$count){
        if( $parentid = WPF()->forum->add( array( 'title' => __('Main Category', 'wpforo'), 'description' => __('This is a simple category / section', 'wpforo') ), FALSE ) ){
            WPF()->forum->add( array( 'title' => __('Main Forum', 'wpforo'), 'description' => __('This is a simple parent forum', 'wpforo'), 'parentid' => $parentid, 'icon' => 'fa-comments' ), FALSE );
        }
    }

    #################################################################
    // Permalink Settings ///////////////////////////////////////////
    $permalink_structure = get_option( 'permalink_structure' );
    if( !$permalink_structure ){
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
    }

    #################################################################
    // Creating Forum Page //////////////////////////////////////////
    wpforo_create_forum_page();

    #################################################################
    // Importing Language Packs and Phrases /////////////////////////
    WPF()->phrase->xml_import('english.xml', 'install');

    #################################################################
    // Creating wpforo folders //////////////////////////////////////
    $upload_array = wp_upload_dir();
    $wpforo_upload_dir = $upload_array['basedir'].'/wpforo/';
    if (!is_dir($wpforo_upload_dir)) {
        wp_mkdir_p($wpforo_upload_dir);
    }
    $avatars_upload_dir=$upload_array['basedir'].'/wpforo/avatars/';
    if (!is_dir($avatars_upload_dir)) {
        wp_mkdir_p($avatars_upload_dir);
    }

    #################################################################
    // RESET USER CACHE /////////////////////////////////////////////
    WPF()->member->clear_db_cache();

    #################################################################
    // RESET FUNCTIONS //////////////////////////////////////////////
    $sql = "SHOW COLUMNS FROM `".WPF()->tables->phrases."` WHERE `Field` LIKE 'package'";
    if( !WPF()->db->get_row($sql, ARRAY_A) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->phrases."` ADD COLUMN `package` VARCHAR(255) NOT NULL DEFAULT 'wpforo'" );
    }
    WPF()->phrase->clear_cache();

    #################################################################
    // ADD `private` field in TOPIC TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->topics, 'col' => 'private', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD `private` TINYINT(1) NOT NULL DEFAULT '0', ADD INDEX `is_private` (`private`);" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD INDEX `own_private` ( `userid`, `private`);" );
    }
    // ADD INDEXES in wpforo_views TABLE///////////////////////////
    $args = array( 'table' => WPF()->tables->views, 'col' => 'topicid', 'check' => 'key_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->views."` ADD INDEX(`userid`);" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->views."` ADD INDEX(`topicid`);" );
    }
    $args = array( 'table' => WPF()->tables->views, 'col' => 'created', 'check' => 'col_type' );
    $col_type = wpforo_db_check( $args );
    if( $col_type != 'int(11)' ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->views."` MODIFY `created` INT(11) NOT NULL;" );
    }
    // ADD `status` field in TOPICS & POSTS TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->topics, 'col' => 'status', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD `status` TINYINT(1) NOT NULL DEFAULT '0', ADD INDEX `status` (`status`);" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."` ADD `status` TINYINT(1) NOT NULL DEFAULT '0', ADD INDEX `status` (`status`);" );
    }
    // ADD `name` and `email` field in TOPIC TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->topics, 'col' => 'name', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD `name` VARCHAR(50) NOT NULL,  ADD `email` VARCHAR(50) NOT NULL" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."` ADD `name` VARCHAR(50) NOT NULL,  ADD `email` VARCHAR(50) NOT NULL" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD KEY `email` (`email`)" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."` ADD KEY `email` (`email`)" );
    }
    // ADD `utitle`, `role` and `access` to USERGROUP TABLE  /////////
    $args = array( 'table' => WPF()->tables->usergroups, 'col' => 'utitle', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->usergroups."` ADD `utitle` VARCHAR(100), ADD `role` VARCHAR(50), ADD `access` VARCHAR(50)" );
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `utitle` = 'Admin', `role` = 'administrator', `access` = 'full' WHERE `groupid` = 1");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `utitle` = 'Moderator', `role` = 'editor', `access` = 'moderator' WHERE `groupid` = 2");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `utitle` = 'Registered', `role` = 'subscriber', `access` = 'standard' WHERE `groupid` = 3");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `utitle` = 'Guest', `role` = '', `access` = 'read_only' WHERE `groupid` = 4");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `utitle` = 'Customer', `role` = 'customer', `access` = 'standard' WHERE `groupid` = 5");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `utitle` = 'name', `role` = 'subscriber', `access` = 'standard' WHERE `utitle` IS NULL OR `utitle` = ''");
    }
    #################################################################
    // ADD `color` field in usergroups TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->usergroups, 'col' => 'color', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->usergroups."` ADD `color` varchar(7) NOT NULL DEFAULT ''" );
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `color` = '#FF3333' WHERE `groupid` = 1");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `color` = '#0066FF' WHERE `groupid` = 2");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `color` = '#222222' WHERE `groupid` = 4");
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `color` = '#993366' WHERE `groupid` = 5");
    }
    #################################################################
    // ADD `visible` field in usergroups TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->usergroups, 'col' => 'visible', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->usergroups."` ADD `visible` TINYINT(1) NOT NULL DEFAULT 1;" );
    }
    #################################################################
    // ADD `online_time` field in profiles TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->profiles, 'col' => 'online_time', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->profiles."` ADD `online_time` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `last_login`, ADD KEY (`online_time`)" );
    }
    // ADD `is_email_confirmed` field in profiles TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->profiles, 'col' => 'is_email_confirmed', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        WPF()->db->query( "ALTER TABLE `".WPF()->tables->profiles."` ADD `is_email_confirmed` TINYINT(1) NOT NULL DEFAULT 0, ADD KEY (`is_email_confirmed`)" );
        WPF()->db->query( "UPDATE `".WPF()->tables->profiles."`
                                 JOIN `".WPF()->tables->subscribes."`
                                       ON `".WPF()->tables->subscribes."`.`userid` = `".WPF()->tables->profiles."`.`userid`
                                            SET `".WPF()->tables->profiles."`.`is_email_confirmed` = 1 
                                                WHERE `".WPF()->tables->subscribes."`.`active` = 1");
        WPF()->db->query("UPDATE `".WPF()->tables->profiles."` SET `is_email_confirmed` = 1 WHERE `groupid` = 1");
    }
    #################################################################
    // DROP uname unique key from profiles TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->profiles, 'col' => 'UNIQUE USERNAME', 'check' => 'key_exists' );
    if( wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->profiles."` DROP KEY `UNIQUE USERNAME`" );
    }
    $args = array( 'table' => WPF()->tables->profiles, 'col' => 'UNIQUE ID', 'check' => 'key_exists' );
    if( wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->profiles."` DROP KEY `UNIQUE ID`" );
    }
    #################################################################
    // ADD `private` field in post TABLE  ///////////////////////////
    $args = array( 'table' => WPF()->tables->posts, 'col' => 'private', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."` ADD `private` TINYINT(1) NOT NULL DEFAULT 0, ADD INDEX `is_private` (`private`)" );
    }
    #################################################################
    // ADD `unique_vote` KEY in post Votes  ///////////////////////////
    $args = array( 'table' => WPF()->tables->votes, 'col' => 'unique_vote', 'check' => 'key_exists' );
    if( !wpforo_db_check( $args ) ){
        $args = array( 'table' => WPF()->tables->votes, 'col' => 'userid', 'check' => 'key_exists' );
        if( wpforo_db_check( $args ) ) @WPF()->db->query( "ALTER TABLE `".WPF()->tables->votes."` DROP KEY `userid`" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->votes."` ADD UNIQUE KEY `unique_vote` (`userid`, `postid`, `reaction`)" );
    }
    #################################################################
    //Add user_name col in subsciption table///////////////////////////
    $args = array( 'table' => WPF()->tables->subscribes, 'col' => 'user_name', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->subscribes."` ADD `user_name` VARCHAR(60) NOT NULL DEFAULT ''" );
    }
    //Add user_email col in subsciption table
    $args = array( 'table' => WPF()->tables->subscribes, 'col' => 'user_email', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->subscribes."` ADD `user_email` VARCHAR(60) NOT NULL DEFAULT ''" );
    }
    //Add indexes for subscribe new fields
    $args = array( 'table' => WPF()->tables->subscribes, 'col' => 'fld_group_unq', 'check' => 'key_exists' );
    if( !wpforo_db_check( $args ) ){
        $args = array( 'table' => WPF()->tables->subscribes, 'col' => 'itemid', 'check' => 'key_exists' );
        if( wpforo_db_check( $args ) ) @WPF()->db->query( "ALTER TABLE `".WPF()->tables->subscribes."` DROP KEY `itemid`" );
        wpforo_add_unique_key( WPF()->tables->subscribes, 'subid', 'fld_group_unq', '`itemid`, `type`, `userid`, `user_email`(60)');
    }
    $args = array( 'table' => WPF()->tables->subscribes, 'col' => 'type', 'check' => 'col_type' );
    $col_type = wpforo_db_check( $args );
    if( $col_type != 'varchar(50)' ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->subscribes."` MODIFY `type` VARCHAR(50) NOT NULL" );
    }
    //Add index for double condition queries to avoid SQl caching
    $args = array( 'table' => WPF()->tables->posts, 'col' => 'forumid_status', 'check' => 'key_exists' );
    if( !wpforo_db_check( $args ) ) {
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."` ADD KEY `forumid_status` (`forumid`,`status`)" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."` ADD KEY `topicid_status` (`topicid`,`status`)" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."` ADD KEY `topicid_solved` (`topicid`,`is_answer`)" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD KEY `forumid_status` (`forumid`,`status`)" );
    }
    #################################################################
    // ADD `secondary_groups` field in profiles TABLE  //////////////
    $args = array( 'table' => WPF()->tables->profiles, 'col' => 'secondary_groups', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->profiles."` ADD `secondary_groups` VARCHAR(255)" );
    }
    $args = array( 'table' => WPF()->tables->usergroups, 'col' => 'secondary', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `" . WPF()->tables->usergroups . "` ADD `secondary` TINYINT(1) NOT NULL DEFAULT 0;" );
        @WPF()->db->query( "UPDATE `".WPF()->tables->usergroups."` SET `secondary` = 1 WHERE `groupid` IN(3,5)");
    }
    #################################################################
    // ADD `fields` field in profiles TABLE  ////////////////////////
    $args = array( 'table' => WPF()->tables->profiles, 'col' => 'fields', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->profiles."` ADD `fields` LONGTEXT" );
    }
    #################################################################
    // Change `phrase_key` type in phrases TABLE  ///////////////////
    $args = array( 'table' => WPF()->tables->phrases, 'col' => 'phrase_key', 'check' => 'col_type' );
    $col_type = strtolower( wpforo_db_check( $args ) );
    if( $col_type != 'text' ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->phrases."` MODIFY `phrase_key` TEXT" );
    }
    #################################################################
    // ADD `prefix` and `tags` fields in TOPIC TABLE  ///////////////
    $args = array( 'table' => WPF()->tables->topics, 'col' => 'tags', 'check' => 'col_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD `prefix` VARCHAR(100) NOT NULL DEFAULT '', ADD `tags` TEXT, ADD KEY (`prefix`), ADD KEY (`tags`(190))" );
    }
    #################################################################
    //Add last_post indexes for forums and topics tables ////////////
    $args = array( 'table' => WPF()->tables->forums, 'col' => 'last_postid', 'check' => 'key_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->forums."` ADD KEY(`last_postid`)");
	    @WPF()->db->query( "ALTER TABLE `".WPF()->tables->topics."` ADD KEY `forumid_status_private` ( `forumid`,`status`,`private` ), ADD KEY(`last_post`)");
	    @WPF()->db->query( "ALTER TABLE `".WPF()->tables->posts."`  ADD KEY `forumid_status_private` (`forumid`, `status`, `private`)");
    }
    #################################################################
    //Add lunique keys in VISITS TABE ///////////////////////////////
    $args = array( 'table' => WPF()->tables->visits, 'col' => 'unique_tracking', 'check' => 'key_exists' );
    if( !wpforo_db_check( $args ) ){
        wpforo_add_unique_key( WPF()->tables->visits, 'id', 'unique_tracking', '`userid`,`ip`,`forumid`,`topicid`');
        @WPF()->db->query( "ALTER TABLE `" . WPF()->tables->visits . "` ADD KEY `time_forumid` (`time`, `forumid`)");
        @WPF()->db->query( "ALTER TABLE `" . WPF()->tables->visits . "` ADD KEY `time_topicid` (`time`, `topicid`)");
    }
    #################################################################
    // CHECK Addon Notice /////////////////////////////////////////
    $lastHash = get_option('wpforo-addon-note-dismissed');
    $first = get_option('wpforo-addon-note-first');
    if( $lastHash && $first == 'true' ) {
        update_option('wpforo-addon-note-first', 'false');
    }
    #################################################################
    // AVOID PLUGIN CONFLICTS ///////////////////////////////////////
    /* Autoptimize *************************************************/
    $autopt = get_option('autoptimize_js_exclude');
    if( $autopt && strpos($autopt, 'wp-includes/js/tinymce') === FALSE ){
        $autopt = $autopt . ', wp-includes/js/tinymce';
        update_option('autoptimize_js_exclude', $autopt);
        if( class_exists('autoptimizeCache') && is_callable(array('autoptimizeCache', 'clearall')) ){
            autoptimizeCache::clearall();
        }
    }

    #################################################################
    // UPDATE VERSION - END /////////////////////////////////////////
    WPF()->db->query("DELETE FROM `" . WPF()->tables->visits."`");
    WPF()->db->query("ALTER TABLE `" . WPF()->tables->visits."` AUTO_INCREMENT = 1");
    update_option('wpforo_version', WPFORO_VERSION);
    WPF()->notice->clear();
    wpforo_clean_cache();
}


function wpforo_update() {
    if ( get_option('wpforo_version') && WPFORO_VERSION !== get_option('wpforo_version') ) wpforo_activation();
}
add_action('wp_loaded', 'wpforo_update');


function wpforo_update_options( $option_key, $default, $exlude = array() ) {

    $option = get_option( $option_key, array() );

    if( !empty($option) ){
        if( !empty($exlude) ){
            foreach( $exlude as $key ){
                if( isset($default[$key]) ) unset($default[$key]);
            }
        }
        $option_update = array_merge($default, $option);
    }
    else{
        $option_update = $default;
    }

    update_option( $option_key, $option_update );
}


function wpforo_deactivation() {}


function wpforo_uninstall() {
    if( ! wpforo_is_admin() ) return;
    if( ! current_user_can( 'activate_plugins' ) ) return;
    $QUERY_STRING = trim(preg_replace('|_wpnonce=[^\&\?\=]*|is', '', $_SERVER['QUERY_STRING']), '&');

    if( 'action=wpforo-uninstall' == trim($QUERY_STRING) ){

        foreach(WPF()->tables as $table){
            $sql = "DROP TABLE IF EXISTS `$table`;";
            WPF()->db->query( $sql );
        }

        if( isset(WPF()->pageid) && WPF()->pageid ){
            wp_delete_post( WPF()->pageid, true );
        }

        $options = array( 'wpforo_version',
            'wpforo_url',
            'wpforo_stat',
            'wpforo_general_options',
            'wpforo_pageid',
            'wpforo_count_per_page',
            'wpforo_default_groupid',
            'wpforo_forum_options',
            'wpforo_post_options',
            'wpforo_member_options',
            'wpforo_subscribe_options',
            'wpforo_theme_options',
            'wpforo_features',
            'wpforo_style_options',
            'wpforo_permastruct',
            'wpforo_use_home_url',
            'wpforo_excld_urls',
            'wpforo_tools_antispam',
            'wpforo_tools_cleanup',
            'wpforo_tools_misc',
            'wpforo_tools_legal',
            'wpforo_api_options',
            'wpforo_deactivation_dialog_never_show',
	        'wpforo_tpl_slugs'
        );

        foreach($options as $option){
            if( strpos( $option, 'wpforo_' ) !== FALSE){
                delete_option( $option );
            }
        }

        WPF()->db->query( "DELETE FROM `" . WPF()->db->usermeta."` WHERE `meta_key` = '_wpf_login_times'" );
        WPF()->db->query( "DELETE FROM `" . WPF()->db->usermeta."` WHERE `meta_key` = '_wpf_member_obj'" );

        WPF()->db->query( "DELETE FROM `" . WPF()->db->usermeta."` WHERE `meta_key` = 'wpf_all_read'" );
        WPF()->db->query( "DELETE FROM `" . WPF()->db->usermeta."` WHERE `meta_key` = 'wpf_read_forums'" );
        WPF()->db->query( "DELETE FROM `" . WPF()->db->usermeta."` WHERE `meta_key` = 'wpf_read_topics'" );

        WPF()->db->query( "DELETE FROM `" . WPF()->db->options."` WHERE option_name LIKE 'wpforo_stat%'" );
        WPF()->db->query( "DELETE FROM `" . WPF()->db->options."` WHERE option_name LIKE 'wpforo_forum_tree_%'" );
        WPF()->db->query( "DELETE FROM `" . WPF()->db->options."` WHERE option_name LIKE 'widget_wpforo_widget_%'" );

        $menu = wp_get_nav_menu_object( 'wpforo-navigation' );
        wp_delete_nav_menu( $menu->term_id );
        wp_delete_post(WPF()->pageid, TRUE);

        deactivate_plugins( WPFORO_BASENAME );

    }
    else{
        return;
    }
}

function wpforo_profile_notice(){
    if( is_multisite() ){
        $users = WPF()->db->get_var("SELECT COUNT(*) FROM `".WPF()->db->usermeta."` WHERE `meta_key` LIKE '".WPF()->blog_prefix."capabilities'");
    } else {
        $users = WPF()->db->get_var("SELECT COUNT(*) FROM `".WPF()->db->users."`");
    }
    $profiles = WPF()->db->get_var("SELECT COUNT(*) FROM `".WPF()->tables->profiles."`");
    $delta = $users - $profiles;
    $status = ( $delta > 2 ) ? round((( $profiles * 100 ) / $users ), 1) . '% (' . $profiles . ' / ' . $users . ') ' : '100%';
    $btext = ( $profiles == 0 ) ? __( 'Start Profile Synchronization', 'wpforo') : __( 'Continue Synchronization', 'wpforo');
    $url = admin_url('admin.php?page=wpforo-community&action=synch');
    $class = 'wpforo-mnote notice notice-warning is-dismissible';
    $note = __( 'This process may take a few seconds or dozens of minutes, please be patient and don\'t close this page.', 'wpforo');
    $info = __( 'You can permanently disable this message in Dashboard > Forums > Features admin page.', 'wpforo');
    $button = '<a href="' . $url . '" class="button button-primary button-large" style="font-size:14px;">' . $btext . ' &gt;&gt;</a>';
    $header = __( 'wpForo Forum Installation | ', 'wpforo' );
    $message = __( 'Forum users\' profile data are not synchronized yet, this step is required! Please click the button below to complete installation.', 'wpforo' );
    echo '<div class="' . $class . '" style="padding:15px 20px;"><h2 style="margin:0px;">' . esc_html($header) . $status . ' </h2><p style="font-size:15px;margin:5px 0px;">' . $message . '</p><p style="margin:0px 0px 10px 0px;">' . $button . '</p><hr /><p style="margin:0px;color:#dd0000;">' . $note . '</p><p style="margin:0px;color:#999; font-size:12px;">' . $info . '</p></div>';
}

function wpforo_update_db_notice(){
    $private_topics = WPF()->db->get_var("SELECT `topicid` FROM `".WPF()->tables->topics."` WHERE `private` = 1 LIMIT 1");
    if( $private_topics ){
        $private_posts = WPF()->db->get_var("SELECT `postid` FROM `".WPF()->tables->posts."` WHERE `private` = 1 LIMIT 1");
        if( !$private_posts ){
            $url = admin_url('admin.php?page=wpforo-community&action=wpfdb&wpfv=142');
            $class = 'wpforo-mnote notice notice-warning is-dismissible';
            $note = __( 'This process may take a few seconds or dozens of minutes, please be patient and don\'t close this page. Database backup is not required. If you got 500 Server Error please don\'t worry, the data updating process is still working in MySQL server.', 'wpforo');
            $button = '<a href="' . $url . '" class="button button-primary button-large" style="font-size:14px;">' . __( 'Updater Database', 'wpforo') . ' &gt;&gt;</a>';
            $header = __( 'wpForo - Update Database ', 'wpforo' );
            $message = __( 'Please click the button below to complete wpForo update.', 'wpforo' );
            echo '<div class="' . $class . '" style="padding:15px 20px;"><h2 style="margin:0px;">' . esc_html($header) . ' </h2><p style="font-size:15px;margin:5px 0px;">' . $message . '</p><p style="margin:0px 0px 10px 0px;">' . $button . '</p><hr /><p style="margin:0px;color:#ed7600;">' . $note . '</p></div>';

        }
    }
}

function wpforo_get_shortcode_pageid( $exclude = array() ){
    $exclude = array_filter( array_map('wpforo_bigintval', (array) $exclude) );
    $sql = "SELECT `ID` FROM `".WPF()->db->posts."` 
        WHERE `post_content` LIKE '%[wpforo]%' 
        AND `post_status` LIKE 'publish' 
        AND `post_type` IN('post', 'page')";
    if( $exclude ) $sql .= " AND `ID` NOT IN(". implode(',', $exclude) .")";
    return WPF()->db->get_var($sql);
}

function wpforo_create_forum_page(){
    if( !WPF()->pageid ||
        !WPF()->db->get_var("SELECT `ID` FROM `".WPF()->db->posts."` WHERE `ID` = '".intval(WPF()->pageid)."' AND ( `post_content` LIKE '%[wpforo]%' OR `post_content` LIKE '%[wpforo-index]%' ) AND `post_status` LIKE 'publish' AND `post_type` IN('post', 'page')") ){
        if( !$page_id = wpforo_get_shortcode_pageid( get_option('page_on_front') ) ){
            $wpforo_page = array(
                'post_date' => current_time( 'mysql', 1 ),
                'post_date_gmt' => current_time( 'mysql', 1 ),
                'post_content' => '[wpforo]',
                'post_title' => 'Forum',
                'post_status' => 'publish',
                'comment_status' => 'close',
                'ping_status' => 'close',
                'post_name' => 'community',
                'post_modified' => current_time( 'mysql', 1 ),
                'post_modified_gmt' => current_time( 'mysql', 1 ),
                'post_parent' => 0,
                'menu_order' => 0,
                'post_type' => 'page'
            );
            $page_id = wp_insert_post( $wpforo_page );
        }
        if( $page_id && !is_wp_error($page_id) ){
            update_option( 'wpforo_pageid', $page_id );
            update_option( 'wpforo_use_home_url', '0' );
            $wpforo_url = get_wpf_option('wpforo_url');
            if( !$wpforo_url ){
                update_option( 'wpforo_permastruct', 'community' );
                update_option( 'wpforo_url', esc_url( home_url('/') ) . "community/" );
            }else{
                if( !WPF()->permastruct ){
                    update_option( 'wpforo_permastruct',  basename($wpforo_url) );
                    update_option( 'wpforo_url', esc_url( home_url('/') ) . basename($wpforo_url) . "/" );
                }else{
                    update_option( 'wpforo_url', esc_url( home_url('/') ) . WPF()->permastruct . "/" );
                }
            }
        }
    }else{
        if( !WPF()->use_home_url ) update_option( 'wpforo_use_home_url', '0' );
        if( !WPF()->permastruct ) update_option( 'wpforo_permastruct', basename( get_wpf_option('wpforo_url') ) );
        WPF()->db->query("UPDATE `".WPF()->db->posts."` SET `post_content` = REPLACE(`post_content`, '[wpforo-index]', '[wpforo]') WHERE `ID` = '".WPF()->pageid."'");
    }

    WPF()->pageid = get_wpf_option( 'wpforo_pageid');
    WPF()->permastruct = trim( get_wpf_option('wpforo_permastruct'), '/' );
    flush_rewrite_rules(FALSE);
    nocache_headers();
}

function wpforo_update_db(){
    // ADD posts' private values from TOPICS table ///////////////////////////
    @WPF()->db->query( "UPDATE `".WPF()->tables->posts."`, `".WPF()->tables->topics."` SET `".WPF()->tables->posts."`.`private` = `".WPF()->tables->topics."`.`private` WHERE `".WPF()->tables->posts."`.`topicid` = `".WPF()->tables->topics."`.`topicid`");
    // ADD INDEXES in wpforo_views TABLE///////////////////////////
    $args = array( 'table' => WPF()->tables->views, 'col' => 'topicid', 'check' => 'key_exists' );
    if( !wpforo_db_check( $args ) ){
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->views."` ADD INDEX(`userid`);" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->views."` ADD INDEX(`topicid`);" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->views."` ADD UNIQUE( `userid`, `topicid`);" );
        @WPF()->db->query( "ALTER TABLE `".WPF()->tables->likes."` ADD UNIQUE( `userid`, `postid`);" );
    }
    update_option('wpforo_version_db', WPFORO_VERSION);
}