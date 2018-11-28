<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class wpForoSEO
{
    public $options;
    private $default;
    private $stylesheet;
    private $http_protocol = 'HTTP/1.1';

    public function __construct()
    {
        $stylesheet_url       = preg_replace( '/(^http[s]?:)/', '', WPFORO_URL . '/wpf-assets/css/wpforo-sitemap.xsl' );
        $this->stylesheet     = '<?xml-stylesheet type="text/xsl" href="' . esc_url( $stylesheet_url ) . '"?>';

        if ( !empty( $_SERVER['SERVER_PROTOCOL'] ) ) {
            $this->http_protocol = sanitize_text_field( $_SERVER['SERVER_PROTOCOL'] );
        }

        add_action('init', array($this, 'init'), 9);
    }

    public function init()
    {
        $this->init_defaults();
        $this->init_options();
        $this->init_hooks();
    }

    private function init_defaults()
    {
        $this->default = new stdClass();
        $this->default->options = array(
            'members_sitemap' => 1,
            'forums_sitemap' => 1,
            'topics_sitemap' => 1,
            'sitemap_items_per_page' => 1000,
            'allow_ping' => 1,
            'ping_immediately' => 0
        );
    }

    private function init_options()
    {
        $this->options = apply_filters('wpforo_seo_options', get_wpf_option('wpforo_seo_options', $this->default->options));
        if( !wpforo_feature('seo-profile') ) $this->options['members_sitemap'] = 0;
    }

    private function init_hooks()
    {
        add_filter('wpforo_before_init_current_object', array($this, 'redirect'), 10, 2);
        add_action('wpforo_ping_search_engines', array( $this, 'ping_search_engines' ) );
        add_action('wpforo_hit_sitemap_index', array( $this, 'hit_sitemap_index' ) );
    }

    public function sitemap_url($url)
    {
        $date = ( !empty($url['lastmod']) ? date('c', strtotime($url['lastmod']) ) : null );
        $url['loc'] = htmlspecialchars($url['loc']);

        $output = "\t<url>\n";
        $output .= "\t\t<loc>" . $this->encode_url_rfc3986($url['loc']) . "</loc>\n";
        $output .= empty($date) ? '' : "\t\t<lastmod>" . htmlspecialchars($date) . "</lastmod>\n";
        $output .= "\t</url>\n";

        return apply_filters('wpforo_sitemap_url', $output, $url);
    }

    public function members_sitemap($paged){
        $paged = absint($paged);
        $sitemap = '';
        if( $paged && $profiles = $this->get_public_profiles($paged) ){
            foreach ( $profiles as $profile ){
                $profile['loc'] = wpforo_member($profile['userid'], 'profile_url');
                $sitemap .= $this->sitemap_url($profile);
            }
        }

        return $sitemap;
    }

    public function forums_sitemap(){
        $sitemap = '';
        if( $forums = $this->get_public_forums() ){
            foreach ( $forums as $forum ){
                $forum['loc'] = wpforo_forum($forum['forumid'], 'url');
                $sitemap .= $this->sitemap_url($forum);
            }
        }

        return $sitemap;
    }

    public function topics_sitemap($paged){
        $paged = absint($paged);
        $sitemap = '';
        if( $paged && $topics = $this->get_public_topics($paged) ){
            foreach ( $topics as $topic ){
                $topic['loc'] = wpforo_topic($topic['topicid'], 'url');
                $sitemap .= $this->sitemap_url($topic);
            }
        }

        return $sitemap;
    }

    private function encode_url_rfc3986($url)
    {

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $path = wp_parse_url($url, PHP_URL_PATH);

        if (!empty($path) && '/' !== $path) {
            $encoded_path = explode('/', $path);

            $encoded_path = array_map('rawurldecode', $encoded_path);
            $encoded_path = array_map('rawurlencode', $encoded_path);
            $encoded_path = implode('/', $encoded_path);
            $encoded_path = str_replace('%7E', '~', $encoded_path); // PHP <5.3.

            $url = str_replace($path, $encoded_path, $url);
        }

        $query = wp_parse_url($url, PHP_URL_QUERY);

        if (!empty($query)) {

            parse_str($query, $parsed_query);

            if (defined('PHP_QUERY_RFC3986')) { // PHP 5.4+.
                $parsed_query = http_build_query($parsed_query, null, '&amp;', PHP_QUERY_RFC3986);
            } else {
                $parsed_query = http_build_query($parsed_query, null, '&amp;');
                $parsed_query = str_replace('+', '%20', $parsed_query);
                $parsed_query = str_replace('%7E', '~', $parsed_query);
            }

            $url = str_replace($query, $parsed_query, $url);
        }

        return $url;
    }

    private function get_public_profiles($paged = 0)
    {
        $items_per_page = $this->options['sitemap_items_per_page'];
        $groupids = WPF()->usergroup->get_visible_usergroup_ids();
        $sql = "SELECT `userid`, FROM_UNIXTIME( IFNULL(`online_time`, UNIX_TIMESTAMP()) ) AS lastmod
            FROM " . WPF()->tables->profiles . " 
            WHERE `groupid` IN(" . implode(',', $groupids) . ")
            OR `secondary_groups` REGEXP '(,|^)(" . implode('|', $groupids) . ")(,|$)'
            ORDER BY `userid` ASC";

        if ($paged) $sql .= " LIMIT " . (--$paged * $items_per_page) . ", $items_per_page";
        return (array)WPF()->db->get_results($sql, ARRAY_A);
    }

    private function get_public_forums()
    {
        $forumids = $this->get_public_forumids();
        $sql = "SELECT `forumid`, 
          CASE 
            WHEN `last_post_date` = '0000-00-00 00:00:00' 
            THEN NOW() 
            ELSE `last_post_date` 
          END AS lastmod 
          FROM " . WPF()->tables->forums . " 
          WHERE `forumid` IN(" . implode(',', $forumids) . ") 
          ORDER BY `parentid` ASC, `title` ASC";
        return (array)WPF()->db->get_results($sql, ARRAY_A);
    }

    private function get_public_forumids()
    {
        $forumids = array();
        $sql = "SELECT `forumid` FROM " . WPF()->tables->forums . " ORDER BY `parentid` ASC, `title` ASC";
        if ($_forumids = WPF()->db->get_col($sql)) {
            foreach ($_forumids as $_forumid) {
                if (WPF()->perm->forum_can('vf', $_forumid)) {
                    $forumids[] = $_forumid;
                }
            }
        }

        return $forumids;
    }

    private function get_public_topics($paged = 0)
    {
        $items_per_page = $this->options['sitemap_items_per_page'];
        $forumids = $this->get_public_forumids();
        $sql = "SELECT `topicid`, `modified` as lastmod 
            FROM " . WPF()->tables->topics . " 
            WHERE  `private` = 0 
            AND `status` = 0 
            AND `forumid` IN(" . implode(',', $forumids) . ") 
            ORDER BY `topicid` ASC";
        if ($paged) $sql .= " LIMIT " . (--$paged * $items_per_page) . ", $items_per_page";
        return (array)WPF()->db->get_results($sql, ARRAY_A);
    }

    private function get_public_profiles_count(){
        $groupids = WPF()->usergroup->get_visible_usergroup_ids();
        $sql = "SELECT COUNT(*)
            FROM " . WPF()->tables->profiles . " 
            WHERE `groupid` IN(" . implode(',', $groupids) . ")
            OR `secondary_groups` REGEXP '(,|^)(" . implode('|', $groupids) . ")(,|$)'";
        return (int)WPF()->db->get_var($sql);
    }

    private function get_public_topics_count(){
        $forumids = $this->get_public_forumids();
        $sql = "SELECT COUNT(*) 
            FROM " . WPF()->tables->topics . " 
            WHERE  `private` = 0 
            AND `status` = 0 
            AND `forumid` IN(" . implode(',', $forumids) . ")";
        return (int)WPF()->db->get_var($sql);
    }

    public function get_root_map() {
        $links = array();
        $items_per_page = $this->options['sitemap_items_per_page'];

        if( $this->options['topics_sitemap'] ){
            if( $topics_count = $this->get_public_topics_count() ){
                $pages_count = ceil($topics_count/$items_per_page);
                for( $i = 1; $i <= $pages_count; $i++ ){
                    $links[] = trim( wpforo_home_url('topic-sitemap' . $i . '.xml'), '/' );
                }
            }
        }

        if( $this->options['members_sitemap'] ){
            if( $profiles_count = $this->get_public_profiles_count() ){
                $pages_count = ceil($profiles_count/$items_per_page);
                for( $i = 1; $i <= $pages_count; $i++ ){
                    $links[] = trim( wpforo_home_url('profile-sitemap' . $i . '.xml'), '/' );
                }
            }
        }

        $links[] = trim( wpforo_home_url('forum-sitemap.xml'), '/' );

        // make sitemap index
        $xml = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ( $links as $link ) {
            $link = htmlspecialchars( $link );

            $xml  .= "\t<sitemap>\n";
            $xml .= "\t\t<loc>" . $link . "</loc>\n";
            $xml .= "\t</sitemap>\n";
        }

        $xml .= apply_filters( 'wpforo_sitemap_index', '' );
        $xml .= '</sitemapindex>';

        return $xml;
    }

    public function get_sitemap( $type, $paged ) {
        $transient_key = 'wpforo_seo_sitemap_' . $type . $paged;

        if( !$xml = get_transient($transient_key) ){
            if( $type ){
	            $urls = '';
                switch ($type){
                    case 'topic':
	                    if( $this->options['topics_sitemap'] ) $urls = $this->topics_sitemap($paged);
                        break;
                    case 'forum':
	                    if( $this->options['forums_sitemap'] ) $urls = $this->forums_sitemap();
                        break;
                    case 'profile':
	                    if( $this->options['members_sitemap'] ) $urls = $this->members_sitemap($paged);
                        break;
                }

                if($urls){
                    $urlset = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" '
                        . 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd '
                        . 'http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" '
                        . 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
                    $xml = apply_filters( "wpforo_sitemap_{$type}_urlset", $urlset );
                    $xml .= $urls;
                    $xml .= '</urlset>';
                }
            }else{
                $xml = $this->get_root_map();
            }

            set_transient($transient_key, $xml, DAY_IN_SECONDS);
        }

        return (string)$xml;
    }

    public function get_output( $sitemap ) {
        $output = '<?xml version="1.0" encoding="UTF-8"?>';

        if ( $this->stylesheet ) {
            $output .= apply_filters( 'wpforo_stylesheet_url', $this->stylesheet ) . "\n";
        }

        $output .= $sitemap;
        $output .= "\n<!-- XML Sitemap generated by wpForo SEO -->";

        return $output;
    }

    public function output( $sitemap ) {

        if ( ! headers_sent() ) {
            header( $this->http_protocol . ' 200 OK', true, 200 );
            // Prevent the search engines from indexing the XML Sitemap.
            header( 'X-Robots-Tag: noindex, follow', true );
            header( 'Content-Type: text/xml; charset=UTF-8' );
        }

        echo $this->get_output($sitemap);
    }

    public function sitemap_close() {
        remove_all_actions( 'wp_footer' );
        die();
    }

    public function redirect($current_object, $wpf_url_parse){
        $pattern = '#^[\r\n\t\s\0]*(?<type>\w*)-?sitemap(?:(?<paged>\d*)|_index)\.xml[\r\n\t\s\0]*$#iu';
        if( !empty($wpf_url_parse[0]) && preg_match($pattern, basename($wpf_url_parse[0]), $match) ){
            $current_object['template'] = 'sitemap';

            $query = array('type' => '', 'paged' => 1);
            $query = wpforo_parse_args($match, $query);
            $type = trim($query['type']);
            $paged = absint($query['paged']);
            if(!$paged) $paged = 1;

            if( $sitemap = $this->get_sitemap($type, $paged) ){
                $this->output($sitemap);
                $this->sitemap_close();
            }else{
                $current_object['is_404'] = true;
            }
        }

        return $current_object;
    }

    public function hit_sitemap_index() {
        wp_remote_get( trim( wpforo_home_url('sitemap_index.xml'), '/' ) );
    }

    public function ping_search_engines( $url = '' ) {
        if( !$this->options['allow_ping'] || !$this->get_public_forumids() )  return;
        if(!$url) $url = urlencode( trim( wpforo_home_url('sitemap_index.xml'), '/' ) );
        wp_remote_get( 'http://www.google.com/webmasters/tools/ping?sitemap=' . $url, array( 'blocking' => false ) );
        wp_remote_get( 'http://www.bing.com/ping?sitemap=' . $url, array( 'blocking' => false ) );
    }

    public function clear_cache( $type = '', $paged = 1 ) {
        if( $type == '' && $paged = 1 ){
            $like = 'wpforo_seo_sitemap_%';
            $wheres   = array();
            $wheres[] = sprintf( "option_name LIKE '%s'", addcslashes( '_transient_' . $like, '_' ) );
            $wheres[] = sprintf( "option_name LIKE '%s'", addcslashes( '_transient_timeout_' . $like, '_' ) );

            // Delete transients.
            $sql = sprintf( 'DELETE FROM %1$s WHERE %2$s', WPF()->db->options, implode( ' OR ', $wheres ) );
            WPF()->db->query($sql);
        }else{
            $transient_key = 'wpforo_seo_sitemap_' . $type . $paged;
            delete_transient($transient_key);
        }

        switch ($type){
            case 'profile':
                if( !$this->options['members_sitemap'] ) return;
            break;
            case 'forum':
                if( !$this->options['forums_sitemap'] ) return;
            break;
            case 'topic':
                if( !$this->options['topics_sitemap'] ) return;
            break;
            case '':
                break;
            default:
                return;
        }

        wp_schedule_single_event( ( time() + 300 ), 'wpforo_hit_sitemap_index' );

        if(!$this->options['allow_ping']) return;

        if ( $this->options['ping_immediately'] ) {
            $this->ping_search_engines();
        }elseif ( !wp_next_scheduled('wpforo_ping_search_engines') ) {
            wp_schedule_single_event( ( time() + 300 ), 'wpforo_ping_search_engines' );
        }
    }
}