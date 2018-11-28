<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
 
class wpForoUsergroup{
    public $default;
    public $default_groupid;
    public $cans;

	static $cache = array( 'usergroup' => array(), 'user' => array(), 'user_second' => array() );
	
	function __construct(){
        $this->init_defaults();
        $this->init_options();
	}

    private function init_defaults(){
        $this->default = new stdClass;

        $this->default->default_groupid = 3;

	    $this->default->cans = array(
		    'mf'  => __( 'Dashboard - Manage Forums', 'wpforo' ),
		    'ms'  => __( 'Dashboard - Manage Settings', 'wpforo' ),
		    'mt'  => __( 'Dashboard - Manage Tools', 'wpforo' ),
		    'vm'  => __( 'Dashboard - Manage Members', 'wpforo' ),
		    'aum' => __( 'Dashboard - Moderate Topics & Posts', 'wpforo' ),
		    'vmg' => __( 'Dashboard - Manage Usergroups', 'wpforo' ),
		    'mp'  => __( 'Dashboard - Manage Phrases', 'wpforo' ),
		    'mth' => __( 'Dashboard - Manage Themes', 'wpforo' ),

		    'em' => __( 'Dashboard - Can edit member', 'wpforo' ),
		    'bm' => __( 'Dashboard - Can ban member', 'wpforo' ),
		    'dm' => __( 'Dashboard - Can delete member', 'wpforo' ),

		    'aup'       => __( 'Front - Can pass moderation', 'wpforo' ),
		    'view_stat' => __( 'Front - Can view statistic', 'wpforo' ),
		    'vmem'      => __( 'Front - Can view members', 'wpforo' ),
		    'vprf'      => __( 'Front - Can view profiles', 'wpforo' ),
		    'vpra'      => __( 'Front - Can view member activity', 'wpforo' ),
		    'vprs'      => __( 'Front - Can view member subscriptions', 'wpforo' ),

		    'upa' => __( 'Front - Can upload avatar', 'wpforo' ),
		    'ups' => __( 'Front - Can have signaturee', 'wpforo' ),
		    'va'  => __( 'Front - Can view avatars', 'wpforo' ),

		    'vmu'  => __( 'Front - Can view member username', 'wpforo' ),
		    'vmm'  => __( 'Front - Can view member email', 'wpforo' ),
		    'vmt'  => __( 'Front - Can view member title', 'wpforo' ),
		    'vmct' => __( 'Front - Can view member custom title', 'wpforo' ),
		    'vmr'  => __( 'Front - Can view member reputation', 'wpforo' ),
		    'vmw'  => __( 'Front - Can view member website', 'wpforo' ),
		    'vmsn' => __( 'Front - Can view member social networks', 'wpforo' ),
		    'vmrd' => __( 'Front - Can view member reg. date', 'wpforo' ),
		    'vml'  => __( 'Front - Can view member location', 'wpforo' ),
		    'vmo'  => __( 'Front - Can view member occupation', 'wpforo' ),
		    'vms'  => __( 'Front - Can view member signature', 'wpforo' ),
		    'vmam' => __( 'Front - Can view member about me', 'wpforo' ),
		    'vwpm' => __( 'Front - Can write PM', 'wpforo' )
	    );
    }

    private function init_options(){
        $this->default_groupid = get_wpf_option('wpforo_default_groupid', $this->default->default_groupid);
        $this->cans = apply_filters('wpforo_usergroup_cans', $this->default->cans);
    }
	
	function usergroup_list_data(){
		$ugdata = array();
		$ugroups = WPF()->db->get_results('SELECT * FROM '.WPF()->tables->usergroups.' ORDER BY `name` ', ARRAY_A);
		foreach($ugroups as $ugroup){
			$user_count = WPF()->db->get_var("SELECT COUNT(*) FROM ".WPF()->tables->profiles." WHERE `groupid` = " . intval($ugroup['groupid']) . " OR FIND_IN_SET(" . intval($ugroup['groupid']) . ", `secondary_groups`)");
			$ugdata[$ugroup['groupid']]['groupid'] = $ugroup['groupid'];
			$ugdata[$ugroup['groupid']]['name'] = wpforo_phrase($ugroup['name'], FALSE);
            $ugdata[$ugroup['groupid']]['role'] = $ugroup['role'];
			$ugdata[$ugroup['groupid']]['count'] = intval($user_count);
			$ugdata[$ugroup['groupid']]['access'] = $ugroup['access'];
			$ugdata[$ugroup['groupid']]['color'] = $ugroup['color'];
            $ugdata[$ugroup['groupid']]['secondary'] = $ugroup['secondary'];
		}
		return $ugdata;
	}
	
