<?php

function wpforo_legal_checkbox_forum_privacy(){

    $only_forum = apply_filters( 'wpforo_legal_checkbox_forum_privacy_only_for_forum', true );
    $only_new_guests = apply_filters( 'wpforo_legal_checkbox_forum_privacy_only_for_new_guests', true );

    if( $only_forum && !is_wpforo_page() || is_user_logged_in() ) return false;
    $guest = WPF()->member->get_guest_cookies();
    if( $only_new_guests && wpfval($guest, 'email') ) return false;

    $set = WPF()->tools_legal;

    if( wpfval($set, 'checkbox_forum_privacy') && wpfval($set, 'forum_privacy_text') ){
        $forum_home = preg_replace('|\?.+$|', '', wpforo_home_url() );
        $str_privacy = wpforo_phrase( 'forum privacy policy', false, 'native' );
        $str_privacy_label = wpforo_phrase('I have read and agree to the %s.', false);
        $str_privacy_link = ' <span id="wpf-open-privacy" class="wpflink" title="' . esc_attr(wpforo_phrase('Click to open forum privacy policy below', false)) . '">' . $str_privacy . '</span>';
        $str_privacy = sprintf( $str_privacy_label, $str_privacy_link );
        $str_privacy_text = wpautop(stripslashes($set['forum_privacy_text']));
        $str_privacy_text = apply_filters('wpforo_legal_forum_privacy_text', $str_privacy_text);
        $url = parse_url( get_bloginfo('url') );
        $find = array('[forum-name]', '[forum-url]');
        $domain = (wpfval($url, 'host')) ? $url['host'] : $_SERVER['HTTP_HOST'];
        $replace = array( WPF()->general_options['title'] , $domain);
        $str_privacy_text = str_replace($find, $replace, $str_privacy_text);
        ?>
        <label class="wpforo-legal-checkbox wpflegal-privacy">
            <input id="wpflegal_privacy" name="legal[gdpr]" value="1" required="required" type="checkbox"> &nbsp;
            <span><?php echo wp_unslash($str_privacy); ?></span>
        </label>
        <div class="wpforo-legal-privacy wpforo-text" style="display: none;">
            <?php echo $str_privacy_text; ?>
            <div class="wpflegal-privacy-buttons">
                <div id="wpflegal-privacy-yes" class="wpflegal-privacy-button"><?php wpforo_phrase('I agree'); ?></div>
                <a href="<?php echo esc_url($forum_home); ?>" id="wpflegal-privacy-not" class="wpflegal-privacy-button"><?php wpforo_phrase('I do not agree. Take me away from here.'); ?></a>
            </div>
        </div>
        <?php
    }
}

add_action( 'register_form', 'wpforo_legal_checkbox_forum_privacy', 20 );
add_action( 'wpforo_editor_post_submit_before', 'wpforo_legal_checkbox_forum_privacy', 20 );
add_action( 'wpforo_editor_topic_submit_before', 'wpforo_legal_checkbox_forum_privacy', 20 );

function wpforo_legal_checkbox_forum_rules(){

    $only_forum = apply_filters( 'wpforo_legal_checkbox_rules_only_for_forum', true );
    $only_new_guests = apply_filters( 'wpforo_legal_checkbox_rules_only_for_new_guests', true );
    if( $only_forum && !is_wpforo_page() || is_user_logged_in() ) return false;
    $guest = WPF()->member->get_guest_cookies();
    if( $only_new_guests && wpfval($guest, 'email') ) return false;

    $set = WPF()->tools_legal;

    if( wpfval($set, 'rules_checkbox') && wpfval($set, 'rules_text') ){
        $forum_home = preg_replace('|\?.+$|', '', wpforo_home_url() );
        $str_rules = wpforo_phrase( 'forum rules', false, 'native' );
        $str_rules_text = wpforo_phrase('I have read and agree to abide by the %s.', false);
        $str_rules_link = ' <span id="wpf-open-rules" class="wpflink" title="' . esc_attr(wpforo_phrase('Click to open forum rules below', false)) . '">' . $str_rules . '</span>';
        $str_rules = sprintf( $str_rules_text, $str_rules_link );
        $str_rules_text = apply_filters('wpforo_legal_forum_rules_text', $set['rules_text']);
        ?>
        <label class="wpforo-legal-checkbox wpflegal-rules">
            <input id="wpflegal_rules" name="legal[rules]" value="1" required="required" type="checkbox"> &nbsp;
            <span><?php echo $str_rules; ?></span>
        </label>
        <div class="wpforo-legal-rules wpforo-text" style="display: none;">
            <?php echo wp_unslash($str_rules_text); ?>
            <div class="wpflegal-rules-buttons">
                <div id="wpflegal-rules-yes" class="wpflegal-rules-button"><?php wpforo_phrase('I agree to these rules'); ?></div>
                <a href="<?php echo esc_url($forum_home); ?>" id="wpflegal-rules-not" class="wpflegal-rules-button"><?php wpforo_phrase('I do not agree to these rules. Take me away from here.'); ?></a>
            </div>
        </div>
        <?php
    }
}

