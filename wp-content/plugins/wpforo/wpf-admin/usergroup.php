<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('vmg') ) exit;
?>

<div id="wpf-admin-wrap" class="wrap"><div id="icon-users" class="icon32"><br /></div>
	<h2 style="padding:30px 0px 10px 0px;line-height: 20px;"><?php _e( 'Usergroups', 'wpforo') ?> <a href="<?php echo admin_url( 'admin.php?page=wpforo-usergroups&action=add' ) ?>" class="add-new-h2"><?php _e( 'Add New', 'wpforo') ?></a></h2>
	<?php WPF()->notice->show(FALSE) ?>
	<?php if( wpfval($_GET, 'sfin') ): ?>
        <div class="notice is-dismissible notice-success" style="width: 94%;">
            <p><?php _e('Role-Usergroup synchronization is complete!', 'wpforo'); ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php _e('Dismiss this notice.'); ?></span>
            </button>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.'); ?></span></button>
        </div>
    <?php endif; ?>
	<!-- ###############################################################   Usergroup Main Form -->
	
	<?php if( !isset($_GET['action']) || ( $_GET['action'] != 'add' && $_GET['action'] != 'del' && $_GET['action'] != 'edit') ) : ?>
		<br/>
        <form action="" method="post" class="wpforo-ajax-form">
            <table id="usergroup_table" class="wp-list-table widefat fixed posts" cellspacing="0">
            <thead>
                <tr>
                    <?php if( wpforo_feature('role-synch')): ?>
                        <th scope="col" id="role" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'User Role', 'wpforo') ?>  &nbsp;<a href="https://codex.wordpress.org/Roles_and_Capabilities" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 14px;"><i class="far fa-question-circle"></i></a></span></th>
                    <?php endif; ?>
                    <th scope="col" id="title" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Usergroup', 'wpforo') ?> &nbsp;<a href="https://wpforo.com/docs/root/members/usergroups-and-permissions/" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 14px;"><i class="far fa-question-circle"></i></a></span></th>
                    <th scope="col" id="count" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Members', 'wpforo') ?></span></th>
                    <th scope="col" id="default" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Default', 'wpforo') ?></span></th>
                    <th scope="col" id="access" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Default Access', 'wpforo') ?></span></th>
                    <th scope="col" id="color" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Color', 'wpforo') ?></span></th>
                    <th scope="col" id="id" class="manage-column column-title" style="padding:10px; width: 4%; font-size:14px; padding-left:15px; font-weight:bold; text-align: center;"><span><?php _e( 'ID', 'wpforo') ?></span></th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php $ugroups = WPF()->usergroup->usergroup_list_data(); ?>
                <?php foreach( $ugroups as $key => $ugroup ) : ?>
                    <tr id="usergroup-<?php echo intval($ugroup['groupid']) ?>" class="format-standard hentry alternate iedit" valign="top">
                        <?php if( wpforo_feature('role-synch')): ?>
                            <td class="post-title page-title column-title" style="border-bottom:1px dotted #CCCCCC; padding-left:20px;">
                                <?php
                                $ug_role = trim($ugroup['role']);
                                $ug_role = ( wp_roles()->is_role( $ug_role ) ) ? $ug_role = '<a href="' . admin_url( 'users.php?role=' . $ugroup['role'] ) .'" title="' . __('View Users', 'wpforo') . '" target="_blank"><i class="far fa-user"></i></a>' : '' ;
                                ?>
                                <span style="font-size: 16px; vertical-align: middle;"><?php echo $ug_role; ?></span> &nbsp;
                                <?php if( $ugroup['groupid'] != 4 && $ugroup['groupid'] != 1) : ?>
                                    <select name="wpf_synch_roles[<?php echo $ugroup['groupid'] ?>]"  style="background:#FDFDFD; display:inline;">
                                        <?php $selected = ( wpfval($ugroup, 'role') ) ? $ugroup['role'] : 'subscriber'; ?>
                                        <?php wp_dropdown_roles( $selected ); ?>
                                    </select>
                                <?php elseif( $ugroup['groupid'] == 1 ): ?>
                                    <span style="font-size: 14px; vertical-align: bottom;">&nbsp;<?php echo $ugroup['role']; ?></span>
                                    <input type="hidden" name="wpf_synch_roles[1]" value="administrator">
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td class="post-title page-title column-title" style="border-bottom:1px dotted #CCCCCC; padding-left:20px; background:#ffffff;">
                            <?php $edit_url = ( $ugroup['groupid'] != 1 ? admin_url( 'admin.php?page=wpforo-usergroups&gid=' . $ugroup['groupid'] . '&action=edit' ) : '#') ?>
                            <strong>
                                <a class="row-title" href="<?php echo esc_url($edit_url) ?>" title="<?php _e( 'Usergroup Name', 'wpforo') ?>">
                                    <?php echo esc_html($ugroup['name']) ?>
                                </a> <?php if( wpfval($ugroup, 'secondary') ): ?>&nbsp;<span style="font-size: 12px; color: #777777;" title="<?php _e('Also used as Secondary Usergroup'); ?>"><i class="fas fa-pause"></i></span><?php endif; ?>
                            </strong>
                            <div class="row-actions">
                                <span class="edit"><a title="<?php _e( 'Edit this usergroup', 'wpforo') ?>"  href="<?php echo admin_url( 'admin.php?page=wpforo-usergroups&gid=' . intval($ugroup['groupid']) . '&action=edit' ) ?>"><?php _e( 'Edit', 'wpforo') ?></a> |</span>
                                <?php if( $ugroup['groupid'] != 4 && $ugroup['groupid'] != 1 ): ?>
                                    <span class="trash"><a class="submitdelete" title="<?php _e( 'Delete this usergroup', 'wpforo') ?>" href="<?php echo admin_url( 'admin.php?page=wpforo-usergroups&gid=' . intval($ugroup['groupid']) . '&action=del' ) ?>"><?php _e( 'Delete', 'wpforo') ?></a> |</span>
                                <?php endif; ?>
                                <span class="view"><a title="<?php _e( 'View users list in this usergroup', 'wpforo') ?>"  href="<?php echo admin_url( 'admin.php?ids=&page=wpforo-members&s=&action=-1&groupid=' . intval($ugroup['groupid']) . '&paged=1&action2=-1' ) ?>" rel="permalink"><?php _e( 'View', 'wpforo') ?></a></span>
                            </div>
                        </td>
                        <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#ffffff;">
                            <?php if( $ugroup['groupid'] != 4) : ?>
                                <strong><a class="row-title" href="<?php echo admin_url( 'admin.php?ids=&page=wpforo-members&s=&action=-1&groupid=' . intval($ugroup['groupid']) . '&paged=1&action2=-1' ) ?>" title="<?php _e( 'The number of forum members with this usergroup. Click to view members.', 'wpforo') ?>"><i class="fas fa-user"></i>&nbsp; <?php echo intval($ugroup['count']) ?></a></strong>
                            <?php endif; ?>
                        </td>
                        <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#ffffff;">
                            <strong style="color: #00a636">
                                <?php if( $ugroup['groupid'] == WPF()->usergroup->default_groupid ) : _e( 'is Default', 'wpforo' ); ?>
                                <?php elseif( $ugroup['groupid'] == 4 || $ugroup['groupid'] == 1) : ?>
                                <?php else : ?>
                                    <a class="row-title" href="<?php echo admin_url('admin.php?page=wpforo-usergroups&default=' . intval($ugroup['groupid'])) ?>" title="<?php _e('Users get the Default Usergroup on registration', 'wpforo') ?>"><?php _e('Set as Default', 'wpforo') ?></a>
                                <?php endif ?>
                            </strong>
                        </td>
                        <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#ffffff;">
                            <strong><?php echo $ugroup['access'] ?></strong>
                        </td>
                        <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#ffffff;">
                            <strong><?php if(!isset($ugroup['color']) || !$ugroup['color']): ?><?php _e( 'default (#15)', 'wpforo') ?><?php else: ?><input type="color" value="<?php echo $ugroup['color'] ?>" disabled /><?php endif; ?></strong>
                        </td>
                        <td class="post-title page-title column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; background:#ffffff; text-align: center;">
                            <strong><?php echo $ugroup['groupid'] ?></strong>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <?php if( wpforo_feature('role-synch')): ?>
                            <td style="text-align: center;">
                                <button class="button button-primary forum_submit wpf-synch-roles" title="<?php _e('Synchronize Users Usergroups and Roles', 'wpforo') ?>" style="margin: 5px auto;" />
                                    <?php _e('Synchronize', 'wpforo') ?>
                                    <i class="fas wpf-spinner"></i>
                                </button>
                                <?php
                                    $wpf_nonce = wp_create_nonce('wpforo_synch_roles');
                                    $wpf_data = get_option('wpforo-synch-roles');
                                    $wpf_id = ( wpfval($wpf_data, 'id') ) ? intval($wpf_data['id']) : 0;
                                    $wpf_start = ( wpfval($wpf_data, 'start') ) ? intval($wpf_data['start']) : 0;
                                    $wpf_step = ( wpfval($wpf_data, 'step') ) ? intval($wpf_data['step']) : 1;
                                    $wpf_left = ( wpfval($wpf_data, 'left') ) ? intval($wpf_data['left']) : 0;
                                ?>
                                <input type="hidden" name="wpf-start-id" value="<?php echo intval($wpf_id) ?>" class="wpf-start-id"/>
                                <input type="hidden" name="wpf-start" value="<?php echo intval($wpf_start) ?>" class="wpf-start"/>
                                <input type="hidden" name="wpf-step" value="<?php echo intval($wpf_step) ?>" class="wpf-step"/>
                                <input type="hidden" name="wpf-left-users" value="<?php echo intval($wpf_left) ?>" class="wpf-left-users"/>
                            </td>
                        <?php endif; ?>
                        <td style="text-align:left; vertical-align: middle; padding:10px 20px;"><span class="wpf-progress" style="font-size: 14px; display: inline-block; font-weight: 600; line-height: 18px;">&nbsp;</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </form>

        <?php if( wpforo_feature('role-synch')): ?>
            <p class="description" style="box-shadow: 1px 1px 6px #cccccc; background: #f7f7f7; padding: 20px; width: 95%; margin: 20px 0px 0px 0px; font-size: 14px;">
                <span style="color: #ff4b3c; font-weight: 600;"><?php _e('Note:', 'wpforo') ?></span>
                <?php _e('The [Synchronize] button changes all users Usergroups according to the users Roles. For example, if you select "Contributor" Role for "Registered" Usergroup, all users with "Contributor" Role will get "Registered" Usergroup in forum. The synchronization process may take a few seconds or dozens of minutes, it depends on the number of users. Please be patient, don\'t close this page and wait until the progress counter says 100% completed.', 'wpforo'); ?>
            </p>
        <?php endif; ?>

        <p id="synch" style="margin: 4px;">&nbsp;</p>

        <?php if( wpforo_feature('role-synch')): ?>
            <?php $roles_wug = WPF()->usergroup->get_roles(); $roles_ug = WPF()->usergroup->get_roles_ug(); $roles_users = count_users(); ?>
            <?php if(!empty($roles_wug)): ?>
                <h1 style="padding-bottom: 0px; width: 97%; margin-bottom: 15px;">&nbsp;<?php _e('User Roles'); ?></h1>
                <p class="description" style="border-top: 1px solid #cccccc; background: #f7f7f7; padding: 15px 20px; width: 95%; margin: 10px 0px 20px 0px; font-size: 14px;">
                    <?php _e('In the table above (Usergroups) you can see the list of all available forum Usergroups. The first column of this table displays selected User Roles which are synched with certain Usergroup. However some User Roles are still not synced with any Usergroup of your forum. You can find not-synced User Roles in the table below (User Roles). If you use any of these not-synced User Roles and you want to grant some forum accesses to users of these User Roles you should create new Usergroups for each of them. Once new Usergroup is created, you should use the [Synchronize] button in the table above to synchronize User Roles with new Usergroups.'); ?>
                </p>
                <table id="usergroup_table" class="wp-list-table widefat fixed posts" cellspacing="0">
                    <thead>
                    <tr>
                        <th scope="col" id="role" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Role Name', 'wpforo') ?></span></th>
                        <th scope="col" id="id" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'User Role', 'wpforo') ?></span></th>
                        <th scope="col" id="title" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Usergroups', 'wpforo') ?></span></th>
                        <th scope="col" id="count" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold;"><span><?php _e( 'Users', 'wpforo') ?></span></th>
                        <th scope="col" id="default" class="manage-column column-title" style="padding:10px; font-size:14px; padding-left:15px; font-weight:bold; width: 40%;"><span><?php _e( 'Status', 'wpforo') ?></th>
                    </tr>
                    </thead>
                    <tbody id="the-list">
                        <?php foreach( $roles_wug as $role_key => $role_name ): ?>
                            <tr>
                                <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#F9F9F9; font-weight: 600; font-size: 14px;"><?php echo $role_name; ?></td>
                                <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#ffffff; color: #c420fa; font-weight: 600;"><?php echo $role_key; ?></td>
                                <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#ffffff;"><?php echo (wpfval($roles_ug, $role_key)) ? '<span style="color:#43a6df; font-weight: 600;">' . implode(', ', $roles_ug[$role_key]) . '</span>' : 'default' ; ?></td>
                                <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding-left:20px; background:#ffffff; font-weight: 600; font-size: 14px;">
                                    <?php echo ( wpfval($roles_users, 'avail_roles') && wpfval($roles_users, 'avail_roles', $role_key) && $roles_users['avail_roles'][$role_key] ) ? '<a href="' . admin_url( 'users.php?role=' . $role_key ) .'" title="' . __('View Users', 'wpforo') . '" target="_blank"><i class="far fa-user"></i> &nbsp;'. intval($roles_users['avail_roles'][$role_key]) .'</a>' : 0; ?>
                                </td>
                                <td class="column-title" style="border-bottom:1px dotted #CCCCCC; vertical-align:middle; padding:10px; background:#ffffff;">
                                    <?php if(!wpfval($roles_ug, $role_key)): ?>
                                        <a href="<?php echo admin_url( 'admin.php?page=wpforo-usergroups&action=add&role=' . $role_key ) ?>" class="add-new-h2" style="display: inline-block; vertical-align: middle; top: 0;"><?php _e( 'Add Usergroup to synch', 'wpforo') ?></a>
                                    <?php elseif( count($roles_ug[$role_key]) > 1 ): ?>
                                        <span style="color: #f61700; font-weight: 600; padding: 0px 5px; display: inline-block;"><?php _e('Not Synched', 'wpforo') ?></span>
                                        <p class="wpf-info" style="padding: 0px 4px;"><?php _e('One User Role cannot be synched with multiple Usergroups.', 'wpforo') ?></p>
                                    <?php else: ?>
                                        <span style="color: #00a636; font-weight: 600; padding: 0px 5px; display: inline-block;"><?php _e('Synched', 'wpforo') ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    $(document).delegate('.wpf-synch-roles', 'click', function (e) {
                        e.preventDefault(); if ($('.wpf-left-users').val() >= 0 ) { var btn = $(this); btn.attr('disabled', 'disabled'); $('.fas', btn).addClass('fa-pulse fa-spinner').removeClass('wpf-spinner'); wpforo_update_roles(btn); }
                    });
                    function wpforo_update_roles(btn) {
                        inprocess = true; var data = btn.parents('.wpforo-ajax-form').serialize();
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: { wpforo_synch_roles_data: data, action: 'wpforo_synch_roles', checkthis: '<?php echo wp_create_nonce( "wpforo_synch_roles" ) ?>'}
                        }).done(function (response) {
                            try {
                                var resp = $.parseJSON(response);
                                $('.wpf-step').val(resp.step);
                                $('.wpf-start').val(resp.start);
                                $('.wpf-start-id').val(resp.id);
                                $('.wpf-left-users').val(resp.left);
                                if (resp.progress < 100) {
                                    wpforo_update_roles(btn);
                                } else {
                                    btn.removeAttr("disabled"); $('.fas', btn).removeClass('fa-pulse fa-spinner').addClass('wpf-spinner');
                                }
                                if (resp.progress <= 1) {
                                    $('.wpf-progress').text(1 + '%');
                                } else {
                                    if (resp.progress < 100) {
                                        $('.wpf-progress').text(resp.progress + '%');
                                    } else {
                                        $('.wpf-progress').css({'color': '#00a636'}); $('.wpf-progress').text(resp.progress + '% <?php _e('Complete!', 'wpforo'); ?>'); $('.wpf-left-users').val(0); $('.wpf-step').val(0); $('.wpf-start-id').val(0); inprocess = false;
                                        window.location.replace("<?php echo admin_url('admin.php?page=wpforo-usergroups&sfin=1') ?>");
                                    }
                                }
                            } catch (e) {
                                console.log(e);
                            }
                        });
                    }
                });
            </script>
        <?php endif; ?>
	<?php endif; ?>


	<!-- ###############################################################  Usergroup Main Form END -->
	
	<!-- ###############################################################  Add / Edit Usergroup Form -->
	
	<?php if( isset($_GET['action']) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ): ?>
	
    <div class="wpf-info-bar" style="margin-top:20px;">
        <div class="form-wrap">
			<form id="add_ug" action="" method="post">
            	<?php wp_nonce_field( 'wpforo-usergroup-addedit' ); ?>
				<input type="hidden" name="usergroup[action]" value="<?php echo ( $_GET['action'] == 'add' ? 'add' : 'edit' ) ?>"/>
				<div style="width:100%; margin-bottom:12px;">
                    <div style="display:block; float:left; padding-right:20px; width:30%; padding-bottom:15px;">
                        <div class="wpf-label-big">
                        <?php _e( 'Usergroup Name', 'wpforo'); if( isset($_GET['gid']) && $_GET['gid'] == 4 ) echo '<span>: ' . __('Guest', 'wpforo') . '</span>'; ?>
                            &nbsp;<a href="https://wpforo.com/docs/root/members/usergroups-and-permissions/" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 14px;"><i class="far fa-question-circle"></i></a><br>
						<?php 
                            if(isset( $_GET['gid'] )){
                                $group = WPF()->usergroup->get_usergroup($_GET['gid']);
                                $group_name = $group['name'];	
                                $group_color = $group['color'];
                            }else{
								$group = array();
                                $group_name  = '';
                                $group_color  = '';
                            }
                         ?>
                        </div>
                        <input name="usergroup[name]" <?php echo ( isset($_GET['gid']) && $_GET['gid'] == 4 ) ? 'type="hidden"' : 'type="text"'; ?>  value="<?php echo esc_attr($group_name) ?>" required="TRUE" style="background:#FDFDFD; min-width:320px;"/>
                        <div style="display:inline-block; margin-top:10px;">
                            <label for="wpf_usergroup_color" style="display:inline-block;"><?php _e('Usergroup Color', 'wpforo') ?>: </label>
                            <input id="wpf_usergroup_color" style="display:inline-block; vertical-align:middle; width:100px;" type="color" name="usergroup[color]" onchange="" value="<?php echo $group_color ?>"> <input id="wpf_usergroup_colorx" style="display:inline-block; vertical-align:middle; width:100px; padding:1px 5px;" type="text" name="usergroup[colorx]" value="<?php echo $group_color ?>"><label style="text-align:right; display:block;"><?php _e('use default link color', 'wpforo'); ?> <input type="checkbox" name="wpfugc" value="default" <?php if(!$group_color): ?> checked="checked"<?php endif; ?> /></label>
                        	<script>
                            	(function($) {
									var $foo = $('#wpf_usergroup_color');
									var $bar = $('#wpf_usergroup_colorx');
									function onChange() { $bar.val($foo.val()); };
									$('#wpf_usergroup_color') .change(onChange) .keyup(onChange);
								})(jQuery);
								(function($) {
									var $foo = $('#wpf_usergroup_colorx');
									var $bar = $('#wpf_usergroup_color');
									function onChange() { $bar.val($foo.val()); };
									$('#wpf_usergroup_colorx') .change(onChange) .keyup(onChange);
								})(jQuery);
                            </script>
                        </div>
                    </div>
                    <?php if( isset($_GET['gid']) && $_GET['gid'] == 4 ): ?>
                    	<input type="hidden" name="usergroup[role]" value=""/>
                    <?php elseif( isset($_GET['gid']) && $_GET['gid'] == 1 ): ?>
                        <input type="hidden" name="usergroup[role]" value="administrator"/>
                    <?php else: ?>
                        <div style="display:block; float:left; width:20%; padding-bottom:15px;">
                            <div class="wpf-label-big"><?php _e('User Role', 'wpforo') ?></div>
                            <select name="usergroup[role]"  style="background:#FDFDFD; display:block;">
                                <?php if(wpfval($_GET, 'role')){ $selected = sanitize_title( $_GET['role'] ); } else{ $selected = (!empty($group) && isset($group['role'])) ? $group['role'] : 'subscriber'; } ?>
                                <?php wp_dropdown_roles( $selected ); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div style="display:block; float:left; width:40%;">
                    	<?php if( !isset( $_GET['gid'] ) ): ?>
							<div class="wpf-label-big"><?php _e('Default Forum Access', 'wpforo') ?></div>
							<select name="usergroup[access]"  style="background:#FDFDFD; display:block;">
								<?php $accesses = WPF()->perm->get_accesses(); ?>
								<?php foreach( $accesses as $accesse ): ?>
									<option value="<?php echo esc_attr($accesse['access']) ?>" <?php if( $accesse['access'] == 'standard' ) echo ' selected="selected"'; ?>><?php echo esc_html($accesse['title']) ?></option>
								<?php endforeach ?>
							</select>
							<div class="wpf-info" style="line-height:17px; display:block; margin-top:6px;">
								<?php _e('This is only used when a new Usergroup is created, it automatically gets the selected Forum Access in all forums.', 'wpforo') ?>
							</div>
						<?php endif; ?>
						<div style="padding: 10px 1px 5px 1px;">
							<label style="display: block;"><input type="checkbox" name="usergroup[visible]" value="1" <?php echo (wpfval($group, 'visible')) ? 'checked="checked"' : ''; ?> /> <?php _e('Display on Members List', 'wpforo') ?></label>
                            <?php if( isset($_GET['gid']) && ($_GET['gid'] == 4 || $_GET['gid'] == 1) ): ?>
                                <input type="hidden" name="usergroup[secondary]" value="0">
                            <?php else: ?>
                                <label style="display: block;"><input type="checkbox" name="usergroup[secondary]" value="1" <?php echo (wpfval($group, 'secondary')) ? 'checked="checked"' : ''; ?> /> <?php _e('Can be also used as Secondary Usergroup', 'wpforo') ?></label>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </div>

				<?php  $cans = WPF()->perm->usergroup_cans_form( ( isset($_GET['gid'] ) ? $_GET['gid'] : FALSE ) ); ?>
                <?php  $n = 0; foreach( $cans as $can => $data ) : ?>
                     <?php if( $n%4 == 0 ): ?>
                     	</table>
                        <table class="wpf-table-box-left" style="margin-right:15px; margin-bottom:15px;  min-width:320px;">
		                     <?php endif; ?>
		                     <tr>
		                         <th class="wpf-dw-td-nowrap"><label class="wpf-td-label" for="wpf-can-<?php echo esc_attr($can) ?>"><?php echo esc_html( __($data['name'], 'wpforo') ) ?></label></th>
		                         <td class="wpf-dw-td-value"><input id="wpf-can-<?php echo esc_attr($can) ?>" type="checkbox" name="cans[<?php echo esc_attr($can) ?>]" value="1" <?php echo ( $data['value'] ) ? 'checked="checked"' : ''; ?>></td>
		                     </tr>
		                <?php $n++; endforeach; ?>
						</table>
                <div class="clear"></div>
				<input type="submit" class="button button-primary forum_submit" value="<?php echo ( $_GET['action'] == 'add' ? __( 'add', 'wpforo') : __( 'save', 'wpforo') ); ?>">
			</form>
		</div>
    </div>
    
    	
	<?php endif; ?>	
	<!-- ###############################################################  END of Add  / Edit Usergroup -->
	
	<!-- ###############################################################  DELETE Usergroup -->
	<?php if( isset($_GET['action']) && $_GET['action'] == 'del') : ?>
			<form action="" method="post">
            <?php wp_nonce_field( 'wpforo-usergroup-delete' ); ?>
			<input type="hidden" name="wpforo_delete" value="1"/>
			<div class="form-wrap">
				<div class="form-field form-required">
					<div class="form-field">
						<table>
							<tr>
								<td>
									<label for="delete_ug" class="menu_delete" style="color: red;">
										<?php _e( 'Delete Chosen Usergroup And Users', 'wpforo') ?>
									</label>
								</td>
								<td width="20px">
									<input id="delete_ug" type="radio" name="usergroup[delete]" value="1"  onchange="mode_changer_ug('false');"/>
								</td>
							</tr>
							<tr>
								<td>
									<label for="marge">
										<?php _e( 'Delete Chosen Usergroup And Join Users To Other Usergroup', 'wpforo') ?>
									</label>
								</td>
								<td>
									<input id="marge" type="radio" name="usergroup[delete]" value="0" checked="" onchange="mode_changer_ug('true');"/>
								</td>
							</tr>
							<tr>
								<td>
									<select id="ug_select" name="usergroup[mergeid]" class="postform" >
										<?php WPF()->usergroup->show_selectbox() ?>
									</select>
									<p><?php _e( 'Users will be join this usergroup', 'wpforo') ?></p>
								</td>
							</tr>
						</table>
					</div>
					<input id="ug_submit"  type="submit" name="usergroup[submit]" class="button button-primary forum_submit" value="<?php _e( 'Delete', 'wpforo') ?>" />
				</div>
			</div>
			</form>
	<?php endif; ?>	
	<!-- ###############################################################  DELETE Usergroup -->
	
	