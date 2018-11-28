<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
	if( !WPF()->perm->usergroup_can('mp') ) exit;
?>

<div id="wpf-admin-wrap" class="wrap" style="margin-top: 0px">
	<?php wpforo_screen_option() ?>
	<div id="icon-users" class="icon32"><br></div>
	<h2 style="padding:30px 0px 0px 0px;line-height: 20px; margin-bottom:15px;"><?php _e('Front-end Phrases', 'wpforo'); ?> &nbsp;<a href="<?php echo admin_url( 'admin.php?page=wpforo-phrases&action=add' ) ?>" class="add-new-h2"><?php wpforo_phrase('add_new') ?></a></h2>
	<?php WPF()->notice->show(FALSE) ?>
	<?php
		if( !((isset($_GET['action']) && $_GET['action'] != '-1') || (isset($_GET['action2']) && $_GET['action2'] != '-1')) ){
			$fields = array( 'phrase_key', 'phrase_value', 'package' );
			$search_fields = array( 'phrase_key', 'phrase_value' );
			$filter_fields = array( 'langid', 'package' );
			wpforo_create_form_table( 'phrase', 'phraseid', $fields, $search_fields, $filter_fields, array('edit'), array('edit')); 
		}
	?>
	<?php if( (isset($_GET['action']) && $_GET['action'] == 'edit') || (isset($_GET['action2']) && $_GET['action2'] == 'edit') ) : ?>
		<?php if(isset($_GET['id'])){$phrase_ids = array($_GET['id']);}else{ $phrase_ids = explode(',', $_GET['ids']);} ?>
		<form action="" method="POST" id="phrases" class="validate">
        		<?php wp_nonce_field( 'wpforo-phrases-edit' ); ?>
				<table class="form-table">
					<tbody>
						<?php foreach($phrase_ids as $phraseid) : ?>
						<tr class="form-field form-required">
							<th scope="row">
								<?php $data =  WPF()->phrase->get_wpforo_phrase($phraseid); ?>
								<label for="phrase"> <?php wpforo_phrase('phrase_key'); ?>
								<span class="description">(<?php echo esc_html($data['phrase_key']); ?>)</span></label>
							 </th>
							<td>
                            	<textarea name="phrase[data][<?php echo intval($phraseid) ?>][title]" id="phrase" required style="width:80%; height:29px;"><?php wpfo($data['phrase_value'], true, 'esc_textarea'); ?></textarea>
                            </td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<p class="submit">
				<input type="submit" name="phrase[save]" id="createusersub" class="button button-primary" value="<?php wpforo_phrase('update') ?>">
			</p>
		</form>
	<?php endif; ?>
	<?php if( (isset($_GET['action']) && $_GET['action'] == 'add')) : ?>
		<form action="" method="POST" id="phrases" class="validate">
        		<?php wp_nonce_field( 'wpforo-phrase-add' ); ?>
				<table class="form-table">
					<tbody>
						<tr class="form-field form-required">
							<th scope="row">
								<label for="phrase_key"> <?php wpforo_phrase('phrase_key') ?></label>
							 </th>
							<td><input name="phrase[key]" type="text" id="phrase_key" value="" required="" required></td>
							
							<th scope="row">
								<label for="phrase_value"> <?php wpforo_phrase('phrase_value') ?></label>
							 </th>
							<td><input name="phrase[value]" type="text" id="phrase_value" required="" value="" required></td>
						</tr>
					</tbody>
				</table>
			<p class="submit">
				<input type="submit" name="phrase[add]" id="createusersub" class="button button-primary" value="<?php wpforo_phrase('add') ?>">
			</p>
		</form>
	<?php endif; ?>
</div>