add_action( 'register_form', 'wpforo_legal_checkbox_forum_rules', 20 );
add_action( 'wpforo_editor_post_submit_before', 'wpforo_legal_checkbox_forum_rules', 20 );
add_action( 'wpforo_editor_topic_submit_before', 'wpforo_legal_checkbox_forum_rules', 20 );

function wpforo_legal_checkbox_terms_privacy(){

    $only_forum = apply_filters( 'wpforo_legal_checkbox_only_for_forum', true );
    $only_new_guests = apply_filters( 'wpforo_legal_checkbox_only_for_new_guests', true );
    if( $only_forum && !is_wpforo_page() || is_user_logged_in() ) return false;
    $guest = WPF()->member->get_guest_cookies();
    if( $only_new_guests && wpfval($guest, 'email') ) return false;

    $set = WPF()->tools_legal;

    $str_and = '';
    $str_terms = '';
    $str_privacy = '';
    $str_site_name = get_bloginfo( 'name' );
    $str_site_name = ( $str_site_name ) ? $str_site_name : wpforo_phrase('the website', false);
    $str_terms_privacy = wpforo_phrase('I have read and agree to the', false);
    $str_if_no_pages =  sprintf( wpforo_phrase('I have read and agree to %s privacy policy.', false), $str_site_name);

    if(wpfval($set, 'page_terms')){
        $term_url = $set['page_terms'];
        $term_pageid = url_to_postid( $term_url );
        $str_terms = wpforo_phrase('Terms', false);
        $term_title = ($term_pageid) ? get_the_title( $term_pageid ) : $str_terms;
        $str_terms = ' <a href="' . esc_url( $term_url ) . '" title="' . esc_attr( $term_title ) . '">' . $str_terms . ' </a>';
    }

    if(wpfval($set, 'page_privacy')){
        $privacy_url = $set['page_privacy'];
        $privacy_pageid = url_to_postid( $privacy_url );
        $str_privacy = wpforo_phrase('Privacy Policy', false);
        $privacy_title = ($privacy_pageid) ? get_the_title( $privacy_pageid ) : $str_privacy;
        $str_privacy = ' <a href="' . esc_url( $privacy_url ) . '" title="' . esc_attr( $privacy_title ) . '">' . $str_privacy . ' </a>';
    }

    if( $str_terms && $str_privacy ){
        $str_and = wpforo_phrase('and', false, 'lower');
    }

    if( $str_terms || $str_privacy || (wpfval($set, 'checkbox_forum_privacy') && wpfval($set, 'forum_privacy_text')) ){
        $terms_privacy = $str_terms_privacy . ' ' . $str_terms . ' ' . $str_and . ' ' . $str_privacy;
        $terms_privacy = apply_filters('wpforo_legal_checkbox_label_terms_and_privacy', $terms_privacy);
        if( ( $str_terms || $str_privacy ) && $terms_privacy ){
            ?>
            <label class="wpforo-legal-checkbox wpflegal-terms-privacy">
                <input name="legal[terms_privacy]" value="1" required="required" type="checkbox"> &nbsp;
                <span><?php echo $terms_privacy; ?></span>
            </label>
            <?php
        }
    }
    elseif( wpfval($set, 'checkbox_terms_privacy') ){
        ?>
        <label class="wpforo-legal-checkbox wpflegal-terms-privacy">
            <input name="legal[terms_privacy]" value="1" required="required" type="checkbox"> &nbsp;
            <span><?php echo $str_if_no_pages; ?></span>
        </label>
        <?php
    }
}

