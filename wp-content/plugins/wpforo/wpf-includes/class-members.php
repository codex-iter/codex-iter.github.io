<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;


class wpForoMember{
	public $default;
	public $options;
    private $fields;
    private $countries;
    private $timezones;
    public $login_min_length;
    public $login_max_length;
    public $pass_min_length;
    public $pass_max_length;

	static $cache = array( 'users' => array(), 'user' => array(), 'guest' => array(), 'avatar' => array() );
	
	function __construct(){
		$this->init_defaults();
		$this->init_options();

		add_action('delete_user_form', array(&$this, 'show_delete_form'), 10, 2);
	}

	private function init_defaults(){
	    $this->default = new stdClass;

        $this->default->options = array(
            'custom_title_is_on' => 1,
            'default_title' => 'Member',
            'members_per_page' => 15,
            'online_status_timeout' => 240,
            'url_structure' => 'nicename',
            'search_type' => 'search', // can to be 'search' or 'filter'
            'login_url' => '',
            'register_url' => '',
            'lost_password_url' => '',
            'redirect_url_after_login' => '',
            'redirect_url_after_register' => '',
            'redirect_url_after_confirm_sbscrb' => '',
            'rating_title_ug' => array ( 1 => '0', 5 => '1', 4 => '1', 2 => '0', 3 => '1' ),
            'rating_badge_ug' => array ( 1 => '1', 5 => '1', 4 => '1', 2 => '1', 3 => '1' ),
            'title_usergroup' => array ( 1 => '1', 5 => '1', 4 => '1', 2 => '1', 3 => '0' )
        );

        $this->default->login_min_length = 3;
        $this->default->login_max_length = 30;
        $this->default->pass_min_length = 6;
        $this->default->pass_max_length = 20;
    }

    private function init_options(){
        $this->options = get_wpf_option('wpforo_member_options', $this->default->options);
        if( !preg_match('#^https?://[^\r\n\t\s\0]+#isu', $this->options['redirect_url_after_login']) ) $this->options['redirect_url_after_login'] = '';
        if( !preg_match('#^https?://[^\r\n\t\s\0]+#isu', $this->options['redirect_url_after_register']) ) $this->options['redirect_url_after_register'] = '';
        if( !preg_match('#^https?://[^\r\n\t\s\0]+#isu', $this->options['redirect_url_after_confirm_sbscrb']) ) $this->options['redirect_url_after_confirm_sbscrb'] = '';

        $this->login_min_length = $this->default->login_min_length;
        $this->login_max_length = $this->default->login_max_length;
        $this->pass_min_length = $this->default->pass_min_length;
        $this->pass_max_length = $this->default->pass_max_length;
    }
	
	public function get_cache( $var ){
		if( isset(self::$cache[$var]) ) return self::$cache[$var];
	}
	
	public function send_new_user_notifications($user_id, $notify = 'admin'){
		wp_send_new_user_notifications( $user_id, $notify );
	}
 
 	private function add_profile($args){
 		if(empty($args)) return FALSE;
 		if(!isset($args['userid']) || !$args['userid'] || !isset($args['username']) || !$args['username'] ) return FALSE;
		extract( $args, EXTR_OVERWRITE );
		$this->reset($userid);
		$sql = "INSERT IGNORE INTO `".WPF()->tables->profiles."` (`userid`, `title`, `username`, `groupid`, `site`, `timezone`, `about`, `last_login`) VALUES 
		    ( '%d', '%s', '%s', '%d', '%s', '%s', '%s', '%s' )";
		$sql = WPF()->db->prepare(
            $sql,
            $userid,
            ( isset($title) && $title ? $title : WPF()->member->options['default_title'] ),
			sanitize_user($username),
			intval((isset($groupid) && $groupid ? $groupid : WPF()->usergroup->default_groupid)),
			(isset($site) ? sanitize_text_field($site) : '' ),
			( isset($timezone) ? sanitize_text_field($timezone) : 'UTC+0' ),
			( isset($about) ? stripslashes( wpforo_kses(trim($about), 'user_description') ) : '' ),
			( isset($last_login) ? $last_login : current_time('mysql', 1) )
        );
		return WPF()->db->query($sql);
	}
 	
