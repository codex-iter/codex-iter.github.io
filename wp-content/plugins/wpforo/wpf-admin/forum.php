<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->forum->manage() ) exit;
?>

<!-- Screen Options -->
<?php if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'add', 'edit' ) ) ) : ?>
	
	<div id="screen-meta" class="metabox-prefs" style="display: none; ">
		<div id="screen-options-wrap" class="hidden" tabindex="-1" aria-label="Screen Options Tab" style="display: none; ">
			<form id="adv-settings" action="" method="post">
				<h5><?php _e('Show on screen', 'wpforo'); ?></h5>
				<div class="metabox-prefs">
					<label for="forum_cat-hide"><input class="hide-postbox-tog" name="forum_cat-hide" type="checkbox" id="forum_cat-hide" value="forum_cat" checked="checked"><?php _e('Forum Options', 'wpforo'); ?></label>
					<label for="forum_permissions-hide"><input class="hide-postbox-tog" name="forum_permissions-hide" type="checkbox" id="forum_permissions-hide" value="forum_permissions" checked="checked"><?php _e('Permissions', 'wpforo'); ?></label>
					<label for="forum_slug-hide"><input class="hide-postbox-tog" name="forum_slug-hide" type="checkbox" id="forum_slug-hide" value="forum_slug" checked="checked"><?php _e('Slug', 'wpforo'); ?></label>
					<label for="forum_meta-hide"><input class="hide-postbox-tog" name="forum_meta-hide" type="checkbox" id="forum_meta-hide" value="forum_meta" checked="checked"><?php _e('Forum Meta', 'wpforo'); ?></label>
					<br class="clear">
				</div>
				<h5 class="screen-layout"><?php _e('Screen Layout', 'wpforo'); ?></h5>
				<div class="columns-prefs"><?php _e('Number of Columns', 'wpforo'); ?>:				
					<label class="columns-prefs-1"><input type="radio" name="screen_columns" value="1">1</label>
					<label class="columns-prefs-2"><input type="radio" name="screen_columns" value="2" checked="checked">2</label>
				</div>
			</form>
		</div>
	</div>
	
	<div id="screen-meta-links">
		<div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle" style="">
			<button aria-expanded="true" aria-controls="screen-options-wrap" class="button show-settings screen-meta-active" id="show-settings-link" type="button"><?php _e('Screen Options', 'wpforo'); ?></button>
		</div>
	</div>
    