add_action( 'register_form', 'wpforo_legal_checkbox_terms_privacy', 20 );
add_action( 'wpforo_editor_post_submit_before', 'wpforo_legal_checkbox_terms_privacy', 20 );
add_action( 'wpforo_editor_topic_submit_before', 'wpforo_legal_checkbox_terms_privacy', 20 );

function wpforo_legal_checkbox_email_password(){

    $only_forum = apply_filters( 'wpforo_legal_checkbox_email_password', true );
    if( $only_forum && !is_wpforo_page() ) return false;
    $set = WPF()->tools_legal;

    if( wpforo_feature('user-register-email-confirm') && wpfval($set, 'checkbox_email_password') ){
        $str_email_password = wpforo_phrase('I agree to receive an email confirmation with a link to set a password.', false);
        $str_email_password = apply_filters('wpforo_legal_checkbox_confirm_email_password', $str_email_password);
        ?>
        <label class="wpforo-legal-checkbox wpflegal-email">
            <input name="legal[email]" value="1" required="required" type="checkbox"> &nbsp;
            <span><?php echo $str_email_password; ?></span>
        </label>
        <?php
    }
}

add_action( 'register_form', 'wpforo_legal_checkbox_email_password', 20 );

function wpforo_page_privacy_policy( $template ){
    if( $template !== 'privacy' ) return false;
    $set = WPF()->tools_legal;
    if( wpfval($set, 'checkbox_forum_privacy') ){
        $str_privacy_text = wpautop(stripslashes($set['forum_privacy_text']));
        $str_privacy_text = apply_filters('wpforo_legal_forum_privacy_text', $str_privacy_text);
        $url = parse_url( get_bloginfo('url') );
        $find = array('[forum-name]', '[forum-url]');
        $domain = (wpfval($url, 'host')) ? $url['host'] : $_SERVER['HTTP_HOST'];
        $replace = array( WPF()->general_options['title'] , $domain);
        $str_privacy_text = str_replace($find, $replace, $str_privacy_text);
        $str_privacy_text = apply_filters('wpforo_legal_forum_privacy_output', $str_privacy_text);
        ?>
        <div class="wpforo-page wpforo-page-privacy wpforo-text">
            <?php echo $str_privacy_text; ?>
        </div>
        <?php
    }
}

add_action( 'wpforo_page', 'wpforo_page_privacy_policy', 20 );

function wpforo_page_forum_rules( $template ){
    if( $template !== 'rules' ) return false;
    $set = WPF()->tools_legal;
    if( wpfval($set, 'rules_checkbox') ){
        $str_rules_text = wpautop(stripslashes($set['rules_text']));
        $str_rules_text = apply_filters('wpforo_legal_forum_rules_text', $str_rules_text);
        ?>
        <div class="wpforo-page wpforo-page-rules wpforo-text">
            <?php echo $str_rules_text; ?>
        </div>
        <?php
    }
}

add_action( 'wpforo_page', 'wpforo_page_forum_rules', 11 );

function wpforo_contact_forum_admin(){
    $set = WPF()->tools_legal;
    if( wpfval($set, 'contact_page_url') ){
        $url = $set['contact_page_url'];
        $html = '<a href="'. esc_url($url).'" title="'. wpforo_phrase('Contact Us', false) . '" class="wpf-contact-admin">' . wpforo_phrase('Contact the forum administrator', false) . '</a>';
        echo apply_filters('wpforo_legal_contact_forum_admin_button', $html);
    }
}

add_action( 'wpforo_profile_account_bottom', 'wpforo_contact_forum_admin', 10 );