 	function create( $data ){

		if(!wpforo_feature('user-register')){
			WPF()->notice->add('User registration is disabled.', 'error');
			return FALSE;
		}

		$user_fields = array();
		$user_fields_ignore = array( 'user_login', 'user_email', 'user_pass1', 'user_pass2' );

		if( !empty($data) ){
            if( wpfval($data, 'wpfreg') ){
                $user_fields = $data['wpfreg'];
            } else {
                $user_fields = $data;
            }
        }

		$user_fields = apply_filters( 'wpforo_create_profile', $user_fields );
		
		if( (isset($user_fields['error']) && $user_fields['error']) || !$user_fields ){
			return FALSE;
		}

		$this->login_min_length = apply_filters('wpforo_login_min_length', $this->login_min_length);
		$this->login_max_length = apply_filters('wpforo_login_max_length', $this->login_max_length);
		$this->pass_min_length = apply_filters('wpforo_pass_min_length', $this->pass_min_length);
		$this->pass_max_length = apply_filters('wpforo_pass_max_length', $this->pass_max_length);

		if( !wpforo_feature('user-register-email-confirm') && !empty($user_fields) && is_array($user_fields) && !empty($user_fields['user_pass1']) ){

		    remove_action('register_new_user', 'wp_send_new_user_notifications');
			add_action('register_new_user', array($this, 'send_new_user_notifications'));
			do_action( 'wpforo_create_profile_before', $user_fields );

			$errors = new WP_Error();
			
			extract($user_fields, EXTR_OVERWRITE);
			$sanitized_user_login = sanitize_user( $user_login );
			$user_email = apply_filters( 'user_registration_email', $user_email );
			$user_pass1 = trim(substr($user_pass1, 0, 100));
			$user_pass2 = trim(substr($user_pass2, 0, 100));
			$illegal_user_logins = array_map( 'strtolower', (array) apply_filters( 'illegal_user_logins', array() ) );

			if( $sanitized_user_login == '' ) {
				$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ) );
				WPF()->notice->add('Username is missed.', 'error');
				return FALSE;
			}
			elseif ( ! validate_username( $user_login ) ) {
				$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
				$sanitized_user_login = '';
				WPF()->notice->add('Illegal character in username.', 'error');
				$user_login = '';
				return FALSE;
			}
			elseif( strlen($user_login) < $this->login_min_length || strlen($user_login) > $this->login_max_length ){
				WPF()->notice->add( 'Username length must be between %d characters and %d characters.', 'error', array($this->login_min_length, $this->login_max_length) );
				return FALSE;
			}
			elseif ( username_exists( $sanitized_user_login ) ) {
				$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.' ) );
				WPF()->notice->add('Username exists. Please insert another.', 'error');
				return FALSE;
			}
			elseif ( in_array( strtolower( $sanitized_user_login ), $illegal_user_logins ) ) {
				$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: Sorry, that username is not allowed.' ) );
				WPF()->notice->add('ERROR: invalid_username. Sorry, that username is not allowed. Please insert another.', 'error');
				return FALSE;
			}
			elseif ( $user_email == '' ) {
				$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your email address.' ) );
				WPF()->notice->add('Insert your Email address.', 'error');
				return FALSE;
			}
			elseif ( ! is_email( $user_email ) ) {
				$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' ) );
				WPF()->notice->add('Invalid Email address', 'error');
				$user_email = '';
				return FALSE;
			}
			elseif ( email_exists( $user_email ) ) {
				$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.' ) );
				WPF()->notice->add('Email address exists. Please insert another.', 'error');
				return FALSE;
			}
			elseif( strlen($user_pass1) < $this->pass_min_length || strlen($user_pass1) > $this->pass_max_length ){
				WPF()->notice->add( 'Password length must be between %d characters and %d characters.', 'error', array($this->pass_min_length, $this->pass_max_length) );
				return FALSE;
			}
			elseif( $user_pass1 != $user_pass2 ) {
				WPF()->notice->add('Password mismatch.', 'error');
				return FALSE;
			}
			else{
				do_action( 'register_post', $sanitized_user_login, $user_email, $errors );
				$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );
				if( $errors->get_error_code()){
					$user_fields = array();
					foreach($errors->errors as $u_err) $user_fields[] = $u_err[0];
					WPF()->notice->add($user_fields, 'error');
					return FALSE;
				}
				$user_id = wp_create_user( $sanitized_user_login, $user_pass1, $user_email );
				if( !is_wp_error( $user_id ) && $user_id ){
                    $data['userid'] = $user_id;
                    $creds = array('user_login' => $sanitized_user_login, 'user_password' => $user_pass1 );
                    wp_signon( $creds );
                    do_action( 'wpforo_create_user_after', $data );
                    do_action( 'register_new_user', $user_id );
                    $data['wpfreg'] = wpforo_clear_array( $data['wpfreg'], $user_fields_ignore, 'key' );
                    $this->update( $data, 'full', false );
                    WPF()->notice->clear();
                    WPF()->notice->add('Success!', 'success');
                    return $user_id;
				}
			}
		}
		elseif( wpforo_feature('user-register-email-confirm') && !empty($user_fields['user_login']) && !empty($user_fields['user_email']) ){
            if( strlen($user_fields['user_login']) < $this->login_min_length || strlen($user_fields['user_login']) > $this->login_max_length ){
                WPF()->notice->add( 'Username length must be between %d characters and %d characters.', 'error', array($this->login_min_length, $this->login_max_length) );
                return FALSE;
            }
			$user_id = register_new_user( $user_fields['user_login'], $user_fields['user_email'] );
			if ( !is_wp_error( $user_id ) && $user_id ) {
                $data['userid'] = $user_id;
                $data['wpfreg'] = wpforo_clear_array( $data['wpfreg'], $user_fields_ignore, 'key' );
                $this->update( $data, 'full', false );
				do_action( 'wpforo_create_user_after', $data );
                WPF()->notice->clear();
				WPF()->notice->add('Success! Please check your mail for confirmation.', 'success');
				return $user_id;
			}
		}

		if(!empty($user_id->errors)){
			$user_fields = array();
			foreach($user_id->errors as $u_err) $user_fields[] = $u_err[0];
			WPF()->notice->add($user_fields, 'error');
			return FALSE;
		}

		WPF()->notice->add('Registration Error', 'error');
		return FALSE;
	}

    /**
     * @deprecated since 1.5.0
     * @deprecated No longer used by core and not recommended.
     * @deprecated Use WPF()->member->update()
     */
	function edit( $args = array() ){
		if( empty($args) && empty($_REQUEST['member']) ) return FALSE;
		if( empty($args) && !empty($_REQUEST['member']) ) $args = $_REQUEST['member'];

		$args = apply_filters( 'wpforo_edit_profile', $args );
		do_action( 'wpforo_edit_profile_before', $args );

		if( (isset($args['error']) && $args['error']) || !$args ){
			return FALSE;
		}
		
		extract($args, EXTR_OVERWRITE);

        $fields = array();
        $fields_types = array();

		if( isset($userid) && $userid ){
            $userid = intval($userid);

            $isRegister = ( isset($args['template']) && $args['template'] == 'register' ) ? true : false;
            if ( !$isRegister && ( !is_user_logged_in() || !WPF()->perm->user_can_manage_user( WPF()->current_userid, $userid ) ) ) {
                WPF()->notice->add('Permission denied', 'error');
                return FALSE;
            }

            if( isset($display_name) && $display_name ){
                $fields['display_name'] = sanitize_text_field(trim($display_name));
                $fields_types[] = '%s';
            }
            if( isset($user_email) && $user_email ){
                $user_email = sanitize_email($user_email);
                if ( ! is_email( $user_email ) ) {
                    WPF()->notice->add('Invalid Email address', 'error');
                    return FALSE;
                }elseif ( ( $owner_id = email_exists( $user_email ) ) && ( $owner_id != $userid ) ) {
                    WPF()->notice->add('This email address is already registered. Please insert another.', 'error');
                    return FALSE;
                }

                $fields['user_email'] = $user_email;
                $fields_types[] = '%s';
            }
            if( isset($user_nicename) && $user_nicename ){
                $user_nicename = sanitize_title( trim($user_nicename) );
                if( is_numeric($user_nicename) ){
                    WPF()->notice->add('Numerical nicknames are not allowed. Please insert another.', 'error');
                    return FALSE;
                }
                $sql = "SELECT `ID` FROM `".WPF()->db->users."` WHERE `ID` != ". intval($userid) ." AND ( `user_nicename` LIKE '".esc_sql($user_nicename)."' OR `ID` LIKE '".esc_sql($user_nicename)."' )";
                if( WPF()->db->get_var($sql)){
                    WPF()->notice->add('This nickname is already registered. Please insert another.', 'error');
                    return FALSE;
                }

                $fields['user_nicename'] = $user_nicename;
                $fields_types[] = '%s';

                WPF()->db->update(
                    WPF()->db->usermeta,
                    array('meta_value' => $user_nicename),
                    array('user_id' => $userid, 'meta_key' => 'nickname'),
                    array('%s'),
                    array('%d', '%s')
                );
            }

			if( $fields ){
				WPF()->db->update(
					WPF()->db->users,
					$fields,
					array('ID' => $userid),
					$fields_types,
					array('%d')
				);

				$this->reset($userid);
			}
			if( FALSE !== $this->edit_profile($args) ){
				do_action( 'wpforo_edit_profile_after', $args );
				WPF()->notice->add('Your profile data have been successfully updated.', 'success');
				return $userid;
			}
		}
		
		WPF()->notice->add('Something wrong with profile data.', 'error');
		return FALSE;
	}

    /**
     * @deprecated since 1.5.0
     * @deprecated No longer used by core and not recommended.
     * @deprecated Use WPF()->member->update()
     * @deprecated Or use WPF()->member->update_profile_field() for one field update
     */
    function edit_profile($args){
        if(empty($args)) return FALSE;
        if( !isset($args['userid']) || !$args['userid'] ) return FALSE;
        extract( $args, EXTR_OVERWRITE );

        $fields = array();
        $fields_types = array();
        $secondary_groups = (isset($secondary_groups)) ? $secondary_groups : ' ';

        if(isset($last_login) && $last_login){
            $fields['last_login'] = sanitize_text_field($last_login);
            $fields_types[] = '%s';
        }
        if(isset($groupid) && $groupid){
            $groupid = intval($groupid);
            if( !(!WPF()->current_object['user_is_same_current_user'] && wpforo_current_user_is('admin')) ) {
                $flds = $this->get_fields();
                if( !in_array($groupid, wpforo_parse_args($flds['groupid']['allowedGroupIds'])) ) $groupid = WPF()->usergroup->default_groupid;
            }
            $fields['groupid'] = $groupid;
            $fields_types[] = '%d';
        }
        if(isset($title) && $title){
            $fields['title'] = sanitize_text_field(trim($title));
            $fields_types[] = '%s';
        }
        if(isset($site)){
            $fields['site'] = sanitize_text_field(trim($site));
            $fields_types[] = '%s';
        }
        if(isset($icq)){
            $fields['icq'] = sanitize_text_field(trim($icq));
            $fields_types[] = '%s';
        }
        if(isset($aim)){
            $fields['aim'] = sanitize_text_field(trim($aim));
            $fields_types[] = '%s';
        }
        if(isset($yahoo)){
            $fields['yahoo'] = sanitize_text_field(trim($yahoo));
            $fields_types[] = '%s';
        }
        if(isset($msn)){
            $fields['msn'] = sanitize_text_field(trim($msn));
            $fields_types[] = '%s';
        }
        if(isset($facebook)){
            $fields['facebook'] = sanitize_text_field(trim($facebook));
            $fields_types[] = '%s';
        }
        if(isset($twitter)){
            $fields['twitter'] = sanitize_text_field(trim($twitter));
            $fields_types[] = '%s';
        }
        if(isset($gtalk)){
            $fields['gtalk'] = sanitize_text_field(trim($gtalk));
            $fields_types[] = '%s';
        }
        if(isset($skype)){
            $fields['skype'] = sanitize_text_field(trim($skype));
            $fields_types[] = '%s';
        }
        if(isset($signature)){
            $fields['signature'] = stripslashes(wpforo_kses(trim($signature), 'user_description'));
            $fields_types[] = '%s';
        }
        if(isset($about)){
            $fields['about'] = stripslashes(wpforo_kses(trim($about), 'user_description'));
            $fields_types[] = '%s';
        }
        if(isset($occupation)){
            $fields['occupation'] = stripslashes(sanitize_text_field(trim($occupation)));
            $fields_types[] = '%s';
        }
        if(isset($location)){
            $fields['location'] = stripslashes(sanitize_text_field(trim($location)));
            $fields_types[] = '%s';
        }
        if(isset($timezone)){
            $fields['timezone'] = sanitize_text_field(trim($timezone));
            $fields_types[] = '%s';
        }
        if(isset($avatar_type) && $avatar_type != 'gravatar' && isset($avatar_url) && $avatar_url){
            $fields['avatar'] = esc_url(trim($avatar_url));
            $fields_types[] = '%s';
        }
        if(isset($avatar_type) && $avatar_type == 'gravatar'){
            $fields['avatar'] = '';
            $fields_types[] = '%s';
        }
        if(isset($secondary_groups) && !is_null($secondary_groups)){
            if( is_array( $secondary_groups ) && !empty( $secondary_groups ) ){
                $secondary_groups = array_map('intval', $secondary_groups);
                $secondary_groups = implode(',', $secondary_groups);
            }
            $fields['secondary_groups'] = stripslashes(sanitize_text_field(trim($secondary_groups)));
            $fields_types[] = '%s';
        }

        $this->reset($userid);

        $result = true;
        if($fields){
            $result = WPF()->db->update(
                WPF()->tables->profiles,
                $fields,
                array('userid' => intval($userid)),
                $fields_types,
                array('%d')
            );

            if( $result !== FALSE && $userid ){
                if(isset($fields['site'])){
                    WPF()->db->query("UPDATE `".WPF()->db->users."` SET `user_url` = '" . esc_sql($fields['site']) . "' WHERE `ID` = " . intval($userid) );
                }
                if(isset($fields['about'])){
                    update_user_meta( $userid, 'description', $fields['about'] );
                }
            }
        }

        return $result;
    }

    /**
     * Updates user data
     * @since  1.5.0
     *
     * @param   array       $data - User data as a simple array( field => value ) OR
     *                      $data['member'] = array( field => value )   user and profile fields - Account form
     *                      $data['wpfreg'] = array( field => value )   user and profile fields - Registration form
     *                      $data['data']   = array( field => value )   user custom fields
     *
     *                      $data['member']['userid'] or $data['userid'] is required
     *
     * @param   string      $type                    User data update types (comma separated)
     *                      $type = 'full'           user fields, profile fields, custom fields
     *                      $type = 'user'           only user fields (wp_users table)
     *                      $type = 'profile'        only profile fields (wp_wpforo_profiles table)
     *                      $type = 'custom_fields'  only custom fields (wp_wpforo_profiles table > fields column)
     *                      $type = 'profile, custom_fields'
     *
     * @param   boolean     $check_permissions      Whether check the current editor permissions or not
     *
     * @return  false|int  User ID
     */
	public function update( $data, $type = 'full', $check_permissions = true ){

        $type = explode(',', $type);
        $type = array_map('trim', $type );
        $varname = WPF()->form->varname();
        $template = (wpfval(WPF()->current_object, 'template')) ? WPF()->current_object['template'] : false;

        if( $template == 'account' ){
            $form = 'member';
            $form_fields = $this->get_account_fields();
        } elseif( $template == 'register' ){
            $form = 'wpfreg';
            $form_fields = $this->get_register_fields();
        } else{
            $form = 'member';
            $form_fields = $this->get_account_fields();
            $form = apply_filters( 'wpforo_user_update_form', $form );
            $form_fields = apply_filters( 'wpforo_user_update_fields', $form_fields );
        }

        if( !$form ){ WPF()->notice->add( 'Form name not found', 'error'); return false; }
        if( !$form_fields ){ WPF()->notice->add( 'Form template not found', 'error'); return false; }

	    if( !wpfkey($data, $form) ){
	        if( in_array('custom_fields', $type) && count($type) == 1 ){
                if( !wpfval($data, $varname) ){
                    $data[ $varname ] = $data;
                    if( wpfkey($data, $varname, 'userid') ) unset($data[ $varname ]['userid']);
                }
            } else {
                $data[ $form ] = $data;
            }
        }
        if( wpfval($data, 'userid') && !wpfval($data, $form, 'userid') ){
            $data[ $form ]['userid'] = $data['userid'];
        }

	    if( wpfval($data, $form, 'userid') ){

            $result_user = true;
            $result_fields = true;
            $result_profile = true;
            $custom_fields = array();

            //Define $user
            $user = $data[ $form ];

            //Define $userid
            $userid = intval($data[ $form ]['userid']);

            //Check profile editor permissions
            if( $check_permissions ) WPF()->perm->can_edit_user( $userid );

            //Check custom fields and merge to $user array
            if( wpfval($data, $varname) && is_array($data[$varname]) && !empty($data[$varname]) ){
                $custom_fields = $data[$varname];
                $user = array_merge( $custom_fields, $user );
            }

            //Check file uploading custom fields and merge to $user array
            $file_data = isset($_FILES[$varname]['name']) && $_FILES[$varname]['name'] && is_array($_FILES[$varname]['name']) ? array_filter($_FILES[$varname]['name']) : array();
            $file_fields = WPF()->form->prepare_file_args( $file_data, $userid );
            if( wpfval( $file_fields, 'fields') ) {
                $user = array_merge( $file_fields['fields'], $user );
                $custom_fields = ( !empty($custom_fields) ) ? array_merge($custom_fields, $file_fields['fields']) : $file_fields['fields'];
            }

            //Hooks
            $user = apply_filters( 'wpforo_update_profile', $user );
            do_action( 'wpforo_update_profile_before', $user );
            if( wpfval($user, 'error') || empty($user) ){
                $error_message = ( wpfval($user, 'error_message') ) ? sanitize_text_field($user['error_message']) : 'Unknown error in profile editing hook. Please disable all plugins and check it again.';
                WPF()->notice->add( $error_message, 'error');
                return false;
            }

            //Validate fields
            $result = WPF()->form->validate( $user, $form_fields );
            if( wpfval( $result, 'error' ) ){
                if(is_admin() && wpfval($result['error'], 0)){
                    wp_die($result['error'][0]);
                }
                else{
                    WPF()->notice->add( $result['error'], 'error');
                    return false;
                }
            }

            //Sanitize fields
            $user = WPF()->form->sanitize( $user, $form_fields );

            //Update User Fields
            if( !empty($user) && ( in_array('full', $type) || in_array('user', $type) ) ){
                $result_user = $this->update_user_fields( $userid, $user, false );
            }

            //Update Profile Fields
            if( !empty($user) && (in_array('full', $type) || in_array('profile', $type) ) ){
                $result_profile = $this->update_profile_fields( $userid, $user, false );
            }

            //Password field validation and update
            $result_password = WPF()->form->validate_password( $user );
            if( $result_password === false ){
                $result_password = true;
            }
            if( wpfval( $result_password, 'error' ) ){
                WPF()->notice->add( $result_password['error'], 'error');
                $result_password = false;
            }
            if( $result_password && wpfval($user, 'old_pass') && wpfval($user, 'user_pass1') ){
                $result_password = WPF()->member->change_password( $user['old_pass'], $user['user_pass1'], $userid );
            }

            //Upload avatar
            if( wpfval($user, 'avatar_type') && $user['avatar_type'] == 'custom' ){
                WPF()->member->upload_avatar( $userid );
            }

            //Update Custom Fields
            if( !empty($custom_fields) && ( in_array('full', $type) || in_array('custom_fields', $type) ) ){
                $result_fields = $this->update_custom_fields( $userid, $custom_fields, false );
            }

            //Upload Files from Custom Fields
            if ( wpfval( $file_fields, 'files' ) ) {
                $this->upload_files( $file_fields['files'] );
            }

            //Reset this user cache
            $this->reset($userid);

            if( $result_user === false || $result_profile === false || $result_fields === false || $result_password === false ){
                return false;
            } else{
                WPF()->notice->add('Profile updated successfully', 'success');
                do_action( 'wpforo_update_profile_after', $user );
                return $user;
            }
        }

        return false;
    }

    public function update_user_fields( $userid, $data, $check_permissions = true ){

        $result_user = true;

        if( $check_permissions ){
            WPF()->perm->can_edit_user( $userid );
        }

        //User Fields
        if( wpfkey($data, 'display_name') ){
            $user_fields['display_name'] = $data['display_name'];
            $user_fields_types[] = '%s';
        }
        if( wpfkey($data, 'user_email') ){
            $user_fields['user_email'] = $data['user_email'];
            $user_fields_types[] = '%s';
        }
        if( wpfkey($data, 'user_nicename') ){
            if( !wpfval($data, 'user_nicename') ){
                $user_info = get_userdata( $userid );
                $data['user_nicename'] = sanitize_title( $user_info->user_nicename );
            }
            $user_fields['user_nicename'] = $data['user_nicename'];
            WPF()->db->update( WPF()->db->usermeta, array('meta_value' => $data['user_nicename'] ), array('user_id' => $userid, 'meta_key' => 'nickname'), array('%s'), array('%d', '%s') );
            $user_fields_types[] = '%s';
        }
        if( wpfkey($data, 'site') ){
            $user_fields['user_url'] = $data['site'];
            $user_fields_types[] = '%s';
        }

        if( !empty($user_fields) ){
            $result_user = WPF()->db->update(
                WPF()->db->users,
                $user_fields,
                array('ID' => $userid),
                $user_fields_types,
                array('%d')
            );
            if( false === $result_user ) {
                WPF()->notice->add('User data update failed', 'error');
                if( WPF()->db->last_error ){
                    WPF()->notice->add( sanitize_text_field( WPF()->db->last_error ), 'error');
                }
            }
        }
        return $result_user;
    }

    public function update_profile_field( $userid, $field_name, $field_value = NULL ){
        $result = false;
        if( $field_name && !is_null($field_value) ) {
            $sql = "UPDATE `" . WPF()->tables->profiles . "` SET `" . esc_sql( $field_name ) . "` = '" . esc_sql( $field_value ) . "' WHERE `userid` = " . wpforo_bigintval( $userid );
            $result = WPF()->db->query( $sql );
        }
        return $result;
    }

    public function update_profile_fields( $userid, $data, $check_permissions = true ){

        $result_profile = true;

        if( $check_permissions ){
            WPF()->perm->can_edit_user( $userid );
        }

	    $profile_fields = array();
	    $profile_fields_types = array();

        //Profile Fields
        if( wpfkey($data, 'last_login') ){
            $profile_fields['last_login'] = $data['last_login'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'groupid') ){
            $profile_fields['groupid'] = $data['groupid'];
            $profile_fields_types[] = '%d';
        }
        if( wpfkey($data, 'title') ){
            $profile_fields['title'] = $data['title'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'site') ){
            $profile_fields['site'] = $data['site'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'icq') ){
            $profile_fields['icq'] = $data['icq'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'aim') ){
            $profile_fields['aim'] = $data['aim'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'yahoo') ){
            $profile_fields['yahoo'] = $data['yahoo'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'msn') ){
            $profile_fields['msn'] = $data['msn'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'facebook') ){
            $profile_fields['facebook'] = $data['facebook'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'twitter') ){
            $profile_fields['twitter'] = $data['twitter'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'gtalk') ){
            $profile_fields['gtalk'] = $data['gtalk'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'skype') ){
            $profile_fields['skype'] = $data['skype'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'signature') ){
            $profile_fields['signature'] = $data['signature'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'about') ){
            $profile_fields['about'] = $data['about'];
            $profile_fields_types[] = '%s';
            update_user_meta( $userid, 'description', $data['about'] );
        }
        if( wpfkey($data, 'occupation') ){
            $profile_fields['occupation'] = $data['occupation'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'location') ){
            $profile_fields['location'] = $data['location'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'timezone') ){
            $profile_fields['timezone'] = $data['timezone'];
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'avatar_type') && $data['avatar_type'] != 'gravatar' &&  wpfval($data, 'avatar_url') ){
            $profile_fields['avatar'] = esc_url(trim($data['avatar_url']));
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'avatar_type') && $data['avatar_type'] == 'gravatar'){
            $profile_fields['avatar'] = '';
            $profile_fields_types[] = '%s';
        }
        if( wpfkey($data, 'secondary_groups') && is_array($data['secondary_groups']) ){
            $data['secondary_groups'] = array_filter($data['secondary_groups']);
            $profile_fields['secondary_groups'] = implode( ',', $data['secondary_groups'] );
            $profile_fields_types[] = '%s';
        }

        if( !empty($profile_fields) ){
            $result_profile = WPF()->db->update(
                WPF()->tables->profiles,
                $profile_fields,
                array('userid' => intval($userid)),
                $profile_fields_types,
                array('%d') );
            if( false === $result_profile ) {
                WPF()->notice->add('User profile update failed', 'error');
                if( WPF()->db->last_error ){
                    WPF()->notice->add( sanitize_text_field( WPF()->db->last_error ), 'error');
                }
            }
        }
        return $result_profile;
    }

    public function update_custom_field( $userid, $field_name, $field_value = NULL ){
        $result = false;
	    $fields = $this->get_custom_fields( $userid );
	    if( !empty($fields) && $field_name && !is_null($field_value) ){
	        foreach( $fields as $name => $value ){
	            if( $name == $field_name ){
                    $fields[ $name ] = $field_value;
                }
            }
            $custom_fields = array_filter($fields);
            $custom_fields = wpforo_unslashe($custom_fields);
            $custom_fields = wpforo_decode($custom_fields);
            $custom_fields = wpforo_encode($custom_fields);
            $fields_json = json_encode($custom_fields, JSON_UNESCAPED_UNICODE);
            $sql = "UPDATE `" . WPF()->tables->profiles . "` SET `fields` = %s WHERE `userid` = %d;";
            $sql = WPF()->db->prepare($sql, $fields_json, $userid);
            $result = WPF()->db->query($sql);
        }
        return $result;
    }

    public function update_custom_fields( $userid, $custom_fields, $check_permissions = true ){

        $result_fields = true;

	    if( !empty($custom_fields) ){
	        if( $check_permissions ){
                WPF()->perm->can_edit_user( $userid );
            }
            $custom_fields = wpforo_unslashe($custom_fields);
            $custom_fields = wpforo_decode($custom_fields);
            $custom_fields = wpforo_encode($custom_fields);
            $data_old = $this->get_custom_fields( $userid );
            if ($data_old && is_array($data_old)) {
                $custom_fields = wp_parse_args($custom_fields, $data_old);
            }
            $fields_json = json_encode($custom_fields, JSON_UNESCAPED_UNICODE);
            $sql = "UPDATE `" . WPF()->tables->profiles . "` SET `fields` = %s WHERE `userid` = %d;";
            $sql = WPF()->db->prepare($sql, $fields_json, $userid);
            $result_fields = WPF()->db->query($sql);
            if( false === $result_fields ) {
                WPF()->notice->add('User custom field update failed', 'error');
                if( WPF()->db->last_error ){
                    WPF()->notice->add( sanitize_text_field( WPF()->db->last_error ), 'error');
                }
            }
        }

        return $result_fields;
    }

    public function upload_avatar( $userid = 0 ){

        $userid = intval($userid);

        if( wpfval( WPF()->current_object, 'template') ){
            $template = WPF()->current_object['template'];
            if( $template == 'account' ){
                if( !WPF()->perm->usergroup_can('upa') ) return;
            }
        }

        if( !$user = $this->get_member($userid) ) return;
        $user_nicename = urldecode($user['user_nicename']);
        if(isset($_FILES['avatar']) && !empty($_FILES['avatar']) && isset($_FILES['avatar']['name']) && $_FILES['avatar']['name']){
            $name = sanitize_file_name($_FILES['avatar']['name']); //myimg.png
            $type = sanitize_mime_type($_FILES['avatar']['type']); //image/png
            $tmp_name = sanitize_text_field($_FILES['avatar']['tmp_name']); //D:\wamp\tmp\php986B.tmp
            $error = sanitize_text_field($_FILES['avatar']['error']); //0
            $size = intval($_FILES['avatar']['size']); //6112

            if( $size > 2*1048576 ){
                WPF()->notice->clear();
                WPF()->notice->add('Avatar image is too big maximum allowed size is 2MB', 'error');
                return FALSE;
            }

            if( $error ){
                $error = wpforo_file_upload_error($error);
                WPF()->notice->clear();
                WPF()->notice->add($error, 'error');
                return FALSE;
            }

            $upload_dir = wp_upload_dir();
            $uplds_dir = $upload_dir['basedir']."/wpforo";
            $avatar_dir = $upload_dir['basedir']."/wpforo/avatars";
            if(!is_dir($uplds_dir)) wp_mkdir_p($uplds_dir);
            if(!is_dir($avatar_dir)) wp_mkdir_p($avatar_dir);

            $ext = pathinfo($name, PATHINFO_EXTENSION);
            if( !wpforo_is_image($ext) ){
                WPF()->notice->clear();
                WPF()->notice->add('Incorrect file format. Allowed formats: jpeg, jpg, png, gif.', 'error');
                return FALSE;
            }

            $fnm = pathinfo($user_nicename, PATHINFO_FILENAME);
            $fnm = str_replace(' ', '-', $fnm);
            while(strpos($fnm, '--') !== FALSE) $fnm = str_replace('--', '-', $fnm);
            $fnm = preg_replace("/[^-a-zA-Z0-9]/", "", $fnm);
            $fnm = trim($fnm, "-");

            $avatar_fname = $fnm . ( $fnm ? '_' : '' ) . $userid . "." . strtolower($ext);
            $avatar_fname_orig = $fnm . ( $fnm ? '_' : '' ) . $userid . "." . $ext;
            $avatar_path = $avatar_dir . "/" . $avatar_fname;
            $avatar_path_orig = $avatar_dir . "/" . $avatar_fname_orig;

            if(is_dir($avatar_dir)){
                if(move_uploaded_file($tmp_name, $avatar_path)) {
                    $image = wp_get_image_editor( $avatar_path );
                    if ( ! is_wp_error( $image ) ) {
                        $image->resize( 150, 150, true );
                        $saved = $image->save( $avatar_path );
                        if(! is_wp_error( $saved ) && $avatar_fname != $avatar_fname_orig ) {
                            if ( defined (PHP_OS) && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') unlink( $avatar_path_orig );
                        }
                    }
                    $blog_url = preg_replace('#^https?\:#is', '', $upload_dir['baseurl']);
                    WPF()->db->update(WPF()->tables->profiles, array('avatar' => $blog_url . "/wpforo/avatars/" . $avatar_fname), array('userid' => intval($userid)), array('%s'), array('%d'));
                    $this->reset($userid);
                }
            }
        }
    }

    public function upload_files( $file_fields ){
        if ( !empty( $file_fields ) ) {
            $varname = WPF()->form->varname();
            foreach( $file_fields as $field_name => $file_path ) {
                if( wpfval( $_FILES, $varname, 'tmp_name', $field_name ) &&
                    !move_uploaded_file( $_FILES[ $varname ]['tmp_name'][$field_name], $file_path )
                ) {
                    WPF()->notice->add('Sorry, there was an error uploading attached file', 'error');
                }
            }
        }
    }

    public function get_custom_field( $userid, $field_name ) {
        $field_value = '';
        if( $userid ){
            $sql = WPF()->db->prepare( "SELECT `fields` FROM `" . WPF()->tables->profiles . "` WHERE userid = %d", $userid);
            $fields = WPF()->db->get_var( $sql );
            if( $fields ) {
                $data = (array) json_decode( $fields, true );
                if( !empty($data) ){
                    $data = wpforo_unslashe($data);
                    if( wpfkey($data, $field_name) ){
                        $field_value = $data[ $field_name ];
                    }
                }
            }
        }
        return $field_value;
    }

    public function get_custom_fields( $userid ) {
        $data = array();
        if( $userid ){
            $sql = WPF()->db->prepare( "SELECT `fields` FROM `" . WPF()->tables->profiles . "` WHERE userid = %d", $userid);
            $fields = WPF()->db->get_var( $sql );
            if( $fields ) {
                $data = (array) json_decode( $fields, true );
            }
            $data = wpforo_unslashe($data);
        }
        return $data;
    }

	public function change_password($old_passw, $new_passw, $userid){
        if( !$userid = wpforo_bigintval($userid) ){
            WPF()->notice->clear();
            WPF()->notice->add('Userid is wrong', 'error');
            return false;
        }

        $user = $this->get_member($userid);
        if( !apply_filters('wpforo_change_password_validate', true, $old_passw, $new_passw, $user) ) return false;

        if ( wp_check_password( $old_passw, $user['user_pass'], $userid) ){
            wp_set_password( $new_passw, $userid );

            /**
             *  Login user after change password with new pass
             */
            $creds = array('user_login' => sanitize_user( $user['user_login'] ), 'user_password' => $new_passw );
            wp_signon($creds);

            WPF()->notice->add('Password successfully changed', 'success');
            return true;
        }

        WPF()->notice->clear();
        WPF()->notice->add('Old password is wrong', 'error');
        return false;
    }

    public function synchronize_user($userid, $roles_usergroups = array()){
        $groupid = false;
	    if(!$userid) return FALSE;
        $user = get_userdata($userid);
		if( !empty($roles_usergroups) && !empty($user->roles) ){
            foreach( $user->roles as $role ){
                if( isset($roles_usergroups[$role]) ){
                    $groupid = $roles_usergroups[$role]; break;
                }
            }
        }
        if( !$groupid ){
            if( is_super_admin( $userid ) || in_array('administrator', $user->roles) ){
                $groupid = 1;
            }elseif( in_array('editor', $user->roles) ){
                $groupid = 2;
            }elseif( in_array('customer', $user->roles) ){
                $groupid = 5;
            }else{
                $groupid = WPF()->usergroup->default_groupid;
            }
        }
        $insert_groupid = ( isset($_POST['wpforo_usergroup']) && !wpforo_feature('role-synch') ) ? intval($_POST['wpforo_usergroup']) : $groupid;
		$insert_timezone = (isset($_POST['wpforo_usertimezone'])) ? sanitize_text_field($_POST['wpforo_usertimezone']) : '';
		$about = get_user_meta( $userid, 'description', true );
		return $this->add_profile( 
						array(  'userid' => wpforo_bigintval($userid),
								'username' => sanitize_user($user->user_login), 
								'groupid' => intval($insert_groupid), 
								'site' => esc_url($user->user_url), 
								'timezone' => sanitize_text_field($insert_timezone), 
								'about' => stripslashes( wpforo_kses(trim($about), 'user_description') ), 
								'last_login' => sanitize_text_field($user->user_registered) ) );
	}

    public function synchronize_users($limit = NULL){
		
		if( is_multisite() ){
			$sql = "SELECT `user_id` FROM `".WPF()->db->usermeta."` WHERE `meta_key` LIKE '".WPF()->blog_prefix."capabilities' AND `user_id` NOT IN( SELECT `userid` FROM `".WPF()->tables->profiles."` ) ORDER BY `user_id` ASC";
		} else {
			$sql = "SELECT `ID` as user_id FROM `".WPF()->db->users."` WHERE `ID` NOT IN( SELECT `userid` FROM `".WPF()->tables->profiles."` ) ORDER BY `ID` ASC";
		}
		if( !is_null($limit) ){
		    $sql .= " LIMIT " . intval($limit);
        }

		$userids = WPF()->db->get_col($sql);
		if( !empty($userids) ){
            $roles_usergroups = WPF()->usergroup->get_role_usergroup_relation();
			foreach($userids as $userid){
				$this->synchronize_user($userid, $roles_usergroups);
			}
		}

		## -- START -- delete profiles where not participant on multisite blog
		if( is_multisite() ){
            $sql = "DELETE FROM `".WPF()->tables->profiles."` WHERE `userid` NOT IN( SELECT `user_id` FROM `".WPF()->db->usermeta."` WHERE `meta_key` LIKE '".WPF()->blog_prefix."capabilities' )";
            WPF()->db->query($sql);
		}
        ## -- END -- delete profiles where not participant on multisite blog
    }

    public function get_member($args){
        if(!$args) return $this->get_guest();

        $cache = WPF()->cache->on('memory_cashe');

        $default = array(
            'userid' => NULL, // $userid
            'user_nicename' => '' // $user_nicename
        );

        if( is_numeric($args) ){
            $args = array( 'userid' => $args );
        }elseif ( !is_array($args) ){
            $args = array( 'user_nicename' => $args );
        }

		$args = wpforo_parse_args( $args, $default );
		
		if(isset($args['userid'])){
			if( $cache && isset(self::$cache['user'][$args['userid']]) ){
				return self::$cache['user'][$args['userid']];
			}
		}
		
        extract($args);

        $do_db_cache =  wpforo_feature('member_cashe');

        $userid = wpforo_bigintval($userid);

        $user_meta_obj = true;
        $member = array();
        if( $do_db_cache ){
            if( $user_nicename ){
                $user_obj = get_user_by( 'user_nicename', $user_nicename );
                if( !empty($user_obj) ) $userid = $user_obj->ID;
            }
            $member = get_user_meta( $userid, '_wpf_member_obj', true );
        }

        if(empty($member)){
            $user_meta_obj = false;
            $sql = "SELECT *, ug.name AS groupname, ug.color AS color FROM `".WPF()->db->users."` u 
            INNER JOIN `".WPF()->tables->profiles."` p ON p.`userid` = u.`ID`
            LEFT JOIN `".WPF()->tables->usergroups."` ug ON ug.`groupid` = p.`groupid`";
            $wheres = array();
            if($userid)  $wheres[] = "`ID` = $userid";
            if($user_nicename)   $wheres[] = "`user_nicename` = '"   . esc_sql($user_nicename) . "'";
            if( !empty($wheres) ) $sql .= " WHERE " . implode($wheres, " AND ");
            $member = WPF()->db->get_row($sql, ARRAY_A);
        }

        if(!empty($member)) {
            $member['profile_url'] = $this->profile_url( $member );
            $member['stat'] = $this->get_stat( $member, false, true );
            if( $do_db_cache ){
                if(!$user_meta_obj) update_user_meta( $userid, '_wpf_member_obj', $member );
            }
        }

        if($cache && isset($userid) && $member){
            return self::$cache['user'][$userid] = $member;
        }else{
            return $member;
        }
	}

    public function get_members($args = array(), &$items_count = 0){
		
		$default = array(
		  'include' => array(), // array( 2, 10, 25 )
	  	  'exclude' => array(),  // array( 2, 10, 25 )
	  	  'status' => array('active', 'banned'),  // 'active', 'blocked', 'trashed', 'spamer'
		  'groupid' => NULL, // groupid
		  'online_time' => NULL, // groupid
		  'orderby' => 'userid', //
		  'order' => 'ASC', // ASC DESC
		  'offset' => 0, // OFFSET
		  'row_count' => NULL, // ROW COUNT
		  'groupids' => array(), // array( 1, 2 )
		);
		
		$args = wpforo_parse_args( $args, $default );
		if(!empty($args)){
			extract($args, EXTR_OVERWRITE);
			
			$include = wpforo_parse_args( $include );
			$exclude = wpforo_parse_args( $exclude );
			
			$sql = "SELECT *, ug.name AS groupname, ug.color AS color FROM `".WPF()->db->users."` u 
				INNER JOIN `".WPF()->tables->profiles."` p ON p.`userid` = u.`ID`
				LEFT JOIN `".WPF()->tables->usergroups."` ug ON ug.`groupid` = p.`groupid`";
			$wheres = array();
			if(!empty($include))        $wheres[] = " u.`ID` IN(" . implode(', ', array_map('intval', $include)) . ")";
			if(!empty($exclude))        $wheres[] = " u.`ID` NOT IN(" . implode(', ', array_map('intval', $exclude)) . ")";
			if(!empty($status))         $wheres[] = " p.`status` IN('" . implode("','", array_map('esc_sql', array_map('sanitize_text_field', $status))  ) . "')";
			if(!empty($groupids))       $wheres[] = " (p.`groupid` IN(" . implode(', ', array_map('intval', $groupids)) . ") OR CONCAT(',', p.`secondary_groups`, ',') REGEXP ',(" . implode('|', array_map('intval', $groupids)) . "),' )";
			if(!is_null($groupid))      $wheres[] = " (p.`groupid` = " . intval($groupid) . " OR FIND_IN_SET(" . intval($groupid) . ", p.`secondary_groups`) ) ";
			if(!is_null($online_time))  $wheres[] = " p.`online_time` > " . intval($online_time);

			if(!empty($wheres)) $sql .= " WHERE " . implode($wheres, " AND ");
			
			$item_count_sql = preg_replace('#SELECT.+?FROM#isu', 'SELECT count(*) FROM', $sql);
            $item_count_sql = preg_replace('#ORDER.+$#is', '', $item_count_sql);
			if( $item_count_sql ) $items_count = WPF()->db->get_var($item_count_sql);
			
			if( $orderby == 'groupid' ) $orderby = 'p.`groupid`';
			$sql .= esc_sql(" ORDER BY $orderby " . $order);
			if($row_count) $sql .= esc_sql(" LIMIT $offset,$row_count");
			
			return WPF()->db->get_results($sql, ARRAY_A);
		}
	}

    public function search($needle, $fields = array(), $limit = NULL){
		
		if($needle != ''){
			$needle = sanitize_text_field($needle);
			if(empty($fields)){
				$fields = array( 
				  'title',
				  'user_nicename',
				  'user_email',
				  'signature'
				);
			}
			
			$sql = "SELECT `ID` FROM `".WPF()->db->users."` u 
			    INNER JOIN `".WPF()->tables->profiles."` p ON p.`userid` = u.`ID`";
			$wheres = array();
			
			foreach($fields as $field){
				$field = sanitize_text_field($field);
				$wheres[] = "`".esc_sql($field)."` LIKE '%" . esc_sql($needle) ."%'";
			}
			
			if(!empty($wheres)){
				$sql .= " WHERE " . implode($wheres, " OR ");
				if( $limit ) $sql .= " LIMIT " . intval($limit);
				
				return WPF()->db->get_col($sql);
			}else{
				return array();
			}
		}else{
			return array();
		}
		
	}

	public function filter($args, $limit = NULL){
		if($args && is_array($args)){
            $sql = "SELECT `ID` FROM `".WPF()->db->users."` u 
			    INNER JOIN `".WPF()->tables->profiles."` p ON p.`userid` = u.`ID`";
            $wheres = array();

            foreach($args as $field => $needle){
                $field = sanitize_text_field($field);
                $needle = sanitize_text_field($needle);
                $wheres[] = "`".esc_sql($field)."` LIKE '%" . esc_sql($needle) ."%'";
			}

			if($wheres){
				$sql .= " WHERE " . implode($wheres, " AND ");
				if( $limit ) $sql .= " LIMIT " . intval($limit);

				return WPF()->db->get_col($sql);
			}
		}

        return array();
	}

    public function ban($userid){
		if( $userid == WPF()->current_userid ){
			WPF()->notice->add('You can\'t make yourself banned user', 'error');
			return FALSE;
		}
		if( !WPF()->perm->usergroup_can('bm') || !WPF()->perm->user_can_manage_user( WPF()->current_userid, intval( $userid ) )){
			WPF()->notice->add('Permission denied for this action', 'error');
			return FALSE;
		}
		if( FALSE !== WPF()->db->update(
				WPF()->tables->profiles,
				array('status' => 'banned'),
				array('userid' => intval( $userid )),
				array('%s'),
				array('%d')
			) 
		){
			$this->reset($userid);
			WPF()->notice->add('User successfully banned from wpforo', 'success');
			return TRUE;
		}
		
		WPF()->notice->add('User ban action error', 'error');
		return FALSE;
	}

    public function unban($userid){
		if( !WPF()->perm->usergroup_can('bm') || !WPF()->perm->user_can_manage_user( WPF()->current_userid, intval( $userid ) )){
			WPF()->notice->add('Permission denied for this action', 'error');
			return FALSE;
		}
		if( FALSE !== WPF()->db->update(
				WPF()->tables->profiles,
				array('status' => 'active'),
				array('userid' => intval( $userid )),
				array('%s'),
				array('%d')
			) 
		){
			$this->reset($userid);
			WPF()->notice->add('User successfully unbanned from wpforo', 'success');
			return TRUE;
		}
		
		WPF()->notice->add('User unban action error', 'error');
		return FALSE;
	}
	
	/**
	* 
	* @param int $userid
	* @param int $reassign
	* 
	* @return bool true | false if user successfully deleted 
	*/
	public function delete( $userid, $reassign = NULL ){
		if( !($userid = intval($userid)) ) return FALSE;
		if( !WPF()->perm->usergroup_can('dm') || !WPF()->perm->user_can_manage_user( WPF()->current_userid, intval( $userid ) )){
			WPF()->notice->add('Permission denied for this action', 'error');
			return FALSE;
		}

		do_action('wpforo_before_delete_user', $userid, $reassign);
		
		if( !($reassign = intval($reassign)) ){
			if( $postids = WPF()->db->get_col( WPF()->db->prepare( "SELECT `postid` FROM `".WPF()->tables->posts."` WHERE userid = %d", $userid ) ) ){
				foreach( $postids as $postid ) WPF()->post->delete($postid);
			}
			
			if( $topicids = WPF()->db->get_col( WPF()->db->prepare( "SELECT `topicid` FROM `".WPF()->tables->topics."` WHERE userid = %d", $userid ) ) ){
				foreach( $topicids as $topicid ) WPF()->topic->delete($topicid, false);
			}
		}else{
			WPF()->db->update( WPF()->tables->topics, array('userid' => $reassign), array('userid' => $userid) );
			WPF()->db->update( WPF()->tables->posts, array('userid' => $reassign), array('userid' => $userid) );
			WPF()->db->update( WPF()->tables->likes, array('post_userid' => $reassign), array('post_userid' => $userid) );
			WPF()->db->update( WPF()->tables->votes, array('post_userid' => $reassign), array('post_userid' => $userid) );
			if( $user_stats = WPF()->db->get_row(
					WPF()->db->prepare( "SELECT 
							SUM(`posts`) AS posts, 
							SUM(`questions`) AS questions, 
							SUM(`answers`) AS answers, 
							SUM(`comments`) AS comments 
						 FROM `".WPF()->tables->profiles."` 
						 WHERE `userid` IN( %d , %d )", $userid, $reassign
					), 
					ARRAY_A 
				) 
			){
				WPF()->db->update(
					WPF()->tables->profiles,
					array(
						'posts' => $user_stats['posts'],
						'questions' => $user_stats['questions'],
						'answers' => $user_stats['answers'],
						'comments' => $user_stats['comments']
					),
					array('userid' => $reassign),
					array('%d','%d','%d','%d'),
					array('%d')
				);
			}
		}
		
		WPF()->db->delete(
			WPF()->tables->subscribes, array( 'userid' => $userid ), array( '%d' )
		);
		
		WPF()->db->delete(
			WPF()->tables->views, array( 'userid' => $userid ), array( '%d' )
		);
		
		WPF()->db->delete(
			WPF()->tables->likes, array( 'userid' => $userid ), array( '%d' )
		);
		
		WPF()->db->delete(
			WPF()->tables->votes, array( 'userid' => $userid ), array( '%d' )
		);
		
		if( FALSE !== WPF()->db->delete(
				WPF()->tables->profiles, array( 'userid' => $userid ), array( '%d' )
			)
		){

            do_action('wpforo_after_delete_user', $userid, $reassign);

			WPF()->notice->add('User successfully deleted from wpforo', 'success');
			return TRUE;
		}
		
		WPF()->notice->add('User delete error', 'error');
		return FALSE;
	}
	
	public function avatar($member, $attr = '', $size = ''){
		
		if(!isset($member['userid'])) return '';
		$cache = WPF()->cache->on('memory_cashe');
		
		$src = $member['avatar'];
		$userid = ( $member['userid'] ? $member['userid'] : $member['user_email'] );
		if($cache && isset(self::$cache['avatar'][$userid])){
			if( isset(self::$cache['avatar'][$userid]['attr']) && self::$cache['avatar'][$userid]['attr'] == $attr && isset(self::$cache['avatar'][$userid]['size']) && self::$cache['avatar'][$userid]['size'] == $size){
				if(isset(self::$cache['avatar'][$userid]['img'])){
					return self::$cache['avatar'][$userid]['img'];
				}
			}
		}
		if($src && wpforo_feature('custom-avatars')){
			$attr = ($attr ? $attr : 'height="96" width="96"');
			$img = '<img class="avatar" src="'.esc_url($src).'" '. $attr .' />';
		}else{
			$img = ($size) ? get_avatar($userid, $size) : get_avatar($userid);
			if($attr) $img = str_replace('<img', '<img ' . $attr, $img);
		}
		if($cache){
			self::$cache['avatar'][$userid]['attr'] = $attr;
			self::$cache['avatar'][$userid]['size'] = $size;
			return self::$cache['avatar'][$userid]['img'] = $img;
		}
		else{
			return $img;
		}
	}

    public function get_avatar($userid, $attr = '', $size = ''){
		
		$cache = WPF()->cache->on('memory_cashe');
		
		if($cache && isset(self::$cache['avatar'][$userid])){
			if(self::$cache['avatar'][$userid]['attr'] == $attr && self::$cache['avatar'][$userid]['size'] == $size){
				if(isset(self::$cache['avatar'][$userid]['img'])){
					return self::$cache['avatar'][$userid]['img'];
				}
			}
		}
		$src = $this->get_avatar_url($userid);
		if($src && wpforo_feature('custom-avatars')){
			$attr = ($attr ? $attr : 'height="96" width="96"');
			$img = '<img class="avatar" src="'.esc_url($src).'" '. $attr .' />';
		}else{
			$img = ($size) ? get_avatar($userid, $size) : get_avatar($userid);
			if($attr) $img = str_replace('<img', '<img ' . $attr, $img);
		}
		if($cache){
			self::$cache['avatar'][$userid]['attr'] = $attr;
			self::$cache['avatar'][$userid]['size'] = $size;
			return self::$cache['avatar'][$userid]['img'] = $img;
		}
		else{
			return $img;
		}
	}
	
	public function get_avatar_url($userid){
        $cache = WPF()->cache->on('memory_cashe');
        if( $cache && array_key_exists($userid, self::$cache['user']) && array_key_exists('avatar', self::$cache['user'][$userid]) ){
             return self::$cache['user'][$userid]['avatar'];
        }
        if( $cache && array_key_exists($userid, self::$cache['avatar']) && array_key_exists('avatar_url', self::$cache['avatar'][$userid]) ){
             return self::$cache['avatar'][$userid]['avatar_url'];
        }

		$avatar_url = WPF()->db->get_var( WPF()->db->prepare("SELECT `avatar` FROM `".WPF()->tables->profiles."` WHERE `userid` = %d", wpforo_bigintval($userid)) );

        if($cache) return self::$cache['avatar'][$userid]['avatar_url'] = $avatar_url;
        return $avatar_url;
	}

    public function get_topics_count( $userid ){
		$count = WPF()->db->get_var("SELECT count(topicid) FROM `".WPF()->tables->topics."` WHERE `userid` = ".intval($userid));
		return $count;
	}

    public function get_questions_count( $userid ){
		$count = WPF()->db->get_var("SELECT count(topicid) FROM `".WPF()->tables->topics."` WHERE `userid` = ".intval($userid));
		return $count;
	}

    public function get_answers_count( $userid ){
		$count = WPF()->db->get_var("SELECT count(postid) FROM `".WPF()->tables->posts."` WHERE `is_answer` = 1 AND `userid` = ".intval($userid));
		return $count;
	}

    public function get_question_comments_count( $userid ){
		$count = WPF()->db->get_var("SELECT count(postid) FROM `".WPF()->tables->posts."` WHERE `parentid` > 0 AND `userid` = ".intval($userid));
		return $count;
	}

    public function get_replies_count( $userid ){
		$count = WPF()->db->get_var("SELECT count(postid) FROM `".WPF()->tables->posts."` WHERE `userid` = ".intval($userid));
		return $count;
	}

    public function get_likes_count( $userid ){
		$count = WPF()->db->get_var("SELECT count(likeid) FROM `".WPF()->tables->likes."` WHERE `userid` = ".intval($userid));
		return $count;
	}

    public function get_votes_count( $userid ){
		$count = WPF()->db->get_var("SELECT count(voteid) FROM `".WPF()->tables->votes."` WHERE `userid` = ".intval($userid));
		return $count;
	}
	
	// how many times the user like or vote
    public function get_votes_and_likes_count( $userid ){
		return $this->get_votes_count( intval($userid) ) + $this->get_likes_count( intval($userid) );
	}
	
	//getting user's posts votes and likes count
    public function get_user_votes_and_likes_count( $userid ){
		$votes_count = WPF()->db->get_var("SELECT count(voteid) FROM `".WPF()->tables->votes."` WHERE `post_userid` = ".intval($userid));
		$likes_count = WPF()->db->get_var("SELECT count(likeid) FROM `".WPF()->tables->likes."` WHERE `post_userid` = ".intval($userid));
		return $votes_count + $likes_count;
	}

    public function get_profile_url( $arg, $template = 'profile' ){
		if(!$arg) return wpforo_home_url();
		if( wpfval( WPF()->tpl->slugs, $template ) && WPF()->tpl->slugs[$template] ) $template = WPF()->tpl->slugs[$template];
		$userid = intval( basename($arg) );
		$member_args = ( $userid ? $userid : array( 'user_nicename' => basename($arg) ) );
		$user = $this->get_member( $member_args );
		if(empty($user)) return wpforo_home_url();
		$user_slug = ( wpfo(WPF()->member->options['url_structure'], false) == 'id' ? $user['ID'] : $user['user_nicename'] );
        $profile_url = wpforo_home_url("$template/$user_slug");
        return apply_filters('wpforo_member_profile_url', $profile_url, $user, $template);
	}

    public function profile_url( $member = array(), $template = 'profile' ){
        if( wpfval( WPF()->tpl->slugs, $template ) && WPF()->tpl->slugs[$template] ) $template = WPF()->tpl->slugs[$template];
		if(isset($member['ID']) || isset($member['user_nicename'])){
			$user_slug = ( wpfo(WPF()->member->options['url_structure'], false) == 'id' ? $member['ID'] : $member['user_nicename'] );
			$profile_url = wpforo_home_url("$template/$user_slug");
			$profile_url = apply_filters( 'wpforo_profile_url', $profile_url, $member, $template );
		}
		else{
			$profile_url = wpforo_home_url();
			$profile_url = apply_filters( 'wpforo_no_profile_url', $profile_url, $template );
			
		}
		return apply_filters('wpforo_member_profile_url', $profile_url, $member, $template);
	}
	
	//$args = UserID or Member Object
	//$live_count = TRUE / FALSE
    public function get_stat( $args = array(), $live_count = false, $cache = false ){

		$cache = WPF()->cache->on('memory_cashe');

		$stat = array(	'points' => 0,
						'rating' => 0,
						'rating_procent' => 0,
						'color' => $this->rating(0, 'color'),
						'badge' => $this->rating(0, 'icon'),
						'posts' => 0,
						'topics' => 0,
						'questions' => 0,
						'answers' => 0,
						'question_comments' => 0,
						'likes' => 0,
						'liked' => 0,
						'title' => $this->rating(0, 'title'));

		$userid = ( isset($args['userid']) && $args['userid'] ) ? $args['userid'] : $args;

		if(  $cache && isset(self::$cache['stat'][$userid]) ){
			return self::$cache['stat'][$userid];
		}

		if( is_array($args) && isset($args['userid']) ){
			$userid = $args['userid'];
			$stat['topics'] = (int)$this->get_topics_count( $userid );
			if(isset($args['questions'])) $stat['questions'] = intval($args['questions']);
			if(isset($args['answers'])) $stat['answers'] = intval($args['answers']);
			if(isset($args['posts'])) $stat['posts'] = intval($args['posts']);
			if(isset($args['comments'])) $stat['question_comments'] = intval($args['comments']);
		}
		elseif($userid = wpforo_bigintval($args)){
			$stat['topics'] = (int)$this->get_topics_count( $userid );
			if($live_count){
				if($questions = $this->get_questions_count( $userid )) $stat['questions'] = $questions;
				if($answers = $this->get_answers_count( $userid )) $stat['answers'] = $answers;
				if($posts = $this->get_replies_count( $userid )) $stat['posts'] = $posts;
				if($question_comments = $this->get_question_comments_count( $userid )) $stat['question_comments'] = $question_comments;
			}
			else{
				$profile = WPF()->db->get_var("SELECT `posts`, `questions`, `answers`, `comments` FROM `".WPF()->tables->profiles."` WHERE `userid` = ".intval($userid));
				if(isset($profile['questions'])) $stat['questions'] = intval($profile['questions']);
				if(isset($profile['answers'])) $stat['answers'] = intval($profile['answers']);
				if(isset($profile['posts'])) $stat['posts'] = intval($profile['posts']);
				if(isset($profile['comments'])) $stat['question_comments'] = intval($profile['comments']);
			}
		}

		if( $userid ){
			if($likes = $this->get_votes_and_likes_count( $userid )) $stat['likes'] = $likes;
			if($liked = $this->get_user_votes_and_likes_count( $userid )) $stat['liked'] = $liked;
			if($stat['posts']) $stat['points'] = $stat['posts']; //TO-DO: Point counter function based on all stat values.
			if($stat['points']) $stat['rating'] = $this->rating_level($stat['points'], false);
			if($stat['rating']) {
				$stat['rating_procent'] = $stat['rating'] * 10;
				$stat['title'] = $this->rating(intval($stat['rating']), 'title');
				$stat['color'] = $this->rating(intval($stat['rating']), 'color');
				$stat['badge'] = $this->rating(intval($stat['rating']), 'icon');
			}
		}

		if($cache && isset($userid)){
			return self::$cache['stat'][$userid] = $stat;
		}
		else{
			return $stat;
		}
	}

    public function get_count(){
		return WPF()->db->get_var( "SELECT COUNT(p.`userid`) FROM `".WPF()->tables->profiles."` p 
			INNER JOIN `".WPF()->db->users."` u ON u.`ID` = p.`userid` WHERE p.`status` NOT LIKE 'trashed'" );
	}

    public function is_online( $userid, $duration = NULL ){
		
		$cache = WPF()->cache->on('memory_cashe');
		
		if( $cache && isset(self::$cache['online'][$userid]) ){
			if(self::$cache['online'][$userid]['durration'] == $duration ){
				if(isset(self::$cache['online'][$userid]['status'])){
					return self::$cache['online'][$userid]['status'];
				}
			}
		}
		if(!$duration) $duration = WPF()->member->options['online_status_timeout'];
		$sql = "SELECT `online_time` FROM `".WPF()->tables->profiles."` WHERE `userid` = %d";
		$sql = WPF()->db->prepare($sql, $userid);
		$online_time = intval( WPF()->db->get_var($sql) );
		$current_time =  current_time( 'timestamp', 1 );
		$online_duration = $current_time - $online_time;
		if( $online_duration < $duration ) {
			$status = true;
		} 
		else{
			$status = false;
		}
		if( $cache ){
			self::$cache['online'][$userid]['durration'] = $duration;
			return self::$cache['online'][$userid]['status'] = $status;
		}
		else{
			return $status;
		}
	}
	
	public function show_online_indicator($userid, $ico = TRUE){
		if( $this->is_online($userid)) : ?>
			
			<?php if($ico) : ?>
            	<i class="far fa-lightbulb wpfsx wpfcl-8" title="<?php wpforo_phrase('Online') ?>"></i>
            <?php else : wpforo_phrase('Online'); endif ?>
            
        <?php else : ?>
        	
        	<?php if($ico) : ?>
            	<i class="far fa-lightbulb wpfsx wpfcl-0" title="<?php wpforo_phrase('Offline') ?>"></i>
            <?php else : wpforo_phrase('Offline'); endif ?>
            
        <?php endif;  
	}

    public function online_members_count( $duration = NULL ){
		if(!$duration) $duration = WPF()->member->options['online_status_timeout'];
		$current_time =  current_time( 'timestamp', 1 );
		$online_timeframe = $current_time - $duration;
		$online = WPF()->db->get_var( "SELECT COUNT(DISTINCT `userid`, `ip`) AS total FROM `".WPF()->tables->visits."` WHERE `time` > " . intval($online_timeframe) );
        if( !$online ) $online = WPF()->db->get_var( "SELECT COUNT(*) FROM `".WPF()->tables->profiles."` WHERE `online_time` > " . intval($online_timeframe) );
	    return $online;
	}

    public function get_online_members( $count = 1, $groupids = array(), $duration = NULL ){
		if(!$duration) $duration = WPF()->member->options['online_status_timeout'];
		$current_time =  current_time( 'timestamp', 1 );
		$online_timeframe = $current_time - $duration;
        $groupids = array_filter( wpforo_parse_args($groupids) );
        $args = array(
            'groupids' => $groupids,
            'online_time' => $online_timeframe, // $current_time - $duration
            'orderby' => 'userid', // forumid, order, parentid
            'row_count'	=> $count,
            'order' => 'ASC', // ASC DESC
        );
        return $this->get_members($args);
	}

    public function levels(){
		$levels = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
		return $levels;
	}

    public function rating( $level = false, $var = false, $default = false ){
		$rating = array();
		$rating['color'] = array( 0 => '#d2d2d2', 1 => '#4dca5c', 2 => '#4dca5c', 3 => '#4dca5c', 4 => '#4dca5c', 5 => '#4dca5c', 6 => '#E5D600', 7 => '#E5D600', 8 => '#E5D600', 9 => '#FF812D', 10 => '#E04A47' );
		$rating['points'] = array( 0 => 0, 1 => 5, 2 => 20, 3 => 50, 4 => 100, 5 => 250, 6 => 500, 7 => 750, 8 => 1000, 9 => 2500, 10 => 5000 );
		$rating['title'] = array( 0 => 'New Member', 1 => 'Active Member', 2 => 'Eminent Member', 3 => 'Trusted Member', 4 => 'Estimable Member', 5 => 'Reputable  Member', 6 => 'Honorable Member', 7 => 'Prominent Member', 8 => 'Noble Member', 9 => 'Famed Member', 10 => 'Illustrious Member' );
		$rating['icon']  = array( 0 => 'far fa-star-half', 1 => 'fas fa-star', 2 => 'fas fa-star', 3 => 'fas fa-star', 4 => 'fas fa-star', 5 => 'fas fa-star', 6 => 'fas fa-certificate', 7 => 'fas fa-certificate', 8 => 'fas fa-certificate', 9 => 'fas fa-shield-alt', 10 => 'fas fa-trophy' );
		
		if(!empty(WPF()->member->options['rating'])){
			
			if($level === false) return WPF()->member->options['rating'];
			if(!empty(WPF()->member->options['rating'][$level])){
				
				if(!$var) return WPF()->member->options['rating'][$level];
				if(!empty(WPF()->member->options['rating'][$level][$var])){

                    if( $var == 'icon' && strpos( WPF()->member->options['rating'][$level][$var], ' ' ) === false ) return $rating[$var][$level];

                    return WPF()->member->options['rating'][$level][$var];
				}
			}
		}
		if( $level !== false && $var ) { return $rating[$var][$level]; }
		elseif( $level !== false && !$var ){ foreach( $rating as $variable => $values ){ $level_data[$variable] = $values[$level];} return $level_data; }
		elseif( $level === false && !$var ) return $rating;
		else return array();
	}

    public function rating_level($member_posts, $percent = TRUE){
		$bar = 0;
		if($member_posts < $this->rating(1, 'points')){$bar = 0;}
		elseif($member_posts < $this->rating(2, 'points')){$bar = 10;}
		elseif($member_posts < $this->rating(3, 'points')){$bar = 20;}
		elseif($member_posts < $this->rating(4, 'points')){$bar = 30;}
		elseif($member_posts < $this->rating(5, 'points')){$bar = 40;}
		elseif($member_posts < $this->rating(6, 'points')){$bar = 50;}
		elseif($member_posts < $this->rating(7, 'points')){$bar = 60;}
		elseif($member_posts < $this->rating(8, 'points')){$bar = 70;}
		elseif($member_posts < $this->rating(9, 'points')){$bar = 80;}
		elseif($member_posts < $this->rating(10, 'points')){$bar = 90;}
		else{$bar = 100;}
		if($percent){
			return $bar;
		}else{
			return floor($bar/10);
		}
	}

    public function rating_badge($level = 0, $view = 'short'){

		$level = ( $level > 10 ) ? floor($level/10) : $level;
		
		if($level == 0){
			return '<i class="'. $this->rating($level, 'icon') .'"></i>';
		}
		elseif($level > 0 && $level < 6){
			if( $view == 'full' ){
				return str_repeat(' <i class="'. $this->rating($level, 'icon') .'"></i> ', $level);
			}
			else{
				return '<span>' . esc_html($level) . '</span> <i class="'. $this->rating($level, 'icon') .'"></i>';
			}
		}
		elseif($level > 5 && $level < 9){
			if( $view == 'full' ){
				return str_repeat(' <i class="'. $this->rating($level, 'icon') .'"></i> ', ($level-5));
			}
			else{
				return '<span>' . esc_html($level-5) . '</span> <i class="'. $this->rating($level, 'icon') .'"></i>';
			}
		}
		elseif($level > 8){
			return '<i class="'. $this->rating($level, 'icon') .'"></i>';
		}
		else{
			return '';
		}
	}
	
	public function reset($userid){
		if( !$userid ) return;
		WPF()->db->query( "DELETE FROM `".WPF()->db->usermeta."` WHERE `meta_key` = '_wpf_member_obj' AND `user_id` = " . intval($userid) );
		wpforo_clean_cache( 'user', $userid );
	}
	
	public function clear_db_cache(){
		WPF()->db->query( "DELETE FROM `".WPF()->db->usermeta."` WHERE `meta_key` = '_wpf_member_obj'" );
	}

	private function update_online_time($userid = NULL){
	    if(!$userid) $userid = WPF()->current_userid;
	    if(!$userid) return false;
	    $current_timestamp = current_time( 'timestamp', 1 );
        $sql = "UPDATE `".WPF()->tables->profiles."` SET `online_time` = %d WHERE `userid` = %d";
        $sql = WPF()->db->prepare($sql, $current_timestamp, wpforo_bigintval($userid));
        if( false !== WPF()->db->query($sql) ) return $current_timestamp;
        return false;
    }
	
	public function init_current_user(){
        WPF()->wp_current_user = $current_user = wp_get_current_user();
		if( $current_user->exists() ){

			WPF()->current_userid  = $current_user->ID;
			WPF()->current_username  = $current_user->user_login;
			WPF()->current_user_email  = $current_user->user_email;
			WPF()->current_user_display_name  = $current_user->display_name;

			$user = $this->get_member( $current_user->ID );
            $user_meta = get_user_meta( $current_user->ID );
			$status = ( isset($user['status']) ? $user['status'] : '' );
			if( $status == 'active' ){
                $user['groupid'] = intval($user['groupid']);
				WPF()->current_user = $user;
                WPF()->current_usermeta = $user_meta;
				WPF()->current_user_groupid = WPF()->current_user['groupid'];
                WPF()->current_user_secondary_groupids = ( wpfkey(WPF()->current_user, 'secondary_groups') ) ? WPF()->current_user['secondary_groups'] : '';
			    $this->update_online_time();
			}
			WPF()->current_user_status  = $status;
		}elseif ( $guest = $this->get_guest_cookies() ){
            WPF()->current_user = $this->get_guest($guest);
            WPF()->current_user_email  = $guest['email'];
            WPF()->current_user_display_name  = $guest['name'];
        }
	}
	
	public function blog_posts( $userid ){
		if( isset($userid) && $userid ) return count_user_posts( $userid , 'post' );
		return 0;
	}
	
	public function blog_comments($userid, $user_email){
		global $wpdb;
		if( !$userid || !$user_email ) return 0;
		return (int) $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->comments. " WHERE `user_id` = " . intval($userid) . " OR `comment_author_email` = '" . esc_sql($user_email) . "'");
	}
	
	public function show_delete_form($current_user, $userids){
		if( empty($current_user) || empty($userids) ) return;
		
		$userids = array_diff( $userids, array( $current_user->ID ) );
		$users_have_content = false;
		if ( WPF()->db->get_var( "SELECT `postid` FROM `".WPF()->tables->posts."` WHERE `userid` IN( " . implode( ',', array_map('intval', $userids) ) . " ) LIMIT 1" ) ) {
			$users_have_content = true;
		}
		?>
		<hr /><strong>#wpForo</strong>
		<?php if ( ! $users_have_content ) : ?>
			<input type="hidden" name="wpforo_user_delete_option" value="delete" />
		<?php else: ?>
			<?php if ( 1 == count($userids) ) : ?>
				<fieldset><p><legend><?php _e( 'What should be done with wpForo content owned by this user?', 'wpforo' ); ?></legend></p>
			<?php else : ?>
				<fieldset><p><legend><?php _e( 'What should be done with wpForo content owned by these users?', 'wpforo' ); ?></legend></p>
			<?php endif; ?>
			<ul style="list-style:none;">
				<li><label><input type="radio" id="wpforo_delete_option0" name="wpforo_user_delete_option" value="delete" />
				<?php _e('Delete all wpForo content.', 'wpforo'); ?></label></li>
				<li><input type="radio" id="wpforo_delete_option1" name="wpforo_user_delete_option" value="reassign" />
				<?php echo '<label for="wpforo_delete_option1">' . __( 'Attribute all content to:' ) . '</label> ';
				wp_dropdown_users( array(
					'name' => 'wpforo_reassign_user',
					'exclude' => $userids,
					'show' => 'display_name_with_login',
				) ); ?></li>
			</ul></fieldset>
			<script type="text/javascript">
				jQuery(document).ready( function($) {
					$('#wpforo_reassign_user').focus( function() {
						$('#wpforo_delete_option1').prop('checked', true).trigger('change');
					});
				});
			</script>
		<?php endif;
	}
	
	public function autoban($userid){
		if( !WPF()->perm->usergroup_can( 'em' ) ){
			WPF()->db->update(
				WPF()->tables->profiles,
				array('status' => 'banned'),
				array('userid' => intval( $userid )),
				array('%s'),
				array('%d')
			);
		}
	}
	
	public function member_approved_posts( $member = array() ){
		if(is_numeric($member)){
			if( isset(WPF()->current_user['posts']) && WPF()->current_user['posts'] && $member == WPF()->current_userid ){
				return WPF()->current_user['posts'];
			}
			elseif($member){
				return WPF()->db->get_var( "SELECT COUNT(*) as posts FROM `".WPF()->tables->posts."` WHERE `status` = 0 AND `userid` = " . intval($member) );
			}
			else{
                return 0;
            }
		}
		elseif(is_array($member) && !empty($member)){
			return intval($member['posts']);
		}
		else{
			return 0;
		}
	}
	
	public function current_user_is_new(){
		if( WPF()->perm->usergroup_can( 'em' ) ){
			//This is an admin or moderator. The number of posts doesn't matter.
			return false;
		}
		else{
		    $new_user_max_posts = WPF()->tools_antispam['new_user_max_posts'];
			$posts = $this->member_approved_posts( WPF()->current_userid );
			if ( $new_user_max_posts && $posts <= $new_user_max_posts ) {
				return true;
			}
			else{
				return false;
			}
		}
	}
	
	public function banned_count(){
		$count = WPF()->db->get_var("SELECT count(*) FROM `".WPF()->tables->profiles."` WHERE `status` = 'banned' " );
		return $count;
	}
	
	public function get_guest( $args = array() ){
		
		$cache = WPF()->cache->on('memory_cashe');
		
		if( !isset($args['name']) || $args['name'] == '' ) $args['name'] = wpforo_phrase('Anonymous', false);
		if( !isset($args['email']) || $args['email'] == '' ) $args['email'] = 'anonymous@example.com';
		
		$args['name'] = strip_tags($args['name']);
		$args['email'] = strip_tags($args['email']);
		$args['posts'] = 0;
		$args['user_registered'] = 0;
		
		if(isset($args['email'])){
			if( $cache && isset(self::$cache['guest'][$args['email']]) ){
				return self::$cache['guest'][$args['email']];
			}
		}
		
		if( $args['email'] && $args['email'] != 'anonymous@example.com' ){
			$post_args = array( 'email' => $args['email'], 'orderby' => '`created` ASC, `postid` ASC' );
			$posts = WPF()->post->get_posts( $post_args );
			if( !empty($posts) ){
				$args['posts'] = count($posts);
				if( isset($posts[0]['created']) || $posts[0]['created'] ) $args['user_registered'] = $posts[0]['created'];
			}
		} else {
            $args['user_registered'] = current_time('mysql', 1);
        }
		
		$member = array(    'ID' => 0,
							'userid' => 0,
							'user_login' => $args['name'],
							'user_pass' => '',
							'user_nicename' => sanitize_text_field($args['name']),
							'user_email' => $args['email'],
							'user_url' => '',  
							'user_registered' => $args['user_registered'],
							'user_activation_key' => '',
							'user_status' => 0,
							'display_name' => $args['name'],
							'title' => '',
							'username' => $args['name'],
							'groupid' => 4, 
							'posts' => $args['posts'], 'questions' => 0, 'answers' => 0, 'comments' => 0, 'site' => '', 'icq' => '', 'aim' => '', 'yahoo' => '', 'msn' => '', 'facebook' => '', 'twitter' => '', 'gtalk' => '', 'skype' => '', 'avatar' => '', 'signature' => '', 'about' => '', 'occupation' => '', 'location' => '', 'last_login' => '', 'rank' => 0, 'like' => 0,
							'status' => 'active',
							'timezone' => '',
							'name' => $args['name'],
							'cans' => '',
							'description' => '',
							'groupname' => wpforo_phrase('Guest', false),
							'profile_url' => '',
							'stat' => array( 'points' => 0, 'rating' => 0, 'rating_procent' => 0, 'color' => '', 'badge' => '', 'posts' => $args['posts'], 'topics' => 0, 'questions' => 0, 'answers' => 0, 'question_comments' => 0, 'likes' => 0, 'liked' => 0, 'title' => '' ),
                            'is_email_confirmed' => 0,
                            '$secondary_groups' => ''
						);
		
		if( $cache && $args['email'] ){
			return self::$cache['guest'][$args['email']] = $member;
		}else{
			return $member;
		}
		
	}

	public function init_fields(){
	    if( !empty($this->fields) ) return;

        $this->init_countries();
        $this->init_timezones();

		$this->fields = apply_filters('wpforo_member_before_init_fields', $this->fields);

        $usergroupids = array();
        $secondary_groups = '';
        $usergroups = WPF()->usergroup->get_usergroups();
        foreach( $usergroups as $usergroup ){
            $usergroupids[] = $usergroup['groupid'];
            if( $usergroup['groupid'] != 1 && $usergroup['secondary'] ){
                $secondary_groups  .= $usergroup['groupid'] . '=>' . $usergroup['name'] . "\r\n";
            }
        }
		$usergroupids_can_edit_fields = WPF()->perm->usergroups_can('em');
		$usergroupids_can_view_social_net = WPF()->perm->usergroups_can('vmsn');

	    $this->fields['user_login'] = array(
            'fieldKey' => 'user_login',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 1,
            'isEditable' => 0,
            'label' => wpforo_phrase('Username', false),
            'title' => wpforo_phrase('Username', false),
            'placeholder' => wpforo_phrase('Username', false),
            'description' => wpforo_phrase('Length must be between 3 characters and 15 characters.', false),
            'minLength' => $this->default->login_min_length,
            'maxLength' => $this->default->login_max_length,
            'faIcon' => 'fas fa-user',
            'name' => 'user_login',
			'canBeInactive' => array(
                    'account',
                    'profile',
                    'search'
            ),
			'canEdit' => array(),
			'canView' => WPF()->perm->usergroups_can('vmu'),
			'can' => 'vmu',
            'isSearchable' => 0
        );

        $this->fields['user_email'] = array(
            'fieldKey' => 'user_email',
            'type' => 'email',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 1,
            'isEditable' => 1,
            'label' => wpforo_phrase('Email', false),
            'title' => wpforo_phrase('Email', false),
            'placeholder' => wpforo_phrase('Email', false),
            'minLength' => 0,
            'maxLength' => 0,
            'faIcon' => 'fas fa-envelope',
            'name' => 'user_email',
            'canBeInactive' => array(
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('vmm'),
			'can' => 'vmm',
            'isSearchable' => 1
        );

        $this->fields['user_pass'] = array(
            'fieldKey' => 'user_pass',
            'type' => 'password',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 1,
            'isEditable' => 1,
            'isConfirmPassword' => 1,
            'label' => wpforo_phrase('Password', false),
            'title' => wpforo_phrase('Password', false),
            'placeholder' => wpforo_phrase('Password', false),
            'description' => wpforo_phrase('Must be minimum 6 characters.', false),
            'minLength' => $this->default->pass_min_length,
            'maxLength' => $this->default->pass_max_length,
            'faIcon' => 'fas fa-key',
            'name' => 'user_pass',
            'canBeInactive' => array(
                'profile',
                'search'
            ),
            'canEdit' => $usergroupids_can_edit_fields,
            'canView' => array(),
            'can' => '',
            'isSearchable' => 0
        );

        $this->fields['display_name'] = array(
            'fieldKey' => 'display_name',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 1,
            'isEditable' => 1,
            'label' => wpforo_phrase('Display Name', false),
            'title' => wpforo_phrase('Display Name', false),
            'placeholder' => wpforo_phrase('Display Name', false),
			'faIcon' => 'fas fa-user',
            'name' => 'display_name',
            'canBeInactive' => array(
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids,
			'can' => '',
            'isSearchable' => 1
        );

        $this->fields['user_nicename'] = array(
            'fieldKey' => 'user_nicename',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 1,
            'isEditable' => 1,
            'label' => wpforo_phrase('Nickname', false),
            'title' => wpforo_phrase('Nickname', false),
            'placeholder' => wpforo_phrase('Nickname', false),
            'description' => wpforo_phrase('URL Address Identifier', false),
            'minLength' => 0,
            'maxLength' => 0,
            'faIcon' => 'fas fa-link',
            'name' => 'user_nicename',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids,
			'can' => '',
            'isSearchable' => 1
        );

        $this->fields['title'] = array(
            'fieldKey' => 'title',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 1,
            'isEditable' => 1,
            'label' => wpforo_phrase('Title', false),
            'title' => wpforo_phrase('Title', false),
            'placeholder' => wpforo_phrase('Title', false),
            'minLength' => 0,
            'maxLength' => 0,
			'faIcon' => 'fas fa-user',
            'name' => 'title',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('vmt'),
			'can' => 'vmt',
            'isSearchable' => 1
        );

        $this->fields['groupid'] = array(
            'fieldKey' => 'groupid',
            'type' => 'usergroup',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 1,
            'isEditable' => 1,
            'label' => wpforo_phrase('User Group', false),
            'title' => wpforo_phrase('User Group', false),
            'placeholder' => wpforo_phrase('User Group', false),
            'faIcon' => 'fas fa-users',
            'name' => 'groupid',
			'allowedGroupIds' => array(3, 5),
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids,
			'can' => '',
            'isSearchable' => 1
        );

        $this->fields['secondary_groups'] = array(
            'fieldKey' => 'secondary_groups',
            'type' => 'checkbox',
            'isWrapItem' => 1,
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 0,
            'label' => wpforo_phrase('User Groups Secondary', false),
            'title' => wpforo_phrase('User Groups Secondary', false),
            'placeholder' => '',
            'faIcon' => '',
            'values' => $secondary_groups,
            'name' => 'secondary_groups',
            'allowedGroupIds' => array(3, 5),
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
            'canEdit' => $usergroupids_can_edit_fields,
            'canView' => $usergroupids,
            'can' => '',
            'isSearchable' => 1
        );

        $this->fields['avatar'] = array(
            'fieldKey' => 'avatar',
            'type' => 'avatar',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Avatar', false),
            'title' => wpforo_phrase('Avatar', false),
            'placeholder' => wpforo_phrase('Avatar', false),
            'name' => 'avatar',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('va'),
			'can' => 'va',
            'isSearchable' => 0
        );

        $this->fields['site'] = array(
            'fieldKey' => 'site',
            'type' => 'url',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Website', false),
            'title' => wpforo_phrase('Website', false),
            'placeholder' => wpforo_phrase('Website', false),
            'faIcon' => 'fas fa-sitemap',
            'name' => 'site',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('vmw'),
			'can' => 'vmw',
            'isSearchable' => 1
        );

        $this->fields['facebook'] = array(
            'fieldKey' => 'facebook',
            'type' => 'url',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Facebook', false),
            'title' => wpforo_phrase('Facebook', false),
            'placeholder' => wpforo_phrase('Facebook', false),
            'faIcon' => 'fab fa-facebook',
            'name' => 'facebook',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['twitter'] = array(
            'fieldKey' => 'twitter',
            'type' => 'url',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Twitter', false),
            'title' => wpforo_phrase('Twitter', false),
            'placeholder' => wpforo_phrase('Twitter', false),
            'faIcon' => 'fab fa-twitter-square',
            'name' => 'twitter',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['gtalk'] = array(
            'fieldKey' => 'gtalk',
            'type' => 'url',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Google+', false),
            'title' => wpforo_phrase('Google+', false),
            'placeholder' => wpforo_phrase('Google+', false),
            'faIcon' => 'fab fa-google-plus-square',
            'name' => 'gtalk',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['yahoo'] = array(
            'fieldKey' => 'yahoo',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Yahoo', false),
            'title' => wpforo_phrase('Yahoo', false),
            'placeholder' => wpforo_phrase('Yahoo', false),
            'faIcon' => 'fab fa-yahoo',
            'name' => 'yahoo',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['aim']= array(
            'fieldKey' => 'aim',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('AOL IM', false),
            'title' => wpforo_phrase('AOL IM', false),
            'placeholder' => wpforo_phrase('AOL IM', false),
			'faIcon' => 'fas fa-share-alt',
            'name' => 'aim',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['icq'] = array(
            'fieldKey' => 'icq',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('ICQ', false),
            'title' => wpforo_phrase('ICQ', false),
            'placeholder' => wpforo_phrase('ICQ', false),
			'faIcon' => 'fas fa-share-alt',
            'name' => 'icq',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['msn'] = array(
            'fieldKey' => 'msn',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('MSN', false),
            'title' => wpforo_phrase('MSN', false),
            'placeholder' => wpforo_phrase('MSN', false),
			'faIcon' => 'fas fa-share-alt',
            'name' => 'msn',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['skype'] = array(
            'fieldKey' => 'skype',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Skype', false),
            'title' => wpforo_phrase('Skype', false),
            'placeholder' => wpforo_phrase('Skype', false),
            'faIcon' => 'fab fa-skype',
            'name' => 'skype',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 1
        );

        $this->fields['location'] = array(
            'fieldKey' => 'location',
            'type' => 'select',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Location', false),
            'title' => wpforo_phrase('Location', false),
            'placeholder' => wpforo_phrase('Location', false),
            'faIcon' => 'fas fa-globe',
            'values' => $this->countries,
            'name' => 'location',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('vml'),
			'can' => 'vml',
            'isSearchable' => 1
        );

        $this->fields['timezone'] = array(
            'fieldKey' => 'timezone',
            'type' => 'select',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Timezone', false),
            'title' => wpforo_phrase('Timezone', false),
            'placeholder' => wpforo_phrase('Timezone', false),
            'faIcon' => 'fas fa-globe',
            'values' => $this->timezones,
            'name' => 'timezone',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids,
			'can' => '',
            'isSearchable' => 1
        );

        $this->fields['occupation'] = array(
            'fieldKey' => 'occupation',
            'type' => 'text',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Occupation', false),
            'title' => wpforo_phrase('Occupation', false),
            'placeholder' => wpforo_phrase('Occupation', false),
            'faIcon' => 'fas fa-address-card',
            'name' => 'occupation',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('vmo'),
			'can' => 'vmo',
            'isSearchable' => 1
        );

        $this->fields['signature'] = array(
            'fieldKey' => 'signature',
            'type' => 'textarea',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Signature', false),
            'title' => wpforo_phrase('Signature', false),
            'placeholder' => wpforo_phrase('Signature', false),
            'faIcon' => 'fas fa-address-card',
            'name' => 'signature',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('vms'),
			'can' => 'vms',
            'isSearchable' => 1
        );

        $this->fields['about'] = array(
            'fieldKey' => 'about',
            'type' => 'textarea',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('About Me', false),
            'title' => wpforo_phrase('About Me', false),
            'placeholder' => wpforo_phrase('About Me', false),
            'faIcon' => 'fas fa-address-card',
            'name' => 'about',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => WPF()->perm->usergroups_can('vmam'),
			'can' => 'vmam',
            'isSearchable' => 1
        );

        $this->fields['html_soc_net'] = array(
            'fieldKey' => 'html_soc_net',
            'type' => 'html',
            'isDefault' => 1,
            'isRemovable' => 0,
            'isRequired' => 0,
            'isEditable' => 1,
            'label' => wpforo_phrase('Social Networks', false),
            'title' => wpforo_phrase('Social Networks', false),
            'placeholder' => wpforo_phrase('Social Networks', false),
            'description' => wpforo_phrase('Social Networks', false),
            'html' => '<div class="wpf-label">' . wpforo_phrase('Social Networks', false) . '</div>',
            'name' => 'html_soc_net',
            'canBeInactive' => array(
                'register',
                'account',
                'profile',
                'search'
            ),
			'canEdit' => $usergroupids_can_edit_fields,
			'canView' => $usergroupids_can_view_social_net,
			'can' => 'vmsn',
            'isSearchable' => 0
        );

        $this->fields = apply_filters('wpforo_member_after_init_fields', $this->fields);
    }

    private function init_countries(){
	    $this->countries = array( "Afghanistan","Åland Islands","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica",
            "Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain",
            "Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina",
            "Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso",
            "Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile",
            "China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Congo, The Democratic Republic of The",
            "Cook Islands","Costa Rica","Cote D'ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica",
            "Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)",
            "Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon",
            "Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala",
            "Guernsey","Guinea","Guinea-bissau","Guyana","Haiti","Heard Island and Mcdonald Islands","Holy See (Vatican City State)",
            "Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran, Islamic Republic of","Iraq","Ireland",
            "Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Korea, Democratic People's Republic of",
            "Korea, Republic of","Kuwait","Kyrgyzstan","Lao People's Democratic Republic","Latvia","Lebanon","Lesotho","Liberia",
            "Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macao","Macedonia, The Former Yugoslav Republic of",
            "Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius",
            "Mayotte","Mexico","Micronesia, Federated States of","Moldova, Republic of","Monaco","Mongolia","Montenegro","Montserrat",
            "Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia",
            "New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman",
            "Pakistan","Palau","Palestinian Territory, Occupied","Panama","Papua New Guinea","Paraguay","Peru","Philippines",
            "Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russian Federation","Rwanda","Saint Helena",
            "Saint Kitts and Nevis","Saint Lucia","Saint Pierre and Miquelon","Saint Vincent and The Grenadines","Samoa",
            "San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore",
            "Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and The South Sandwich Islands",
            "Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Swaziland","Sweden","Switzerland","Syrian Arab Republic",
            "Taiwan, Province of China","Tajikistan","Tanzania, United Republic of","Thailand","Timor-leste","Togo","Tokelau",
            "Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine",
            "United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan",
            "Vanuatu","Venezuela","Viet Nam","Virgin Islands, British","Virgin Islands, U.S.","Wallis and Futuna","Western Sahara",
            "Yemen","Zambia","Zimbabwe" );
    }

    private function init_timezones(){
	    $this->timezones = timezone_identifiers_list();
        $offset_range = array (-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5,
            0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14);
        foreach ( $offset_range as $offset ) {
            if ( 0 <= $offset )
                $offset_name = '+' . $offset;
            else
                $offset_name = (string) $offset;

            $offset_value = $offset_name;
            $offset_value = 'UTC' . $offset_value;
            $this->timezones[] = 'UTC/' . $offset_value;
        }

        $zones = $this->timezones;
        $timezones = array();
        foreach($zones as $zone){
            if(strpos($zone, '/') === false) continue;

            $zone = str_replace('_', ' ', $zone);

            $group = function_exists('mb_substr') ? mb_substr($zone, 0, strpos($zone, '/')) : substr($zone, 0, strpos($zone, '/'));
            $index = function_exists('mb_strlen') ? mb_strlen($group) + 1 : strlen($group) + 1;
            $optionValue = substr($zone, $index);

            if(strpos($optionValue, 'UTC') !== false){
                $optionTitle = str_replace(array('.25', '.5', '.75',), array(':15', ':30', ':45',), $optionValue);
                $optionValue = "$optionValue=>$optionTitle";
            }else{
                $optionValue = "$zone=>$optionValue";
            }

            $timezones[$group][] = $optionValue;
        }

        $this->timezones = $timezones;
    }

    public function get_fields($only_defaults = false){
        $this->init_fields();
        $fields = $this->fields;

        if(!$only_defaults) $fields = apply_filters('wpforo_get_fields', $fields);
        return $fields;
    }
	
	public function get_register_fields($only_defaults = false){
        $this->init_fields();
        $password = false;
        $regform = array(
            $this->fields['user_login'],
            $this->fields['user_email']
        );
        if( !wpforo_feature('user-register-email-confirm') ) $regform[] = $this->fields['user_pass'];
        $fields = array(
            array(
                $regform
            )
        );
        if(!$only_defaults) {
            $fields = apply_filters('wpforo_get_register_fields', $fields);
            foreach( $fields as $kr => $row ){
                foreach( $row as $kc => $cols ){
                    foreach( $cols as $kf => $field ){
                        if( 'user_pass' == $field['name'] ){
                            if( wpforo_feature('user-register-email-confirm') ){
                                unset( $fields[$kr][$kc][$kf] );
                                $password = false;
                            } else {
                                $password = true;
                            }
                        }
                    }
                }
            }
            if( !wpforo_feature('user-register-email-confirm') && !$password ){
                $fields[$kr][$kc][] = $this->fields['user_pass'];
            }
        }
        return $fields;
	}

	public function get_account_fields($only_defaults = false){
        $this->init_fields();

        $fields = array(
            array(
                array(
                   $this->fields['user_login'],
                   $this->fields['display_name'],
                   $this->fields['user_nicename'],
                   $this->fields['user_email'],
                   $this->fields['title'],
                   $this->fields['groupid'],
                   $this->fields['secondary_groups'],
                   $this->fields['avatar'],
				   $this->fields['about'],
                   $this->fields['site'],
				   $this->fields['occupation'],
                   $this->fields['signature']
                )
            ),
            array(
                array(
                    $this->fields['html_soc_net']
                ),
                array(
                    $this->fields['facebook'],
                    $this->fields['gtalk'],
                    $this->fields['aim'],
                    $this->fields['msn']
                ),
                array(
                    $this->fields['twitter'],
                    $this->fields['yahoo'],
                    $this->fields['icq'],
                    $this->fields['skype']
                )
            ),
            array(
                array(
                    $this->fields['location'],
                    $this->fields['timezone'],
                    $this->fields['user_pass']
                )
            )
        );

        if(!$only_defaults) $fields = apply_filters('wpforo_get_account_fields', $fields);
        return $fields;
	}

    public function get_profile_fields($only_defaults = false){
        $this->init_fields();

        $fields = array(
            array(
                array(
                    $this->fields['about'],
                    $this->fields['site'],
                )
            ),
            array(
                array(
                    $this->fields['location'],
                    $this->fields['timezone'],
                    $this->fields['occupation'],
                    $this->fields['signature']
                )
            ),
			array(
                array(
                    $this->fields['html_soc_net']
                ),
            ),
			array(
                array(
                    $this->fields['facebook'],
                    $this->fields['gtalk'],
                    $this->fields['aim'],
                    $this->fields['msn'],
                ),
				array(
                    $this->fields['twitter'],
                    $this->fields['yahoo'],
                    $this->fields['icq'],
                    $this->fields['skype']
                )
            ),
        );

        if(!$only_defaults) $fields = apply_filters('wpforo_get_profile_fields', $fields);
        return $fields;
    }

    public function get_search_fields($only_defaults = false){
        $this->init_fields();

        $fields = array(
            array(
                array(
					$this->fields['display_name'],
                    $this->fields['user_nicename'],
                )
            )
        );

        if(!$only_defaults) $fields = apply_filters('wpforo_get_search_fields', $fields);

        foreach ($fields as $row_key => $row){
            foreach ($row as $col_key => $col){
                foreach ($col as $field_key => $field){
                    $fields[$row_key][$col_key][$field_key]['isRequired'] = 0;
                    $fields[$row_key][$col_key][$field_key]['class'] = 'wpf-member-search-field';
                    if( $field['type'] == 'text' || $field['type'] == 'textarea' || $field['type'] == 'email' || $field['type'] == 'url' )
                        $fields[$row_key][$col_key][$field_key]['type'] = 'search';
                }
            }
        }

        return $fields;
    }

    public function get_search_fields_names($only_defaults = false){
        $names = array();
        $fields = $this->get_search_fields($only_defaults);

        foreach ($fields as $row_key => $row){
            foreach ($row as $col_key => $col){
                foreach ($col as $field_key => $field){
                    if( !$field['isDefault'] ) continue;
                    $names[] = $field['name'];
                }
            }
        }

        return $names;
    }
	
	public function set_guest_cookies( $args ){
	    if ( !WPF()->tools_legal['cookies'] ) return false;
		if ( isset($args['name']) && isset($args['email']) ) {
			$comment_cookie_lifetime = apply_filters( 'comment_cookie_lifetime', 30000000 );
			$secure = ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) );
            setcookie( 'comment_author_' . COOKIEHASH, $args['name'], time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN, $secure );
            setcookie( 'comment_author_email_' . COOKIEHASH, $args['email'], time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN, $secure );

            WPF()->current_user_display_name  = $args['name'];
            WPF()->current_user_email  = $args['email'];
        }
	}
	
	public function get_guest_cookies(){
        if( !WPF()->tools_legal['cookies'] ) return false;
		$guest = array();
		if( !WPF()->current_userid && WPF()->current_user_email ){
            $guest['name'] = WPF()->current_user_display_name;
            $guest['email'] = WPF()->current_user_email;
        }else{
            $guest_cookies = wp_get_current_commenter();
            $guest['name'] = ( isset($guest_cookies['comment_author']) ) ? $guest_cookies['comment_author'] : '';
            $guest['email'] = ( isset($guest_cookies['comment_author_email']) ) ? $guest_cookies['comment_author_email'] : '';
        }
		return $guest;
	}

	public function edit_is_email_confirmed($userid, $status){
        if( false !== WPF()->db->update(
            WPF()->tables->profiles,
                array( 'is_email_confirmed' => intval($status) ),
                array( 'userid' => wpforo_bigintval($userid) ),
                array( '%d' ),
                array( '%d' )
            )
        ){
            WPF()->notice->add('Email has been confirmed', 'success');
            return true;
        }
        WPF()->notice->add('Email confirm error', 'error');
        return false;
    }

    public function get_is_email_confirmed($userid){
	    $sql = "SELECT `is_email_confirmed` FROM `".WPF()->tables->profiles."` WHERE `userid` = %d";
	    return (bool) WPF()->db->get_var( WPF()->db->prepare($sql, $userid) );
    }

    public function get_usergroup($userid){
        $sql = "SELECT `groupid` FROM `" . WPF()->tables->profiles . "` WHERE `userid` = %d";
        return WPF()->db->get_var( WPF()->db->prepare($sql, $userid) );
    }

    public function set_usergroup($userid, $groupid){
        if($userid && $groupid){
            WPF()->db->query( "UPDATE `" . WPF()->tables->profiles . "` SET `groupid` = " . intval($groupid) . " WHERE `userid` = " . wpforo_bigintval($userid) );
            $this->reset($userid);
        }
    }

    public function set_usergroups_secondary($userid, $group_ids = array()){
	    //TODO: set_usergroups_secondary() method condition
    }

}