<?php endif; ?>
<!-- end Screen Options -->

	<div id="icon-edit" class="icon32 icon32-posts-post"></div>
	<div id="wpf-admin-wrap" class="wrap">

	<h2 style="padding:30px 0px 10px 0px; line-height: 20px;">
		<?php _e('Categories and Forums', 'wpforo'); ?> &nbsp;
		<a href="<?php echo admin_url( 'admin.php?page=wpforo-forums&action=add' ) ?>" class="add-new-h2"><?php _e('Add New', 'wpforo'); ?></a>
	</h2>

	<?php WPF()->notice->show(FALSE) ?>
	
	<!-- Forum Hierarchy -->
	<?php if( !isset($_GET['action'])) : ?>
		<?php if( WPF()->forum->manage() ): ?>
			
            <div class="wpf-info-bar" style="line-height: 1em; clear:both; padding: 5px 30px; font-size:15px; display:block; box-shadow:none; margin: 20px 0 10px 0; font-style: italic; background: #FFFFC6; width:90%; position: relative;">
                <a href="https://wpforo.com/docs/root/categories-and-forums/forum-manager/" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 16px; position: absolute; right: 15px; top: 15px;"><i class="far fa-question-circle"></i></a>
            	<ul style="list-style-type: disc; line-height:18px;">
            		<li style="list-style:none; margin-left:-17px; font-style:normal; font-weight:bold;"><i class="fas fa-info-circle" aria-hidden="true"></i>&nbsp; <?php _e('Important Tips', 'wpforo'); ?></li>
                    <li><?php _e('Please drag and drop forum panels to set parent-child hierarchy.', 'wpforo'); ?></li>
            		<li><?php _e('If a category (blue panels) does not have forums (grey panels) it will not be displayed on front-end. Each category should contain at least one forum.', 'wpforo'); ?></li>
                    <li><?php _e('Forums can be displayed with different layouts (Extended, Simplified, Q&A), just edit the top (blue panels) category and set the layout you want. Child forums\' layout depends on the top category (blue panels) layout. They cannot have a different layout.', 'wpforo'); ?></li>
            	</ul>
            </div>
            <br style="clear: both;" />
            
            <form id="forum-hierarchy" encType="multipart/form-data" method="post" action="">
            	<?php wp_nonce_field( 'wpforo-forums-hierarchy' ); ?>
				<input type="hidden" name="forums_hierarchy_submit"/>
				<div id="post-body">
					<ul id="menu-to-edit" class="menu">
						
						<?php WPF()->forum->tree('drag_menu'); ?>
						
					</ul>
				</div><br />
				<div class="major-publishing-actions">
					<div class="publishing-action"><input id="save_menu_footer" class="button button-primary menu-save" name="save_menu" value="<?php _e('Save forums order and hierarchy', 'wpforo'); ?>" onclick="get_forums_hierarchy()" type="button"></div>
				</div>
			</form>	
            <script>
            var menus = false;
			navMenuL10n.saveAlert = null;
			window.onbeforeunload=function(){if(a.menusChanged){return navMenuL10n.saveAlert}}
            </script>
		<?php endif; ?><!--checking edit forum permission-->
	<?php endif; ?>
	<!-- end Forum Hierarchy -->
	<br style="clear: both;"/>
	<!-- Forum Add || Edit -->
	<?php if( ( isset($_GET['action']) && $_GET['action'] == 'add' ) || ( isset($_GET['action']) && $_GET['action'] == 'edit' ) ) : ?>
		<?php if( WPF()->forum->manage() ): ?>
			<?php
                $disabled_forumid = 0;
				$selected_forumid = 0;
                if(!empty($_GET['id'])){
					 $disabled_forumid = array( $_GET['id'] );
                    if( $data = WPF()->forum->get_forum( array('forumid' => $_GET['id']) ) ) $selected_forumid = $data['parentid'];
                }
                if (!empty($_GET['parentid'])){
                    $selected_forumid = $_GET['parentid'];
                }
            ?>
			<div id="poststuff">
				<form name="forum" action="" method="post">
                	<?php wp_nonce_field( 'wpforo-forum-addedit' ); ?>
					<div id="post-body" class="metabox-holder columns-2">
						<div id="post-body-content">
							<input type="hidden" name="wpforo_submit" value="1"/>
							<input type="hidden" name="forum[order]" value="<?php echo esc_attr(isset($data['order']) ? $data['order'] : '') ?>"/>
							<div class="form-wrap">
								<div class="form-field form-required" style="margin-bottom:0px; padding-bottom:0px;">
									<div id="titlediv">
										<div id="titlewrap">
											<input id="title" name="forum[title]"  type="text" value="<?php echo esc_attr(isset($data['title']) ? $data['title'] : '') ?>" size="40" autocomplete="off" required="TRUE" placeholder="<?php _e('Enter forum title here', 'wpforo'); ?>" />
										</div>
									</div>
									<p>&nbsp;</p>
									<div class="form-field">
										<textarea placeholder="<?php _e('Enter description here . . .', 'wpforo'); ?>" name="forum[description]" rows="5" cols="40" style="padding:10px;"><?php echo esc_textarea(isset($data['description']) ? $data['description'] : '') ?></textarea>
										<p><?php _e('This is a forum description. This content will be displayed under forum title on the forum list.', 'wpforo'); ?></p>
									</div>
								</div>
							</div>	
						</div>
						
						<div id="postbox-container-1" class="postbox-container">
							<div id="side-sortables" class="meta-box-sortables ui-sortable">
								
                                
								<div id="forum_cat" class="postbox">
									<div class="handlediv" title="Click to toggle"><br></div>
									<h3 class="hndle"><span><?php _e('Forum Options', 'wpforo'); ?> &nbsp;<a href="https://wpforo.com/docs/root/categories-and-forums/forum-manager/add-new-forum/#forum-options" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 14px;"><i class="far fa-question-circle"></i></a></span></h3>
									<div class="inside">
										<div class="form-field">
											<p><strong><?php _e('Parent Forum', 'wpforo'); ?></strong></p>
											<p>
                                            <select id="parent" name="forum[parentid]" class="postform" <?php echo (isset($data['is_cat']) && $data['is_cat'] == 1 ? 'disabled' : '') ?>>
												<option value="0"><?php _e('No parent', 'wpforo'); ?></option>
												<?php WPF()->forum->tree('select_box', true, $selected_forumid, false, $disabled_forumid); ?>
											</select>
											</p>
											<p class="form-field">
												<label for="use_us_cat"><?php _e('Use as Category', 'wpforo'); ?> &nbsp;<input id="use_us_cat" onclick="document.getElementById('parent').disabled = this.checked; document.getElementById('cat_layout').disabled = !this.checked;" type="checkbox" name="forum[is_cat]" value="1" <?php echo (isset($data['is_cat']) && $data['is_cat'] == 1 ? 'checked' : '') ?>/> </label>
											</p>
											<p><strong><?php _e('Category Layout', 'wpforo'); ?></strong></p>
											<p>
                                            <?php $layouts = WPF()->tpl->find_layouts( WPFORO_THEME ); ?>
                                            <?php if(!empty($layouts)): ?>
                                                <select id="cat_layout" name="forum[cat_layout]" class="postform" <?php $data['cat_layout'] = ( isset($data['cat_layout']) ? $data['cat_layout'] : 1 ); echo ( isset($data['is_cat']) && $data['is_cat'] == 1  ? '' : 'disabled="TRUE"' ); ?> >
                                                    <?php WPF()->tpl->show_layout_selectbox($data['cat_layout']); ?>
                                                </select>
                                            <?php else: ?>
                                            	<p><?php _e('No layout found.', 'wpforo'); ?></p>
                                            <?php endif; ?>
                                            </p>
										</div>
									</div>
								</div>
								
                                <div id="submitdiv" class="postbox">
									<div class="handlediv" title="Click to toggle"><br></div>
									<h3 class="hndle"><span><?php _e('Publish', 'wpforo'); ?></span></h3>
									<div class="inside">
										<div id="major-publishing-actions" style="text-align:right;">
											<?php if( $_GET['action'] == 'edit' ) : ?>
												<a class="wpf-delete button" href="?page=wpforo-forums&id=<?php echo intval($data['forumid']) ?>&action=del" onclick="if (!confirm('<?php _e('Are you sure you want to delete this forum?', 'wpforo'); ?>')) { return false; }"><?php _e('Delete', 'wpforo'); ?></a> &nbsp; 
												<a class="preview button" href="<?php echo wpforo_home_url( (isset($data['slug']) ? $data['slug'] : '') ) ?>" target="wp-preview" id="post-preview"  style="display:inline-block;float:none;"><?php _e('View', 'wpforo'); ?></a> &nbsp;
                                                <input type="submit" name="forum[save_edit]" class="button button-primary forum_submit" style="display:inline-block;float:none;" value="<?php _e('Update', 'wpforo'); ?>">
                                            <?php else: ?>
                                                <input type="submit" name="forum[save_edit]" class="button button-primary forum_submit" style="display:inline-block;float:none;" value="<?php _e('Publish', 'wpforo'); ?>">
                                            <?php endif; ?>
                                            <div class="clear"></div>
										</div>
									</div>
								</div>
                                
                                
                                <div id="forum_permissions" class="postbox">
									<div class="handlediv" title="Click to toggle"><br></div>
									<h3 class="hndle"><span><?php _e('Forum Permissions', 'wpforo'); ?> &nbsp;<a href="https://wpforo.com/docs/root/categories-and-forums/forum-manager/add-new-forum/#forum-permissions" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 14px;"><i class="far fa-question-circle"></i></a></span></h3>
									<div class="inside">
										<table>
											<?php WPF()->forum->permissions(); ?>
										</table>
									</div>
								</div>
                                
							</div>
						</div>
						
						<div id="postbox-container-2" class="postbox-container">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								
								<div id="forum_slug" class="postbox">
									<div class="handlediv" title="Click to toggle"><br></div>
									<h3 class="hndle"><span><?php _e('Forum Slug', 'wpforo'); ?> &nbsp;<a href="https://wpforo.com/docs/root/categories-and-forums/forum-manager/add-new-forum/#forum-slug" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 14px;"><i class="far fa-question-circle"></i></a></span></h3>
									<div class="inside">
										<input name="forum[slug]"  type="text" value="<?php echo esc_attr(isset($data['slug']) ? $data['slug'] : '') ?>" size="40" />
										<p><?php _e('The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'wpforo'); ?> </p><br /> 
									</div>
								</div>
								
                                <div id="forum_icon" class="postbox">
									<div class="handlediv" title="Click to toggle"><br></div>
									<h3 class="hndle"><span><?php _e('Forum Icon', 'wpforo'); ?> &nbsp;<a href="https://wpforo.com/docs/root/categories-and-forums/forum-manager/add-new-forum/#forum-icon" title="<?php _e('Read the documentation', 'wpforo') ?>" target="_blank" style="font-size: 14px;"><i class="far fa-question-circle"></i></a></span></h3>
									<div class="inside" style="padding-top:10px;">
										<div class="form-field">
											<label for="tag-icon" style="display:block; padding-bottom:5px;"><?php _e('Font-awesome Icon', 'wpforo'); ?>:</label>
											<input name="forum[icon]" value="<?php echo (isset($data['icon']) && $data['icon']) ? esc_attr($data['icon']) : 'fas fa-comments'; ?>" type="text"/>
                                            <p style="margin-bottom:0px; margin-top:5px;"><?php _e('You can find all icons', 'wpforo'); ?> <a href="http://fontawesome.io/icons/" target="_blank"><?php _e('here', 'wpforo'); ?>.</a> <?php _e('Make sure you insert a class of font-awesome icon, it should start with fa- prefix like &quot;fas fa-comments&quot;.', '') ?></p>
										</div>
									</div>
								</div>
                                
								<div id="forum_meta" class="postbox">
									<div class="handlediv" title="Click to toggle"><br></div>
									<h3 class="hndle"><span><?php _e('Forum SEO', 'wpforo'); ?></span></h3>
									<div class="inside" style="padding-top:10px;">
										<div class="form-field">
											<label for="tag-description" style="display:block; padding-bottom:5px;"><?php _e('Meta Description', 'wpforo'); ?>:</label>
											<textarea name="forum[meta_desc]" rows="3" cols="40"><?php echo esc_html(isset($data['meta_desc']) ? $data['meta_desc'] : '') ?></textarea>
										</div>
									</div>
								</div>
								
							</div>
							<div id="advanced-sortables" class="meta-box-sortables ui-sortable"></div>
						</div>
						
					</div>
				</form>
			</div>
		<?php endif; ?><!-- chekcing creat forum permission-->
	<?php endif; ?>
	<!-- end Forum Add || Edit -->
	
	<!-- Forum Delete -->
	<?php if( isset($_GET['action']) && $_GET['action'] == 'del') : ?>
		
		<form action="" method="post">
        	<?php wp_nonce_field( 'wpforo-forum-delete' ); ?>
			<input type="hidden" name="wpforo_delete" value="1"/>
			<div class="form-wrap">
				<div class="form-field form-required">			
					<div class="form-field wpf-info-bar" style="padding:25px 20px 15px 20px; margin-top:20px;">
						<table class="wpforo_settings_table">
							<tr>
								<td style="width:50%;">
									<label for="delete_forum" class="menu_delete" style="color: red; font-size:13px; line-height:18px;"><?php _e('This action will also delete all sub-forums, topics and replies.', 'wpforo'); ?></label>
								</td>
								<td width="20px">
									<input id="delete_forum" type="radio" name="forum[delete]" value="1" checked="" onchange="mode_changer('false');"/>
								</td>
							</tr>
							<tr>
								<td>
									<label for="marge"  style="font-size:13px; line-height:18px;"><?php _e('If you want to delete this forum and keep its sub-forums, topics and replies, please select a new target forum in dropdown below', 'wpforo'); ?></label>
								</td>
								<td><input id="marge" type="radio" name="forum[delete]" value="0" onchange="mode_changer('true');"/> </td>
							</tr>
							<tr>
								<td colspan="2">
                                    <select id="forum_select" name="forum[mergeid]" class="postform" disabled="" >
                                        <?php WPF()->forum->tree('select_box', false); ?>
                                    </select>
                                    <p><?php _e('All sub-forums, topics and replies will be attached to selected forum. Layout will be inherited from this forum.', 'wpforo'); ?></p>
                                </td>
							</tr>
                            <tr>
								<td colspan="2">
                                    <input id="forum_submit"  type="submit" name="forum[submit]" class="button button-primary" value="Delete" />
                                </td>
							</tr>
						</table>
					</div>
				</div>
			</div>	
		</form>
	<?php endif; ?>
	<!-- end Forum Delete -->
	
</div><!-- wpwrap -->