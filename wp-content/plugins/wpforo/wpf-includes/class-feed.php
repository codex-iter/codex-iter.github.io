<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

class wpForoFeed{

	function __construct(){}
	
	function rss2_url($echo = true, $general = false){
		$url = wpforo_get_request_uri();
		if(isset(WPF()->current_object['forumid'])){ $forumid = WPF()->current_object['forumid']; }
		if(isset(WPF()->current_object['topicid'])){ $topicid = WPF()->current_object['topicid']; }
		if(isset($forumid) && isset($topicid)){
			$rss2 = $url . '?type=rss2&forum=' . intval($forumid) . '&topic=' . intval($topicid);	
		}
		elseif(isset($forumid) && !isset($topicid)){
			$rss2 = $url . '?type=rss2&forum=' . intval($forumid);
		}
		
		if($general){
			if( $general == 'topic' ){
				$rss2 = $url . '?type=rss2&forum=g&topic=g';
			}
			elseif( $general == 'forum' ){
				$rss2 = $url . '?type=rss2&forum=g';
			}
		}
		
		$rss2 = esc_url($rss2);
		
		if($echo){
			echo $rss2;
		}
		else{
			return $rss2;
		}
	}
	
	function rss2_forum( $forum = array(), $topics = array() ){
		if(empty($forum)) {
		    if(!wpforo_feature('rss-feed')){
                header('HTTP/1.0 404 Not Found', true, 404);
                die();
            }
            else{
		        return;
            }
        }
		header("Content-Type: application/xml; charset" . get_option('blog_charset') );
		echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
		?><rss version="2.0"
               xmlns:atom="http://www.w3.org/2005/Atom"
               xmlns:dc="http://purl.org/dc/elements/1.1/"
               xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
               xmlns:admin="http://webns.net/mvcb/"
               xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
               xmlns:content="http://purl.org/rss/1.0/modules/content/">
            <channel>
                <title>
                	<?php if(!isset($forum['title']) || !$forum['title']): ?>
                    	<?php echo esc_html(WPF()->general_options['title']) . ' - ' . wpforo_phrase('Recent Topics', false) ?>
                    <?php else: ?>
						<?php echo esc_html($forum['title']); ?> - <?php echo esc_html(WPF()->general_options['title']); ?>
                    <?php endif; ?>
                </title>
                <link><?php echo esc_url($forum['forumurl']); ?></link>
                <description><?php echo esc_html(WPF()->general_options['description']); ?></description>
                <language><?php bloginfo_rss( 'language' ); ?></language>
                <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', date('Y-m-d H:i:s'), false); ?></lastBuildDate>
                <generator>wpForo</generator>
                <ttl>60</ttl>
                <?php if(!empty($topics)): ?>
					<?php foreach($topics as $topic): ?>
                    <item>
                        <title><?php echo wpforo_removebb(esc_html($topic['title'])); ?></title>
                        <link><?php echo esc_url($topic['topicurl']); ?></link>
                        <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $topic['created'], false); ?></pubDate>
                        <description><![CDATA[<?php echo wpforo_removebb(esc_html($topic['description'])) ?>]]></description>
                        <content:encoded><![CDATA[<?php echo wpforo_removebb($topic['content']) ?>]]></content:encoded>
                        <?php if($forum['forumurl'] != '#'): ?><category domain="<?php echo esc_url($forum['forumurl']); ?>"><?php echo esc_html($forum['title']); ?></category><?php endif; ?>
                        <dc:creator><?php echo esc_html($topic['author']); ?></dc:creator>
                        <guid isPermaLink="true"><?php echo esc_url($topic['topicurl']); ?></guid>
                    </item>
                    <?php endforeach; ?>
                <?php endif; ?>
            </channel>
        </rss>
        <?php
		exit();
	}
	
	function rss2_topic( $forum = array(), $topic = array(), $posts = array() ){
		if(empty($forum)) {
            if(!wpforo_feature('rss-feed')){
                header('HTTP/1.0 404 Not Found', true, 404);
                die();
            }
            else{
                return;
            }
        }
		header("Content-Type: application/xml; charset" . get_option('blog_charset') );
		echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
		?><rss version="2.0"
               xmlns:atom="http://www.w3.org/2005/Atom"
               xmlns:dc="http://purl.org/dc/elements/1.1/"
               xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
               xmlns:admin="http://webns.net/mvcb/"
               xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
               xmlns:content="http://purl.org/rss/1.0/modules/content/">
            <channel>
                <title>
					<?php if(!isset($topic['title']) || !$topic['title']): ?>
                    	<?php echo esc_html(WPF()->general_options['title']) . ' - ' . wpforo_phrase('Recent Posts', false); ?>
                    <?php else: ?>
                		<?php echo esc_html($topic['title']); ?> - <?php echo esc_html($forum['title']); ?>
                    <?php endif; ?>
                </title>
                <link><?php echo esc_url($topic['topicurl']); ?></link>
                <description><?php echo esc_html(WPF()->general_options['description']); ?></description>
                <language><?php bloginfo_rss( 'language' ); ?></language>
                <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', date('Y-m-d H:i:s'), false); ?></lastBuildDate>
                <generator>wpForo</generator>
                <ttl>60</ttl>
                <?php if(!empty($posts)): ?>
					<?php foreach($posts as $post): ?>
                    <item>
                        <title><?php echo wpforo_removebb(esc_html($post['title'])); ?></title>
                        <link><?php echo esc_url($post['posturl']); ?></link>
                        <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $post['created'], false); ?></pubDate>
                        <description><![CDATA[<?php echo wpforo_removebb(esc_html($post['description'])) ?>]]></description>
                        <content:encoded><![CDATA[<?php echo wpforo_removebb($post['content']) ?>]]></content:encoded>
                        <?php if($forum['forumurl'] != '#'): ?><category domain="<?php echo esc_url($forum['forumurl']); ?>"><?php echo esc_html($forum['title']); ?></category><?php endif; ?>
                        <dc:creator><?php echo esc_html($post['author']); ?></dc:creator>
                        <guid isPermaLink="true"><?php echo esc_url($post['posturl']); ?></guid>
                    </item>
                    <?php endforeach; ?>
                <?php endif; ?>
            </channel>
        </rss>
        <?php
		exit();
	}

}