	function add($title, $cans = array(), $description = '', $role = 'subscriber', $access = 'standard', $color = '', $visible = 1, $secondary = 0 ){
		$i = 2;
		$real_title = $title;
		while( WPF()->db->get_var(
						WPF()->db->prepare(
								"SELECT `groupid` FROM `".WPF()->tables->usergroups."` 
									WHERE `name` = '%s'", sanitize_text_field($title) )))
		{
			$title = $title . '-' . $i;
			$i++;
		}

		$cans = wpforo_parse_args( $cans, array_map('wpforo_return_zero', $this->cans) );

		if(	WPF()->db->insert(
			WPF()->tables->usergroups,
				array( 
					'name'		=> sanitize_text_field($title), 
					'cans' 	    => serialize( $cans ), 
					'description' => $description,
					'utitle' => sanitize_text_field($real_title), 
					'role' => $role,
					'access' => $access,
					'color' => $color,
					'visible' => $visible,
                    'secondary' => $secondary
				),
				array( 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
                    '%d'
				)
			)
		){
			$ugid = WPF()->db->insert_id;
			$forums = WPF()->forum->get_forums();
			if(!empty($forums) && $ugid){
				foreach($forums as $forum){
					if(isset($forum['permissions'])){
						$permissions = unserialize($forum['permissions']);
						if(!empty($permissions)){
							$permissions[$ugid] = $access;
							$permissions = serialize($permissions);
							WPF()->db->update( WPF()->tables->forums, array('permissions' => $permissions), array('forumid' => $forum['forumid']), array('%s'), array('%d') );
						}
					}
				}
			}
			WPF()->notice->add('User group successfully added', 'success');
			return WPF()->db->insert_id;
		}
		
		WPF()->notice->add('User group add error', 'error');
		return FALSE;
	}
	
	function edit( $groupid, $title, $cans, $description = '', $role = NULL, $access = NULL, $color = '', $visible = 1, $secondary = 0 ){
		
		//if( $groupid == 1 ) return false;
		if( !current_user_can('administrator') ){
			WPF()->notice->add('Permission denied', 'error');
			return FALSE;	
		}
		
		$cans = wpforo_parse_args( $cans, array_map('wpforo_return_zero', $this->cans) );
		$usergroup = $this->get_usergroup( $groupid );
		$role = is_null($role) ? $usergroup['role'] : $role;
		$access = is_null($access) ? $usergroup['access'] : $access;
		
		if( FALSE !== WPF()->db->update(
				WPF()->tables->usergroups,
				array( 
					'name' => sanitize_text_field($title), 
					'cans' => serialize( $cans ), 
					'description' => $description,
					'utitle' => $usergroup['utitle'],
					'role' => $role,
					'access' => $access,
					'color' => $color,
					'visible' => $visible,
                    'secondary' => $secondary
				),
				array( 'groupid' => intval($groupid) ),
				array( 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
                    '%d'
				),
				array( '%d' ))
		){
			WPF()->notice->add('User group successfully edited', 'success');
			return $groupid;
		}
		
		WPF()->notice->add('User group edit error', 'error');
		return FALSE;
	}
	
	function delete(){
		
		if( !current_user_can('administrator') ){
			WPF()->notice->add('Permission denied', 'error');
			return FALSE;	
		}
		
		if( isset($_GET['action']) && $_GET['action'] == 'del' && isset($_GET['gid']) && $_GET['gid'] != 1 && $_GET['gid'] != 4 ){
			$status = FALSE;
			extract($_POST['usergroup'], EXTR_OVERWRITE);
			$mergeid = intval($mergeid);
			$insert_gid = $_GET['gid'];
			#################################################### USERS
			if(isset($mergeid)){
				$status = WPF()->db->query("UPDATE `".WPF()->tables->profiles."` SET `groupid` = " . intval($mergeid) . " WHERE `groupid` = " . intval($insert_gid) );
				$notice = wpforo_phrase('Usergroup has been successfully deleted. All users of this usergroup have been moved to the usergroup you\'ve chosen', false);
			}else{
				$status = WPF()->db->query("UPDATE `".WPF()->tables->profiles."` SET `status` = 'trashed' WHERE `groupid` = " . intval($insert_gid) );
				$notice = wpforo_phrase('Usergroup has been successfully deleted.');
			}
			#################################################### END USERS
			if( $status !== FALSE ){
				if( WPF()->db->query("DELETE FROM `".WPF()->tables->usergroups."` WHERE `groupid` = " . intval($insert_gid) ) ){
					WPF()->notice->add($notice, 'success');
					return TRUE;
				}
			}
		}
		WPF()->notice->add('Can\'t delete this Usergroup', 'error');
		return FALSE;
	}
	
	function get_usergroup( $groupid = 4 ){
		// Guest UsergroupID = 4
		$cache = WPF()->cache->on('memory_cashe');
		if( $cache && isset(self::$cache['usergroup'][$groupid]) ){
			return self::$cache['usergroup'][$groupid];
		}
		$usergroup = WPF()->db->get_row("SELECT * FROM `".WPF()->tables->usergroups."` WHERE `groupid` = ".intval($groupid), ARRAY_A);
		if($cache && isset($groupid)){
			self::$cache['usergroup'][$groupid] = $usergroup;
		}
		return $usergroup;
	}
	
	function get_usergroups( $field = 'full' ){
        $cache = WPF()->cache->on('memory_cashe');
        if( $cache && isset(self::$cache['usergroups'][$field])  ) return self::$cache['usergroups'][$field];

		if( $field == 'full' ){
            $results = WPF()->db->get_results("SELECT * FROM `".WPF()->tables->usergroups."`", ARRAY_A);
		}else{
            $results = WPF()->db->get_col("SELECT `$field` FROM `".WPF()->tables->usergroups."`");
		}

        if( $cache ) self::$cache['usergroups'][$field] = $results;
        return $results;
	}
	
	function get_groupid_by_userid( $userid ){
		$cache = WPF()->cache->on('memory_cashe');
		if( $cache && isset(self::$cache['user'][$userid]) ){
			return self::$cache['user'][$userid];
		}
		$groupid = WPF()->db->get_var("SELECT `groupid` FROM `".WPF()->tables->profiles."` WHERE `userid` = " . intval($userid));
		if($cache && isset($groupid)){
			self::$cache['user'][$userid] = $groupid;
		}
		return $groupid;
	}

    function get_second_groupid_by_userid( $userid ){
        $cache = WPF()->cache->on('memory_cashe');
        if( $cache && isset(self::$cache['user_second'][$userid]) ){
            return self::$cache['user_second'][$userid];
        }
        $second_groupid = WPF()->db->get_var("SELECT `secondary_groups` FROM `".WPF()->tables->profiles."` WHERE `userid` = " . intval($userid));
        if($cache && isset($second_groupid)){
            self::$cache['user_second'][$userid] = $second_groupid;
        }
        return $second_groupid;
    }
	
	function show_selectbox($selected_groupids = array(), $exclude = array() ){
		if( !$selected_groupids ) $selected_groupids = (isset($_POST['usergroup']['groupid'])) ? intval($_POST['usergroup']['groupid']) : 0;
		if( !$selected_groupids ) $selected_groupids = $this->default_groupid;
        $selected_groupids = array_filter( (array)$selected_groupids );
		if( empty($exclude) && isset($_GET['gid']) && intval($_GET['gid']) ) $exclude[] = intval($_GET['gid']);
		$ugroups = $this->usergroup_list_data();
		foreach($ugroups as $ugroup){
			if( in_array($ugroup['groupid'], $exclude) || ( !in_array(4, $selected_groupids) && $ugroup['groupid'] == 4) ) continue;
			echo '<option value="'.esc_attr($ugroup['groupid']).'" '.( in_array($ugroup['groupid'], $selected_groupids) ? 'selected' : '').'>' . esc_html( __($ugroup['name'], 'wpforo') ) . '</option>';
		}
	}
	
	function get_visible_usergroup_ids(){
		return $results = WPF()->db->get_col("SELECT `groupid` FROM `".WPF()->tables->usergroups."` WHERE `visible` = 1");
	}

    function get_secondary_usergroup_ids(){
        return $results = WPF()->db->get_col("SELECT `groupid` FROM `".WPF()->tables->usergroups."` WHERE `secondary` = 1");
    }

    function get_secondary_usergroup_names( $ids ){
	    if( !is_array($ids) ){
            $ids = explode( ',', $ids );
        }
        $ids = array_map('intval', $ids);
        $ids = implode(',', $ids);
        return $results = WPF()->db->get_col("SELECT `name` FROM `".WPF()->tables->usergroups."` WHERE `secondary` = 1 AND `groupid` IN (" . esc_sql( $ids ) . ")");
    }

    function get_secondary_usergroups(){
        return $results = WPF()->db->get_results("SELECT * FROM `".WPF()->tables->usergroups."` WHERE `secondary` = 1", ARRAY_A);
    }

	function get_usergroups_by_role( $role ){
        if( $role ){
            $ugids = WPF()->db->get_col("SELECT `groupid` FROM `" . WPF()->tables->usergroups . "` WHERE `role` = '" . esc_sql($role) . "' ORDER BY `groupid` ASC");
            if( !empty($ugids) ){
                return $ugids;
            }
        }
        return NULL;
    }

	function get_roles(){
        $roles = wp_roles();
        $roles = $roles->get_names();
        return $roles;
    }

    function get_roles_ug(){
        $roles_ug = WPF()->db->get_results("SELECT `name`, `role` FROM `" . WPF()->tables->usergroups . "`", ARRAY_A);
        $roles = wp_roles();
        $roles = $roles->get_names();
        if(!empty( $roles )){
            foreach($roles as $role => $name){
                foreach($roles_ug as $ug){
                    if( wpfval($ug, 'role') && $role == $ug['role'] ){
                        $roles_ug[$role][] = $ug['name'];
                    }
                }
            }
        }
        return $roles_ug;
    }

    function get_roles_woug(){
        $roles_woug = array();
        $roles_ug = WPF()->db->get_col("SELECT `role` FROM `" . WPF()->tables->usergroups . "` GROUP BY `role`");
        $roles = wp_roles();
        $roles = $roles->get_names();
        if(!empty( $roles )){
            foreach($roles as $role => $name){
                if( !in_array($role, $roles_ug) ){
                    $roles_woug[$role] = $name;
                }
            }
        }
        return $roles_woug;
    }

    function get_role_usergroup_relation(){
        $roles = array();
	    $data = WPF()->db->get_results("SELECT `groupid`, `role` FROM `" . WPF()->tables->usergroups . "` ORDER BY `groupid` DESC", ARRAY_A);
        if(!empty( $data )){
            foreach($data as $rel){
                $roles[ $rel['role'] ] = $rel['groupid'];
            }
        }
        return $roles;
    }

    function get_usergroup_role_relation(){
        $usergroups = array();
        $data = WPF()->db->get_results("SELECT `groupid`, `role` FROM `" . WPF()->tables->usergroups . "`", ARRAY_A);
        if(!empty( $data )){
            foreach($data as $rel){
                $usergroups[ $rel['groupid'] ] = $rel['role'];
            }
        }
        return $usergroups;
    }

    function set_ug_roles( $ug_role ){
        if( !empty($ug_role) ){
            foreach( $ug_role as $usergroupid => $role ){
                $role = sanitize_text_field($role);
                WPF()->db->query("UPDATE " . WPF()->tables->usergroups . " SET `role` = '" . esc_sql($role) . "' WHERE `groupid` = " . intval($usergroupid) );
            }
        }
    }

    function set_users_groupid( $groupid_userids ){
        $status = array('error' => 0, 'success' => false );
        if( !empty($groupid_userids) ){
            foreach( $groupid_userids as $group_id => $user_ids ){
                if( $group_id && !empty($user_ids) ){
                    $userids = implode(',', $user_ids);
                    $sql = "UPDATE " . WPF()->tables->profiles ." SET `groupid` = " . intval($group_id) . " WHERE `userid` IN(" . esc_sql($userids) . ")";
                    if( FALSE === WPF()->db->query($sql) ){
                        $status['error'] = WPF()->db->last_error;
                        $status['success'] = false;
                        break;
                    }
                    else{
                        $status['success'] = true;
                    }
                }
            }
        }
        return $status;
    }

    function build_users_groupid_array( $usergroupid_role, $users ){
	    $array = array();
        $group_users = array();
        $user_prime_group = array();
        $user_second_groups = array();
        if( !empty($users) ){
            foreach( $users as $user ){
                if( !empty($user->roles) ){
                    foreach( $user->roles as $role ) {
                        $ugids = wpforo_key($usergroupid_role, $role, 'sort');
                        $ug_count = count($ugids);
                        if(!empty($ugids)){
                            foreach($ugids as $ugid){
                                if( $ug_count == 1 ){
                                    if( !isset($user_prime_group[$user->ID]) ) {
                                        $user_prime_group[$user->ID][] = $ugid;
                                        $group_users[$ugid][] = intval($user->ID);
                                    }
                                    else{
                                        $user_second_groups[$user->ID][] = $ugid;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $array['group_users'] = $group_users;
        $array['user_prime_group'] = $user_prime_group;
        $array['user_second_groups'] = $user_second_groups;
        return $array;
    }

}