<?php
	
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

$args = array(); $items_count = 0;
if(!empty($_GET['wpfpaged'])) $paged = intval($_GET['wpfpaged']);
$args['offset'] = ($paged - 1) * WPF()->post->options['tags_per_page'];
$args['row_count'] = WPF()->post->options['tags_per_page'];
$tags = WPF()->topic->get_tags($args, $items_count);
?>
<div class="wpforo-tags-wrap">
    <div class="wpf-head-bar">
         <h1 id="wpforo-title" style="padding-bottom:0px; margin-bottom:0px;">
            <?php wpforo_phrase('Topic Tags') ?>
         </h1>
    </div>

    <div class="wpf-search-bar" style="margin-top: 10px;">
        <form action="<?php echo wpforo_home_url() ?>" method="get">
            <?php wpforo_make_hidden_fields_from_url( wpforo_home_url() ) ?>
            <div class="wpforo-table">
                <div class="wpforo-tr">
                    <div class="wpforo-td">
                        <span class="wpf-search-label wpfcl-1">&nbsp;<?php wpforo_phrase('Search Phrase') ?>:</span><br />
                        <input type="text" name="wpfs" class="wpfs wpfw-90" value="" />
                    </div>
                    <div class="wpforo-td">
                        <span class="wpf-search-label wpfcl-1">&nbsp;<?php wpforo_phrase('Search Type') ?>:</span><br />
                        <select name="wpfin" class="wpfw-90 wpfin">
                            <option value="tag" selected="selected">&nbsp;<?php wpforo_phrase('Find Topics by Tags') ?></option>
                            <option value="entire-posts">&nbsp;<?php wpforo_phrase('Search Entire Posts') ?></option>
                            <option value="titles-only">&nbsp;<?php wpforo_phrase('Search Titles Only') ?></option>
                            <option value="user-posts">&nbsp;<?php wpforo_phrase('Find Posts by User') ?></option>
                            <option value="user-topics">&nbsp;<?php wpforo_phrase('Find Topics Started by User') ?></option>
                        </select>
                    </div>
                    <div class="wpforo-td wpf-last" style="vertical-align: bottom; text-align: left; padding: 10px 2px 5px;">
                        <input type="submit" class="wpf-search" value="<?php wpforo_phrase('Search') ?>" />
                    </div>
                </div>
            </div>
        </form>
    </div>

    <hr style="margin: 20px 0px" />

    <div class="wpforo-tags-content wpfr-tags wpf-tags">
        <?php if( WPF()->post->options['tags'] ): ?>
            <?php if( !empty($tags) ): ?>
                <?php foreach( $tags as $tag ): ?>
                    <tag><a href="<?php echo wpforo_home_url() . '?wpfin=tag&wpfs=' . $tag['tag'] ?>"><?php echo esc_html($tag['tag']); ?><?php if( $tag['count'] ) echo ' &nbsp;[' . $tag['count'] . ']&nbsp;'; ?></a></tag>
                <?php endforeach ?>
            <?php else: ?>
                <p class="wpf-p-error"><?php wpforo_phrase('No tags found') ?>  </p>
            <?php endif; ?>
        <?php else: ?>
            <p class="wpf-p-error"><?php wpforo_phrase('Tags are disabled') ?>  </p>
        <?php endif; ?>
        <div class="wpf-clear"></div>
    </div>
    <div class="wpf-snavi">
    <?php WPF()->tpl->pagenavi($paged, $items_count, FALSE); ?>
    </div>
</div>