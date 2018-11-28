<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class wpForoActivity
{
    private $default;
    public $options;
    public $activity;
    private $actions;

    public function __construct()
    {
        add_action( 'init', array($this, 'init'), 11 );
    }

    public function init()
    {
        $this->init_defaults();
        $this->init_options();
        $this->activity = $this->default->activity;
        $this->init_hooks();
        $this->init_actions();
    }

    private function init_actions(){
        $this->actions = array(
            'edit_topic' => array(
                'title' => wpforo_phrase('Edit Topic', false),
                'description' => wpforo_phrase('This topic was modified %s by %s', false),
                'before' => '<div class="wpf-post-edited"><i class="far fa-edit"></i>',
                'after' => '</div>',
            ),
            'edit_post' => array(
                'title' => wpforo_phrase('Edit Post', false),
                'description' => wpforo_phrase('This post was modified %s by %s', false),
                'before' => '<div class="wpf-post-edited"><i class="far fa-edit"></i>',
                'after' => '</div>',
            )
        );
    }

    private function init_defaults()
    {
        $this->default = new stdClass();
        $this->default->options = array(
            'edit_topic' => 1,
            'edit_post' => 1,
            'edit_log_display_limit' => 0
        );
        $this->default->activity = array(
            'id' => 0,
            'type' => '',
            'itemid' => 0,
            'itemtype' => '',
            'itemid_second' => 0,
            'userid' => 0,
            'name' => '',
            'email' => '',
            'date' => 0,
            'content' => '',
            'permalink' => ''
        );
        $this->default->activity_format = array(
            'id' => '%d',
            'type' => '%s',
            'itemid' => '%d',
            'itemtype' => '%s',
            'itemid_second' => '%d',
            'userid' => '%d',
            'name' => '%s',
            'email' => '%s',
            'date' => '%d',
            'content' => '%s',
            'permalink' => '%s'
        );
        $this->default->sql_select_args = array(
            'include' => array(),
            'exclude' => array(),
            'userids_include' => array(),
            'userids_exclude' => array(),
            'types_include' => array(),
            'types_exclude' => array(),
            'itemids_include' => array(),
            'itemids_exclude' => array(),
            'itemtypes_include' => array(),
            'itemtypes_exclude' => array(),
            'emails_include' => array(),
            'emails_exclude' => array(),
            'orderby' => 'id',
            'order' => 'ASC',
            'offset' => NULL,
            'row_count' => NULL
        );
    }

    private function init_options()
    {
        $this->options = get_wpf_option('wpforo_activity_options', $this->default->options);
        //Some options are located in Topic & Posts setting page
        foreach( $this->options as $key => $value ){
            if( wpfkey( WPF()->post->options, $key ) ) $this->options[$key] = WPF()->post->options[$key];
        }
    }

    private function init_hooks()
    {
        if ( $this->options['edit_topic'] ) add_action('wpforo_after_edit_topic', array($this, 'action_edit_topic'));
        if ( $this->options['edit_post'] ) add_action('wpforo_after_edit_post', array($this, 'action_edit_post'));
    }

    private function filter_built_html_rows($rows){
        $_rows = array();
        foreach ($rows as $row_key => $row){
            $in_array = false;
            if($_rows){
                foreach ($_rows as $_row_key => $_row){
                    if( in_array($row, $_row) ){
                        $in_array = true;
                        $match_key = $_row_key;
                        break;
                    }
                }
            }
            if( $in_array && isset($match_key) ){
                $_rows[$match_key]['times']++;
            }else{
                $_rows[$row_key]['html'] = $row;
                $_rows[$row_key]['times'] = 1;
            }
        }

        $rows = array();
        foreach ( $_rows as $_row ){
            $times = '';
            if( $_row['times'] > 1 ){
               $times = ' ' . sprintf(
                   wpforo_phrase('%d times', false),
                   $_row['times']
               );
            }

            $rows[] = sprintf($_row['html'], $times);
        }

        $limit = $this->options['edit_log_display_limit'];
        if( $limit ) $rows = array_slice($rows, (-1 * $limit), $limit);

        return $rows;
    }

    private function parse_activity($data){
        return array_merge($this->default->activity, $data);
    }

    private function parse_args($args)
    {
        $args = wpforo_parse_args($args, $this->default->sql_select_args);

        $args['include'] = wpforo_parse_args($args['include']);
        $args['exclude'] = wpforo_parse_args($args['exclude']);

        $args['userids_include'] = wpforo_parse_args($args['userids_include']);
        $args['userids_exclude'] = wpforo_parse_args($args['userids_exclude']);

        $args['types_include'] = wpforo_parse_args($args['types_include']);
        $args['types_exclude'] = wpforo_parse_args($args['types_exclude']);

        $args['itemids_include'] = wpforo_parse_args($args['itemids_include']);
        $args['itemids_exclude'] = wpforo_parse_args($args['itemids_exclude']);

        $args['itemtypes_include'] = wpforo_parse_args($args['itemtypes_include']);
        $args['itemtypes_exclude'] = wpforo_parse_args($args['itemtypes_exclude']);

        $args['emails_include'] = wpforo_parse_args($args['emails_include']);
        $args['emails_exclude'] = wpforo_parse_args($args['emails_exclude']);

        return $args;
    }

    private function build_sql_select($args)
    {
        $args = $this->parse_args($args);

        $wheres = array();
        if (!empty($args['include'])) $wheres[] = "`id` IN(" . implode(',', array_map('wpforo_bigintval', $args['include'])) . ")";
        if (!empty($args['exclude'])) $wheres[] = "`id` NOT IN(" . implode(',', array_map('wpforo_bigintval', $args['exclude'])) . ")";

        if (!empty($args['userids_include'])) $wheres[] = "`userid` IN(" . implode(',', array_map('wpforo_bigintval', $args['userids_include'])) . ")";
        if (!empty($args['userids_exclude'])) $wheres[] = "`userid` NOT IN(" . implode(',', array_map('wpforo_bigintval', $args['userids_exclude'])) . ")";

        if (!empty($args['types_include'])) $wheres[] = "`type` IN('" . implode("','", array_map('trim', $args['types_include'])) . "')";
        if (!empty($args['types_exclude'])) $wheres[] = "`type` IN('" . implode("','", array_map('trim', $args['types_exclude'])) . "')";

        if (!empty($args['itemids_include'])) $wheres[] = "`itemid` IN(" . implode(',', array_map('wpforo_bigintval', $args['itemids_include'])) . ")";
        if (!empty($args['itemids_exclude'])) $wheres[] = "`itemid` NOT IN(" . implode(',', array_map('wpforo_bigintval', $args['itemids_exclude'])) . ")";

        if (!empty($args['itemtypes_include'])) $wheres[] = "`itemtype` IN('" . implode("','", array_map('trim', $args['itemtypes_include'])) . "')";
        if (!empty($args['itemtypes_exclude'])) $wheres[] = "`itemtype` IN('" . implode("','", array_map('trim', $args['itemtypes_exclude'])) . "')";

        if (!empty($args['emails_include'])) $wheres[] = "`email` IN('" . implode("','", array_map('trim', $args['emails_include'])) . "')";
        if (!empty($args['emails_exclude'])) $wheres[] = "`email` IN('" . implode("','", array_map('trim', $args['emails_exclude'])) . "')";

        $sql = "SELECT * FROM " . WPF()->tables->activity;
        if ($wheres) $sql .= " WHERE " . implode($wheres, " AND ");
        $sql .= " ORDER BY " . $args['orderby'] . " " . $args['order'];
        if ($args['row_count']) $sql .= " LIMIT " . wpforo_bigintval($args['offset']) . "," . wpforo_bigintval($args['row_count']);

        return $sql;
    }

    public function get_activity($args)
    {
        if (!$args) return false;
        return $this->parse_activity( WPF()->db->get_row($this->build_sql_select($args), ARRAY_A) );
    }

    public function get_activities($args)
    {
        if (!$args) return false;
        return array_map( array($this, 'parse_activity'), WPF()->db->get_results($this->build_sql_select($args), ARRAY_A) );
    }

    public function action_edit_topic($topic)
    {
        $data = array(
            'type' => 'edit_topic',
            'itemid' => $topic['topicid'],
            'itemtype' => 'topic',
            'userid' => WPF()->current_userid,
            'name' => WPF()->current_user_display_name,
            'email' => WPF()->current_user_email,
            'permalink' => wpforo_topic($topic['topicid'], 'url')
        );

        $this->add($data);
    }

    public function action_edit_post($post)
    {
        $data = array(
            'type' => 'edit_post',
            'itemid' => $post['postid'],
            'itemtype' => 'post',
            'userid' => WPF()->current_userid,
            'name' => WPF()->current_user_display_name,
            'email' => WPF()->current_user_email,
            'permalink' => wpforo_post($post['postid'], 'url')
        );

        $this->add($data);
    }

    private function add($data)
    {
        if (empty($data)) return false;
        $activity = array_merge($this->default->activity, $data);
        unset($activity['id']);

        if (!$activity['type'] || !$activity['itemid'] || !$activity['itemtype']) return false;
        if (!$activity['date']) $activity['date'] = current_time('timestamp', 1);

        if (WPF()->db->insert(
            WPF()->tables->activity,
            wpforo_array_ordered_intersect_key($activity, $this->default->activity_format),
            wpforo_array_ordered_intersect_key($this->default->activity_format, $activity)
        )) {
            return WPF()->db->insert_id;
        }

        return false;
    }

    private function edit($data, $where)
    {
        if (empty($data) || empty($where)) return false;
        if (is_numeric($where)) $where = array('id' => $where);
        $data = (array)$data;
        $where = (array)$where;

        if (false !== WPF()->db->update(
                WPF()->tables->activity,
                wpforo_array_ordered_intersect_key($data, $this->default->activity_format),
                wpforo_array_ordered_intersect_key($where, $this->default->activity_format),
                wpforo_array_ordered_intersect_key($this->default->activity_format, $data),
                wpforo_array_ordered_intersect_key($this->default->activity_format, $where)
            )) {
            return true;
        }

        return false;
    }

    private function delete($where)
    {
        if (empty($where)) return false;
        if (is_numeric($where)) $where = array('id' => $where);
        $where = (array)$where;

        if (false !== WPF()->db->delete(
                WPF()->tables->activity,
                wpforo_array_ordered_intersect_key($where, $this->default->activity_format),
                wpforo_array_ordered_intersect_key($this->default->activity_format, $where)
            )) {
            return true;
        }

        return false;
    }

    public function build($itemtype, $itemid, $type, $echo = false){
        $rows = array();
        $args = array(
            'itemtypes_include' => $itemtype,
            'itemids_include' => $itemid,
            'types_include' => $type
        );
        if( $activities = $this->get_activities($args) ){
            foreach ($activities as $activity){
                switch ($activity['type']){
                    case 'edit_topic':
                    case 'edit_post':
                        $rows[] = $this->_build_edit_topic_edit_post($activity);
                    break;
                }
            }
        }

        $rows = $this->filter_built_html_rows($rows);

        $html = ($rows ? implode('', $rows) : '');
        if(!$echo) return $html;
        echo $html;
    }

    private function _build_edit_topic_edit_post($activity){
        $html = '';
        $type = $activity['type'];
        $userid = $activity['userid'];
        $date = wpforo_date($activity['date'], 'ago', false) . '%s';

        if( $userid ){
            $profile_url = wpforo_member($userid, 'profile_url');
            $display_name = wpforo_member($userid, 'display_name');
            $user = sprintf( '<a href="%s">%s</a>', $profile_url, $display_name );
        } else {
            $user = ( $activity['name'] ) ? $activity['name'] : wpforo_phrase('Guest', false);
        }

        if( wpfval($this->actions, $type, 'before') ){
            $html = $this->actions[$type]['before'];
            $html = apply_filters('wpforo_activity_action_html_before', $html, $activity);
        }
        if( wpfval($this->actions, $type, 'description') ){
            $html .= sprintf( $this->actions[$activity['type']]['description'], $date, str_replace('%', '%%', $user) );
            $html = apply_filters('wpforo_activity_action_html', $html, $activity);
        }
        if( wpfval($this->actions, $type, 'after') ) {
            $html .= $this->actions[$type]['after'];
            $html = apply_filters('wpforo_activity_action_html_after', $html, $activity);
        }

        return $html;
    }
}