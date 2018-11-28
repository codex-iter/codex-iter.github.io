<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;
 

class wpForoNotices{
	
	function __construct(){
		$this->init();
	}
 	
 	/**
	 * @return void
	 */
 	private function init(){
		if( !wpforo_is_session_started() && ( !is_admin() || (!empty($_GET['page']) && strpos($_GET['page'], 'wpforo-') !== false ) ) ) session_start();
	}
 	
 	/**
	 * 
	 * @param string|array $args
	 * @param string $type (e.g. success|error)
     * @param string|array $s
	 * 
	 * @return bool
	 */
	public function add( $args, $type = 'neutral', $s = array() ){
		if(!$args) return FALSE;
        $args = (array) $args;
        if( $s && count($args) == 1 && is_array($s) && isset($s[0]) && !is_array($s[0]) ){
            $s = array($s);
        }else{
            $s = (array) $s;
        }

        if( wpforo_is_session_started() ){
            $type = strtolower($type);
            if( !isset($_SESSION['wpforo_notices']) ) $_SESSION['wpforo_notices'] = array();
            if( !isset($_SESSION['wpforo_notices'][$type]) ) $_SESSION['wpforo_notices'][$type] = array();

            foreach($args as $key => $arg){
                if( $s && isset($s[$key]) ){
                    $args[$key] = wpforo_sprintf_array( wpforo_phrase($arg, FALSE), $s[$key] );
                }else{
                    $args[$key] = wpforo_phrase($arg, FALSE);
                }
            }

            $_SESSION['wpforo_notices'][$type] = array_merge( (array) $_SESSION['wpforo_notices'][$type], (array) $args);
            $_SESSION['wpforo_notices'][$type] = array_unique($_SESSION['wpforo_notices'][$type]);
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	* 
	* @return bool
	* 
	*/
	public function clear(){
		if( wpforo_is_session_started() ){
            $_SESSION['wpforo_notices'] = array();
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	* <p class="success">success msg text</p><p class="error">error msg text</p>
	* 
	* @return string
	*/
	public function get_notices(){
	    $inner = '';
		if(empty($_SESSION['wpforo_notices'])) return $inner;

        foreach($_SESSION['wpforo_notices'] as $type => $notice){
            $notice = (array) $notice;
            foreach ($notice as $msg){
                if( !is_array($msg) ){
                    $msg = trim($msg);
                    if($msg) $inner .= sprintf('<p class="%s">%s</p>', sanitize_html_class($type), $msg);
                }
            }
        }
		
		$this->clear();
		return $inner;
	}
	
	/**
	* 
	* @param bool $frontend (default TRUE)
	* 
	* @return void
	*/
	public function show( $frontend = TRUE ){
		if(empty($_SESSION['wpforo_notices'])) return;
		$inner = '';
		$backend_inner = '';
		foreach($_SESSION['wpforo_notices'] as $type => $notice){
            $notice = (array) $notice;
            foreach ($notice as $msg){
                if( !is_array($msg) ){
                    $msg = trim($msg);
                    if($msg) {
                        $inner .= sprintf('<p class="%s">%s</p>', sanitize_html_class($type), $msg);
                        $backend_inner .= sprintf(
                    '<div class="notice is-dismissible notice-%s">
                                <p>%s</p>
                                <button type="button" class="notice-dismiss">
                                    <span class="screen-reader-text">%s</span>
                                </button>
                            </div>',
                        sanitize_html_class($type), wpforo_kses($msg), __('Dismiss this notice.', 'wpforo'));
                    }
                }
            }
        }
        if($frontend) : ?>
            <script type="text/javascript">
	    		jQuery(document).ready(function($){
	    		    var msg_box = $("#wpf-msg-box");
                    msg_box.html("<?php echo addslashes(wpforo_kses($inner)) ?>");
                    msg_box.show(150).delay(1000);
                    setTimeout(function(){ $("#wpf-msg-box > p.error").remove(); }, 6500);
                    setTimeout(function(){ $("#wpf-msg-box > p.success").remove(); }, 3000);
				});
			</script>
		<?php else :
            echo $backend_inner;
        endif;

		$this->clear();
	}
	
	
	public function addonNote() {
        $lastHash = get_option('wpforo-addon-note-dismissed');
		$first = get_option('wpforo-addon-note-first');
		if( !$lastHash ){
			$hash = $this->addonHash();
        	update_option('wpforo-addon-note-dismissed', $hash);
			update_option('wpforo-addon-note-first', 'true');
		}
		elseif( $lastHash || $first == 'false' ){
			$lastHashArray = explode(',', $lastHash);
			$currentHash = $this->addonHash();
			if ($lastHash != $currentHash) {
				?>
				<div class="updated notice wpforo_addon_note is-dismissible" style="margin-top:10px;">
					<p style="font-weight:normal; font-size:15px; border-bottom:1px dotted #DCDCDC; padding-bottom:10px; width:95%;"><strong><?php _e('New Addons for Your Forum!', 'wpforo'); ?></strong><br><span style="font-size:14px;"><?php _e('Extend your forum with wpForo addons', 'wpforo'); ?></span></p>
					<div style="font-size:14px;">
						<?php
						foreach (WPF()->addons as $key => $addon) {
							if (in_array($addon['title'], $lastHashArray))
								continue;
							?>
							<div style="display:inline-block; min-width:27%; padding-right:10px; margin-bottom:1px;border-bottom:1px dotted #DCDCDC; border-right:1px dotted #DCDCDC; padding-bottom:10px;"><img src="<?php echo $addon['thumb'] ?>" style="height:40px; width:auto; vertical-align:middle; margin:0 10px; text-decoration:none;" />  <a href="<?php echo $addon['url'] ?>" style="text-decoration:none;" target="_blank">wpForo <?php echo $addon['title']; ?></a></div>
							<?php
						}
						?>
						<div style="clear:both;"></div>
					</div>
					<p>&nbsp;&nbsp;&nbsp;<a href="<?php echo admin_url('admin.php?page=wpforo-addons') ?>"><?php _e('View all Addons', 'wpforo'); ?> &raquo;</a></p>
				</div>
				<script>jQuery(document).on( 'click', '.wpforo_addon_note .notice-dismiss', function() {jQuery.ajax({url: ajaxurl, data: { action: 'dismiss_wpforo_addon_note'}})})</script>
				<?php
			}
		}
    }

    public function dismissAddonNote() {
        $hash = $this->addonHash();
        update_option('wpforo-addon-note-dismissed', $hash);
        exit();
    }

    public function dismissAddonNoteOnPage() {
        $hash = $this->addonHash();
        update_option('wpforo-addon-note-dismissed', $hash);
    }

    public function addonHash() {
        $viewed = '';
        foreach (WPF()->addons as $key => $addon) {
            $viewed .= $addon['title'] . ',';
        }
        $hash = $viewed;
        return $hash;
    }

    public function refreshAddonPage() {
        $lastHash = get_option('wpforo-addon-note-dismissed');
        $currentHash = $this->addonHash();
        if ($lastHash != $currentHash) {
            ?>
            <script language="javascript">jQuery(document).ready(function () {
                    location.reload();
                });</script>
            <?php
        }
    }
}