<?php
	// Exit if accessed directly
	if( !defined( 'ABSPATH' ) ) exit;

class wpForoForm{

    public $default;
    public $user_dir;
    public $field_dir;

	public function __construct(){
		$this->init_defaults();
	}
	
	private function init_defaults(){
        $this->user_dir = '/wpforo/users/';
	    $this->field_dir = '/wpforo/users/fields/';
	    $this->default = array(
            'label' => '',
            'title' => '',
            'name' => '',
            'value' => '',
            'values' => '',
            'type' => 'text',
            'placeholder' => '',
            'description' => '',
            'id' => '',
            'class' => '',
            'attributes' => '',
            'isDefault' => 1,
            'isWrapItem' => '',
            'isLabelFirst' => '',
            'isDisabled' => false,
            'isEditable' => 1,
            'isRequired' => 1,
            'isMultiChoice' => 0,
            'isConfirmPassword' => 1,
            'allowedGroupIds' => array(),
            'fileExtensions' => '',
            'minLength' => 0,
            'maxLength' => 0,
            'faIcon' => '',
            'html' => '',
            'varname' => '',
            'template' => '',
            'canBeInactive' => 1,
            'canEdit' => array('1'),
            'canView' => array('1', '2', '3', '5'),
            'can' => ''
        );
    }

    /**
     * The varname of custom fields in forms:
     * $varname = 'member'    user account
     * $varname = 'wpfreg'    register forms
     * $varname = ''          member search
     * $varname = 'data'      custom fields (by default)
     *
     * @return  string
     */
    public function varname(){
        $template = ( isset(WPF()->data['template']) ) ? WPF()->data['template'] : WPF()->current_object['template'];
        if( $template == 'members' ){
            $varname = WPF()->data['varname'];
        } else {
            $varname = ( wpfval( WPF()->data, 'varname') ) ? WPF()->data['varname'] : 'data';
            $varname = apply_filters( 'wpforo_user_custom_field_varname', $varname);
            $varname = ( $varname ) ? $varname : 'data';
        }
        return $varname;
    }

    /**
     * Form builder
     *
     * @param   array $fields       associative multi-level array
     *                        for form layout and field building
     * @return  string        form HTML
     */
	public function build( $fields ){
        if(empty($fields)) return ''; $html = '';
        foreach ($fields as $row_key => $row){
            $row_class = "row-$row_key " . apply_filters('wpforo_row_classes', '', $row_key);
            $html .= '<div class="wpf-tr ' . esc_attr( $row_class ) . '">';
            foreach ( $row as $col_key => $col ){
                $col_class = "row_$row_key-col_$col_key " . apply_filters('wpforo_col_classes', '', $row_key, $col_key);
                $html .= '<div class="wpf-td wpfw-' . count($row) . ' ' . esc_attr( $col_class ) . '">';
                foreach ( $col as $field ){
                    if( !empty($field) ) $html .= $this->build_field( $field );
                }
                $html .= '</div>';
            }
            $html .= '<div class="wpf-cl"></div></div>';
        }
        return $html;
    }

    /**
     * Builds field with input HTML and wrapper divs
     *
     * @param   array $args       field arguments
     * @return  string            field HTML
     */
    public function build_field( $args ){

        $html = '';
        if( !is_array($args) || empty($args) ) return '';
        $f = wpforo_parse_args( $args, $this->default );
        $f = $this->prepare_args( $f );

        //Get field input tag
        $field_html = $this->field( $f );

        //Wrapping field input
        if( $f['template'] == 'register' ){
            if( $this->can_add( $f ) ){
                $html = $this->field_wrap_register( $field_html, $f );
            }
        }
        elseif( $f['template'] == 'account' ){
            if( $this->can_edit( $f ) ){
                $html = $this->field_wrap_account( $field_html, $f );
            }
        }
        elseif( $f['template'] == 'profile' ){
            if( $this->can_view( $f ) ){
                $f = $this->esc_field( $f );
                $html = $this->field_wrap_profile( $field_html, $f );
            }
        }
        elseif( $f['template'] == 'members' ){
            $html = $this->field_wrap_members( $field_html, $f );
        }
        else{
            $html = $this->field_wrap_default( $field_html, $f );
        }
        return $html;
    }

    /**
     * Field input HTML builder
     *
     * @param   array $f      field arguments
     * @return  string  field input HTML
     */
    public function field( $f ){
        $f = $this->prepare_field_args( $f );

	    $f = apply_filters('wpforo_form_field', $f);

        switch( $f['type'] ){
            case 'url': $field_html = $this->field_url($f); break;
            case 'file': $field_html = $this->field_file($f); break;
            case 'html': $field_html = $this->field_html($f); break;
            case 'radio': $field_html = $this->field_radio($f); break;
            case 'select': $field_html = $this->field_select($f); break;
            case 'avatar': $field_html = $this->field_avatar($f); break;
            case 'textarea': $field_html = $this->field_textarea($f); break;
            case 'password': $field_html = $this->field_password($f); break;
            case 'checkbox': $field_html = $this->field_checkbox($f); break;
            case 'usergroup': $field_html = $this->field_usergroup($f); break;
            case 'user_nicename': $field_html = $this->field_nicename($f); break;
            default: $field_html = $this->field_text($f);
        }

        return $field_html;
    }

    /**
     * Wraps input HTML for Registration form
     *
     * @param   string $field_html     input HTML
     * @param   array  $f              field arguments
     * @return  string  wrapped field input HTML
     */
    public function field_wrap_register( $field_html, $f ){
        $html = '<div class="wpf-field wpf-field-type-' . esc_attr($f['type']) . ' wpf-field-name-' . esc_attr($f['field_class']) . ' ' . esc_attr($f['required_class']) . '" title="' .  esc_attr($f['title']) . '">';
        if( $f['type'] != 'html' ){
            if ( $f['label'] || $f['description'] ) {
                $html .= '<div class="wpf-label-wrap">';
                if( $f['label'] ){ $html .= '<p class="wpf-label wpfcl-1">' . $f['label'] . $f['required_indicator'] . '</p>'; }
                if( $f['description'] ){ $html .= '<div class="wpf-desc wpfcl-2">' .  $f['description'] . '</div>'; }
                $html .= '</div>';
            }
            $html .= '<div class="wpf-field-wrap">';
            if( $f['faIcon'] ){ $html .= '<i class="' .  esc_attr($f['faIcon']) . ' wpf-field-icon"></i>'; }
            $html .= $field_html;
            $html .= '</div>';
        }
        else{
            $html .= $field_html;
        }
        $html .= '<div class="wpf-field-cl"></div></div>';
        return $html;
    }

    /**
     * Wraps input HTML for Account form
     *
     * @param   string $field_html     input HTML
     * @param   array  $f              field arguments
     * @return  string  wrapped field input HTML
     */
    public function field_wrap_account( $field_html, $f ){
	    $html = '<div class="wpf-field wpf-field-type-' . esc_attr($f['type']) . ' wpf-field-name-' . esc_attr($f['field_class'])  . ' ' . esc_attr($f['required_class']) .  '" title="' .  esc_attr($f['title']) . '">';
        if( $f['type'] == 'html' ){
            $html .= $field_html;
        }
        elseif($f['name'] == 'user_login'){
            $html .= '<div class="wpf-label-wrap">';
            $html .= '<p class="wpf-label wpfcl-1">' . stripslashes($f['label']) . '</p>';
            $html .= '</div>';
            $html .= '<div class="wpf-field-wrap">';
            $html .= '<span class="wpf-username">' . $f['value'] . '</span>';
            $html .= '</div>';
        }
        elseif( !$f['isEditable'] && !$this->can_moderate($f) && WPF()->current_user_groupid !== 1 ){
            $f = $this->esc_field( $f );
            $html .= '<div class="wpf-label-wrap">';
            $html .= '<p class="wpf-label wpfcl-1">' . stripslashes($f['label']) . '</p>';
            $html .= '</div>';
            $html .= '<div class="wpf-field-wrap">';
            $html .= '<span class="wpf-filed-value"><i class="' .  esc_attr($f['faIcon']) . '"></i> ' . $f['value'] . '</span>';
            $html .= '</div>';
        }
        else{
            if ( $f['label'] || $f['description'] ) {
                $html .= '<div class="wpf-label-wrap">';
                if ($f['label']){ $html .= '<p class="wpf-label wpfcl-1">' . stripslashes($f['label']) . $f['required_indicator'] . '</p>'; }
                if ($f['description']){ $html .= '<div class="wpf-desc wpfcl-2">' .  $f['description'] . '</div>'; }
                $html .= '</div>';
            }
            $html .= '<div class="wpf-field-wrap">';
            if($f['faIcon']){ $html .= '<i class="' .  esc_attr($f['faIcon']) . ' wpf-field-icon"></i>'; }
            $html .= $field_html;
            $html .= '</div>';
        }
        $html .= '<div class="wpf-field-cl"></div></div>';
        return $html;
    }

    /**
     * Wraps input HTML for Profile page
     *
     * @param   string $field_html     input HTML
     * @param   array  $f              field arguments
     * @return  string  wrapped field input HTML
     */
    public function field_wrap_profile( $field_html, $f ){

        if( $f['type'] != 'html' && ( !isset($f['value']) || (!is_numeric($f['value']) && empty($f['value']))) ){
            return false;
        }

        $html = '<div class="wpf-field wpf-field-type-' . esc_attr($f['type']) . ' wpf-field-name-' . esc_attr($f['field_class'])  . ' ' . esc_attr($f['required_class']) .  '" title="' .  esc_attr($f['title']) . '">';
        if( $f['type'] != 'html' ){
            if( !$f['faIcon'] ) { $f['faIcon'] = 'fas fa-address-card'; }
            if( $f['label'] ) {
                $html .= '<div class="wpf-label-wrap">';
                if ($f['label']){
                    $html .= '<p class="wpf-label wpfcl-1"><i class="' .  esc_attr($f['faIcon']) . ' wpf-field-icon"></i> ' . $f['label'] . '</p>';
                }
                $html .= '</div>';
            }
            if( isset($f['value']) && !empty($f['value']) ){
                if( is_array($f['value']) ){
                    $html .= esc_html(implode( ', ', $f['value']));
                }
                else{
                    $f = $this->prepare_values( $f );
                    $html .= '<div class="wpf-field-wrap">';
                    $html .= $f['value'];
                    $html .= '</div>';
                }
            }
        }
        else{
            $html .= $field_html;
        }
        $html .= '<div class="wpf-field-cl"></div></div>';
        return $html;
    }

    /**
     * Wraps input HTML for Members Search form
     *
     * @param   string $field_html     input HTML
     * @param   array  $f              field arguments
     * @return  string  wrapped field input HTML
     */
    public function field_wrap_members( $field_html, $f ){
        $html = '<div class="wpf-field wpf-field-type-' . esc_attr($f['type']) . ' wpf-field-name-' . esc_attr($f['field_class'])  . ' ' . esc_attr($f['required_class']) .  '" title="' .  esc_attr($f['title']) . '">';
        if( $f['type'] == 'html' ){
            $html .= $field_html;
        }
        else{
            if ( $f['label'] || $f['description'] ) {
                $html .= '<div class="wpf-label-wrap">';
                if ($f['label']){ $html .= '<p class="wpf-label wpfcl-1">' .  stripslashes($f['label']) . $f['required_indicator'] . '</p>'; }
                if ($f['description']){ $html .= '<div class="wpf-desc wpfcl-2">' .  $f['description'] . '</div>'; }
                $html .= '</div>';
            }
            $html .= '<div class="wpf-field-wrap">';
            if($f['faIcon']){
                $html .= '<i class="' .  esc_attr($f['faIcon']) . ' wpf-field-icon"></i>';
            }
            $html .= $field_html;
            $html .= '</div>';
        }
        $html .= '<div class="wpf-field-cl"></div></div>';
        return $html;
    }

    /**
     * Default wrapper of input HTML
     *
     * @param   string $field_html     input HTML
     * @param   array  $f              field arguments
     * @return  string  wrapped field input HTML
     */
    public function field_wrap_default( $field_html, $f ){
        $html = '<div class="wpf-field wpf-field-type-' . esc_attr($f['type']) . ' wpf-field-name-' . esc_attr($f['field_class'])  . ' ' . esc_attr($f['required_class']) .  '" title="' .  esc_attr($f['title']) . '">';
        $html .= '<div class="wpf-field-wrap">' . $field_html . '<div class="wpf-field-cl"></div></div>';
        $html .= '<div class="wpf-field-cl"></div></div>';
        return $html;
    }

    /**
     * File - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_file( $f ){
        $field_html = '';
        $extensions = '';
        if( $f['fileExtensions'] ) {
            foreach( $f['fileExtensions'] as $key => $ext ){ if( strpos($ext, '.') === FALSE ) { $f['fileExtensions'][ $key ] = '.' . $ext; }}
            $f['fileExtensions'] = implode(', ', $f['fileExtensions']);
            if( $f['fileExtensions'] ) $extensions = ' accept="' . esc_attr($f['fileExtensions']) . '" ';
        }
        if( $f['value'] ){
            $f['isRequired'] = '';
            $file_name = basename( $f['value'] );
            $extension = pathinfo( $f['value'], PATHINFO_EXTENSION );
            $wp_upload_dir = wp_upload_dir();
            $f['value'] = $wp_upload_dir['baseurl'] . "/" . trim( $f['value'], '/' );

            if( wpfval(WPF()->current_object['user'], 'ID') ){
                $url = strtok( wpforo_get_request_uri(), '?');
                $delete = $url . '?foro_f=' . $f['name'] . '&foro_u=' . WPF()->current_object['user']['ID'];
                $delete_url = wp_nonce_url( wpforo_home_url( $delete ) , 'wpforo_delete_profile_field', 'foro_n' );
                $delete_html = ' &nbsp;|&nbsp; <a href="' . $delete_url . '" title="' . wpforo_phrase( 'Delete this file', false ) . '" onclick="return confirm(\'' . wpforo_phrase( 'Are you sure you want to delete this file?', false ) . '\')"><i class="fas fa-times" style="color:#e86666; font-size:14px; line-height:14px;"></i></a>';
            }

            if( $extension && wpforo_is_image( $extension ) ){
                $field_html .= '<div class="wpf-field-file-wrap" style="margin-bottom: 10px;">
                                    <img src="' . esc_url_raw( $f['value'] ) . '" alt="' . esc_attr($file_name) . '" title="' . esc_attr($file_name) . '" style="max-width:100px; max-height:100px"/>
                                    <div class="wpf-field-file-name">
                                        <i class="fas fa-paperclip"></i> 
                                        <a href="' . esc_url_raw( $f['value'] ) . '" title="' . esc_attr($file_name) . '" target="_blank">' . esc_attr($file_name) . '</a>' . $delete_html . '
                                    </div>
                                </div>';
            }
            else{
                $field_html .= '<div class="wpf-field-file-wrap" style="margin-bottom: 10px;">
                                    <div class="wpf-field-file-name">
                                        <i class="fas fa-paperclip"></i> 
                                        <a href="' . esc_url_raw( $f['value'] ) . '" title="' . esc_attr($file_name) . '" target="_blank">' . esc_attr($file_name) . '</a>' . $delete_html . '
                                    </div>
                                </div>';
            }
        }
        $field_html .= '<input type="file" name="' . esc_attr( $f['fieldName'] ) . '" value="" 
                                    id="' . esc_attr( $f['fieldId'] ) . '"  class="' . esc_attr( $f['fieldId'] ) . '" 
                                        ' . $f['isRequired'] . ' ' . $f['isDisabled'] . ' ' . $f['attributes'] . ' ' . $extensions . ' />';
        return $field_html;
    }

    /**
     * Textarea - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_textarea( $f ){
        $field_html = '<textarea name="' . esc_attr( $f['fieldName'] ) . '" 
                               id="' . esc_attr( $f['fieldId'] ) . '" 
                                  class="' . esc_attr( $f['fieldId'] ) . '" 
                                     placeholder="' . esc_attr($f['placeholder']) . '"
                                        ' . $f['isRequired'] . ' ' . $f['isDisabled'] . ' ' . $f['attributes'] . ' 
                                             ' . $f['minmax'] . '>' . esc_textarea( $f['value'] ) . '</textarea>';
        return $field_html;
    }

    /**
     * Password - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_password( $f ){
        $field_html = '';
        if( $f['template'] == 'account' ){
            $f['isRequired'] = 0;
            $f['label'] = wpforo_phrase('Old password', false); $f['description'] = '';
            $field_html .= '<input type="password" 
                                        name="' . esc_attr( $f['varname'] ) . '[old_pass]" 
                                            value="" id="' . esc_attr($f['fieldId']) . '-old" 
                                                class="' . esc_attr( $f['fieldId'] ) . '" 
                                                    placeholder="' . esc_attr( wpforo_phrase('Old password', false) ) . '"
                                                        ' . $f['isDisabled'] . ' ' . $f['attributes'] . '/>
                                                            <i class="fas fa-eye-slash wpf-show-password"></i>';
        }
        if( $f['isConfirmPassword'] ) { $p1 = '1'; $p2 = '2'; } else{ $p1 = ''; $p2 = ''; }
        if( !empty($f['varname']) ){ $f['fieldName'] = $f['varname'] . '[' . $f['name'] . $p1 . ']'; } else { $f['fieldName'] = $f['name'] . $p1; }
        if( $f['template'] == 'account' ) {
            $f['label'] = wpforo_phrase('New', false) . ' ' . $f['label'];
            $f['placeholder'] = wpforo_phrase('New', false) . ' ' . $f['placeholder'];
        }
        if( $f['template'] == 'account' || $f['template'] == 'register' ){
            if( $f['template'] == 'account' ) $field_html .= '<div style="position:relative"><i class="' .  esc_attr($f['faIcon']) . ' wpf-field-icon"></i>';
            $field_html .= '<input type="password" 
                                        name="' . esc_attr($f['fieldName']) . '" 
                                            value="" id="' . esc_attr($f['fieldId']) . '-new1" 
                                                class="' . esc_attr( $f['fieldId'] ) . '" 
                                                    placeholder="' . esc_attr($f['placeholder']) . '" 
                                                        ' . $f['isDisabled'] . ' ' . $f['attributes'] . ' ' . $f['minmax'] . '/>';
            if( $f['template'] == 'register' ){ $field_html .= '<i class="fas fa-eye-slash wpf-show-password"></i>'; }
            if( $f['template'] == 'account' ){ $field_html .= '</div>'; }
            if( $f['isConfirmPassword'] ){
                $f['label'] = wpforo_phrase('Confirm Password', false);
                $f['placeholder'] = wpforo_phrase('Confirm Password', false);
                $f['description'] = '';
                $f['fieldName'] = ( !empty($f['varname']) ? $f['varname'] . '[' . $f['name'] . $p2 . ']' : $f['name'] . $p2 );
                if( $f['template'] == 'account' || $f['template'] == 'register' ) $field_html .= '<div style="position:relative"><i class="' .  esc_attr($f['faIcon']) . ' wpf-field-icon"></i>';
                $field_html .= '<input type="password" 
                                           name="' . esc_attr($f['fieldName']) . '" 
                                                value="" id="' . esc_attr($f['fieldId']) . '-new2" 
                                                    class="' . esc_attr( $f['fieldId'] ) . '" 
                                                        placeholder="' . esc_attr($f['placeholder']) . '" 
                                                            ' . $f['isDisabled'] . ' ' . $f['attributes'] . ' ' . $f['minmax'] . '/>';
                if( $f['template'] == 'account' || $f['template'] == 'register' ){ $field_html .= '</div>'; }
            }
        }

        return $field_html;
    }

    /**
     * Checkbox - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_checkbox( $f ){
        $i = 0; $field_zero = true; $field_html = '';
        $f['value'] = $this->build_array_value( $f['value'] );
        if( !is_array($f['values']) ) $f['values'] = $this->build_array_using_string_rows( $f['values'] );
        if( !empty($f['values']) ){
            $item_field_name = $f['fieldName'] . '[]';
            $f['isRequired'] = ( count($f['values']) == 1 ) ? $f['isRequired'] : '';
            foreach( $f['values'] as $row ){
                $item = $this->build_item_value_lable($row);
                $field_zero = ( $field_zero === false || $item['value'] === 0 ) ? false : $field_zero;
                $field_html .= '<div class="wpf-field-item">';
                $item_html = '<input type="checkbox" ' . $this->check($item['value'], $f['value']) . '
                                            name="' . esc_attr( $item_field_name ) . '" 
                                                value="' . esc_attr( $item['value'] ) . '"
                                                    id="' . esc_attr( $f['fieldId'] . '_' . ($i + 1) ) . '" 
                                                        class="wpf-input-checkbox ' . esc_attr( $f['class'] ) . '" 
                                                             ' . $f['isDisabled'] . ' ' . $f['isRequired'] . ' ' . $f['attributes'] . ' />';
                $field_html .= $this->build_label($item['label'], $item_html, $f);
                $field_html .= '</div>';
                $i++;
            }
            if( $field_zero ) $field_html .= '<input type="hidden" name="' . esc_attr( $item_field_name ) . '" value="0"/>';
        }
        return $field_html;
    }

    /**
     * Radio - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_radio( $f ){
        $i = 0; $field_html = '';
        if( !is_array($f['values']) ) $f['values'] = $this->build_array_using_string_rows( $f['values'] );
        if( !empty($f['values']) ){
            foreach( $f['values'] as $row ){
                $item = $this->build_item_value_lable($row);
                $field_html .= '<div class="wpf-field-item">';
                $item_html = '<input type="radio"  ' . $this->check($item['value'], $f['value']) . '
                                            name="' . esc_attr( $f['fieldName'] ) . '" 
                                                value="' . esc_attr( $item['value'] ) . '" 
                                                    id="' . esc_attr( $f['fieldId'] . '_' . ($i + 1) ) . '" 
                                                        class="wpf-input-radio ' . esc_attr( $f['class'] ) . '" 
                                                            ' . $f['isDisabled'] . ' ' . $f['isRequired'] . ' ' . $f['attributes'] . ' />';
                $field_html .= $this->build_label($item['label'], $item_html, $f);
                $field_html .= '</div>';
                $i++;
            }
        }
        return $field_html;
    }

    /**
     * Radio - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_select( $f ){
        $field_html = '';
        if( !is_array($f['values']) ) $f['values'] = $this->build_array_using_string_rows( $f['values'] );
        if( !empty($f['values']) ){
            $field_html .= '<select name="' . esc_attr( $f['fieldName'] ) . '" 
                                    id="' . esc_attr( $f['fieldId'] ) . '" 
                                        class="' . esc_attr( $f['class'] ) . '" 
                                            ' . $f['isMultiChoice'] . ' ' . $f['isDisabled'] . ' 
                                                ' . $f['isRequired'] . ' ' . $f['attributes'] . '>';
            $field_html .= '<option value="">' . wpforo_phrase('--- Choose ---', false) . '</option>';
            foreach ($f['values'] as $optgroup => $row) {
                if( is_array($row) && !empty($row) ){
                    $field_html .= '<optgroup label="' . esc_attr( $optgroup ) . '">';
                    foreach ( $row as $sub_row ){
                        $item = $this->build_item_value_lable( $sub_row );
                        $item['value'] = ( $f['name'] == 'timezone' ) ? str_replace(' ', '_', $item['value']) : $item['value'];
                        $field_html .= '<option value="' . esc_attr( $item['value'] ) . '" ' . $this->select( $item['value'], $f['value'] ) . '>' . esc_html( $item['label'] ) . '</option>';
                    }
                    $field_html .= '</optgroup>';
                }else{
                    $item = $this->build_item_value_lable($row);
                    $item['value'] = ( $f['name'] == 'timezone' ) ? str_replace(' ', '_', $item['value']) : $item['value'];
                    $field_html .= '<option value="' . esc_attr( $item['value'] ) . '" ' . $this->select( $item['value'], $f['value'] ) . '>' . esc_html( $item['label'] ) . '</option>';
                }
            }
            $field_html .= '</select>';
        }
        return $field_html;
    }

    /**
     * Usergroup - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_usergroup( $f ){
        $field_html = '';
        if( !empty($f['allowedGroupIds']) ){
            $field_html .= '<select name="' . esc_attr( $f['fieldName'] ) . '" 
                                        id="' . esc_attr( $f['fieldId'] ) . '" 
                                            class="' . esc_attr( $f['class'] ) . '" 
                                                ' . $f['isDisabled'] . ' ' . $f['isRequired'] . ' ' . $f['attributes'] . '>';

            $field_html .= '<option value="">' . wpforo_phrase('--- Choose ---', false) . '</option>';
            foreach( $f['allowedGroupIds'] as $groupid ) {
                if( $f['value'] != 4 && $groupid == 4 ) continue;
                if ( $group = WPF()->usergroup->get_usergroup($groupid) ) {
                    $field_html .= '<option value="' . esc_attr($groupid) . '" ' . $this->select( $groupid, $f['value'] ) . '>' . $group['name'] . '</option>';
                }
            }
            $field_html .= '</select>';
        }
        return $field_html;
    }

    /**
     * Avatar - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_avatar( $f ){
        $remote_url = ( $f['value'] && strpos($f['value'], 'wpforo/avatars') === FALSE ) ? $f['value'] : '';
        $field_html = '<ul>
				<li>
				    <input ' . $f['isRequired'] . ' name="' . esc_attr($f['varname']) . '[avatar_type]" id="wpfat_gravatar" value="gravatar" ' . ( $f['value'] == '' || $f['value'] == NULL ? 'checked="checked"' : '' ) . ' type="radio" />&nbsp; 
				    <label for="wpfat_gravatar">' . wpforo_phrase('Wordpress avatar system', false) . '</label>
				</li>
				<li>
				    <input name="' . esc_attr($f['varname']) . '[avatar_type]" id="wpfat_remote" value="remote" ' . ( $f['value'] && strpos($f['value'], 'wpforo/avatars') === FALSE ? 'checked="checked"' : '' ) . ' type="radio" />&nbsp; 
				    <label for="wpfat_remote">' . wpforo_phrase('Specify avatar by URL:', false) . '</label> 
				    <input autocomplete="off" name="' . esc_attr($f['varname']) . '[avatar_url]" value="' . esc_url($remote_url) . '" maxlength="300" data-wpfucf-minmaxlength="1,300" type="url" />
				</li>';
        if( ( wpfval( WPF()->current_object, 'template') && WPF()->current_object['template'] == 'register') || WPF()->perm->usergroup_can('upa') ) {
            if( strpos($f['value'], 'gravatar.com') === FALSE && strpos($f['value'], 'facebook.com') === FALSE ){
                $url = $f['value'] . '?lm=' . time();
            }
            $field_html .= '<li>
                <input name="' . esc_attr($f['varname']) . '[avatar_type]" id="wpfat_custom" value="custom" type="radio" ' . ( (strpos($url, 'wpforo/avatars') !== FALSE) ? 'checked' : '' ) . ' />&nbsp;
                <label for="wpfat_custom"> ' . wpforo_phrase('Upload an avatar', false) . '</label>' . ( strpos($url, 'wpforo/avatars') !== FALSE ? '<br />
                <img src="' . esc_url($url) . '" class="wpf-custom-avatar-img"/>' : '' ) .'&nbsp; 
                <input class="wpf-custom-avatar" name="avatar" type="file" />&nbsp;</li>';
        }
        $field_html .= '</ul> <script type="text/javascript">jQuery(document).ready(function($){$( "input[name=\'' . esc_attr($f['varname']) . '\[avatar_url\]\']" ).click(function(){$( "#wpfat_remote" ).prop(\'checked\', true);}); $( "input[name=\'avatar\']" ).click(function(){$( "#wpfat_custom" ).prop(\'checked\', true);});});</script>';
        return $field_html;
    }

    /**
     * HTML - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_html( $f ){
        return stripslashes( $f['html'] );
    }

    /**
     * URL - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_url( $f ){
        return '<input type="url" value="' . esc_url_raw( $f['value'] ) . '" 
                                        name="' . esc_attr( $f['fieldName'] ) .'" 
                                            id="' . esc_attr( $f['fieldId'] ) . '" 
                                                class="' . esc_attr( $f['class'] ) . '" 
                                                    placeholder="' . esc_attr( $f['placeholder'] ) . '" 
                                                        ' . $f['isRequired'] . ' ' . $f['isDisabled'] . ' ' . $f['attributes'] . '  ' . $f['minmax'] . '/>';
    }

    /**
     * Nickname - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_nicename( $f ){
        return '<input type="text" value="' . esc_attr( urldecode( $f['value'] ) ) . '" 
                                        name="' . esc_attr( $f['fieldName'] ) .'" 
                                            id="' . esc_attr( $f['fieldId'] ) . '" 
                                                class="' . esc_attr( $f['class'] ) . '" 
                                                    placeholder="' . esc_attr( $f['placeholder'] ) . '" 
                                                        ' . $f['isRequired'] . ' ' . $f['isDisabled'] . ' ' . $f['attributes'] . '  ' . $f['minmax'] . '/>';
    }

    /**
     * Text - Field builder
     *
     * @param   $f      field arguments
     * @return  string  field HTML
     */
    public function field_text( $f ){
        return '<input type="' . esc_attr( $f['type'] ) .'" 
                            value="' . esc_attr( $f['value'] ) . '" 
                                name="' . esc_attr( $f['fieldName'] ) .'" 
                                    id="' . esc_attr( $f['fieldId'] ) . '" 
                                        class="' . esc_attr( $f['class'] ) . '" 
                                            placeholder="' . esc_attr( $f['placeholder'] ) . '" 
                                                ' . $f['isRequired'] . ' ' . $f['isDisabled'] . ' ' . $f['attributes'] . '  ' . $f['minmax'] . '/>';
    }

    /**
     * Prepares displayed values for Profile fields
     *
     * @param   $f      field arguments
     * @return  array   prepared values
     */
    public function prepare_values( $f ){
        switch ( $f['type'] ){
            case 'textarea':
                $f['value'] = wpforo_kses(wpforo_decode($f['value']));
                break;
            case 'url':
                $f['value'] = sprintf('<a href="%s" target="_blank" rel="nofollow">%s</a>', $f['value'], $f['value']);
                break;
            case 'email':
                $f['value'] = sprintf('<a href="mailto:%s" rel="nofollow">%s</a>', $f['value'], $f['value']);
                break;
            case 'phone':
                $f['value'] = sprintf('<a href="tel:%s" rel="nofollow">%s</a>', $f['value'], $f['value']);
                break;
            case 'file':
                if( !empty($f['value']) ){
                    $wp_upload_dir = wp_upload_dir();
                    $f['value'] = $wp_upload_dir['baseurl'] . "/" . trim($f['value'], '/');
                    $extension = pathinfo($f['value'], PATHINFO_EXTENSION);
                    $file_url = esc_url_raw( $f['value'] );
                    $file_name = esc_attr( basename($f['value']) );
                    if( wpforo_is_image( $extension ) ){
                        $f['value'] = sprintf('<a href="%s" target="_blank" title="%s"><img src="%s" alt="%s" class="wpf-field-file-img" style="max-width:120px; max-height:120px" /></a>', $file_url, $file_name, $file_url, $file_name );
                    } else {
                        $f['value'] = sprintf('<a href="%s" target="_blank">%s</a>', $file_url, $file_name );
                    }
                }
                break;
        }
        switch ( $f['name'] ){
            case 'skype':
                $f['value'] = sprintf('<a href="skype:%s?userinfo" rel="nofollow">%s</a>', $f['value'], $f['value']);
                break;
            case 'location':
                $f['value'] = sprintf('<a href="//maps.google.com/?q=%s" target="_blank" rel="nofollow">%s</a>', $f['value'], $f['value']);
                break;
            case 'signature':
                $f['value'] = wpforo_signature( $f['value'], array('echo' => 0) );
                break;
            case 'about':
                $f['value'] = wpforo_nofollow_tag( $f['value'] );
                break;
        }
        return $f;
    }

    public function prepare_args( $f ){

        $is_owner = $this->owner();

        //field_class
        $f['field_class'] = sanitize_text_field( $f['name'] );

        //varname
        $f['varname'] = ( !$f['isDefault'] ) ? apply_filters( 'wpforo_user_custom_field_varname', 'data') : $this->varname();

        //template
        $f['template'] = ( isset(WPF()->data['template']) ) ? WPF()->data['template'] : WPF()->current_object['template'];

        //value
        $f['value'] = ( isset(WPF()->data['value'][$f['name']]) ) ? WPF()->data['value'][$f['name']] : $f['value'];
        if( !$f['isDefault'] && $f['varname'] ) {
            $f['value'] = ( isset(WPF()->data['value'][$f['varname']][$f['name']]) ) ? WPF()->data['value'][$f['varname']][$f['name']] : $f['value'];
        }
        $f['value'] = wpforo_unslashe( $f['value'] );

        if( $f['name'] == 'user_nicename' ){
            $f['value'] = urldecode($f['value']);
        }

        //allowedGroupIds
        $groups = array();
        if ( !empty($f['allowedGroupIds']) ) {
            $groups = $this->build_array_value( $f['allowedGroupIds'] );
        }
        if ( !$is_owner && wpforo_current_user_is('admin') ){
            $groups = WPF()->usergroup->get_usergroups('groupid');
        }
        if( $is_owner && !in_array(WPF()->current_user_groupid, $f['allowedGroupIds']) ) {
            $groups = array();
        }
        $f['allowedGroupIds'] = array_filter( $groups );

        //isRequired
        if( $f['isRequired'] ) {
            $f['required_class'] = ' wpf-field-required ';
            $f['required_indicator'] = ' <span class="wpf-field-required-icon" title="' . esc_attr( wpforo_phrase('Required field', false) )  . '">*</span>';
        }
        else{
            $f['required_class'] = '';
            $f['required_indicator'] = '';
        }

        return $f;
    }

    public function prepare_field_args( $f ){
        //faIcon
        $f['faIcon'] = trim($f['faIcon']);
        if( strpos($f['faIcon'], ' ') === false ) $f['faIcon'] = 'fas ' . $f['faIcon'];

        //isRequired
        $f['isRequired'] = ( $f['isRequired'] ) ? ' required="required" ' : '';

        //isDisabled
        $f['isDisabled'] = ( $f['isDisabled'] ) ? ' disabled="disabled" ' : '';

        //isMultiChoice
        $f['isMultiChoice'] = ( $f['isMultiChoice'] ) ? ' multiple="multiple" ' : '';

        //fieldName | new key in args
        $f['fieldName'] = ( !empty($f['varname'] ) ? $f['varname'] . '[' . $f['name'] . ']' : $f['name'] );

        //fieldId | new key in args
        $f['fieldId'] = ( !empty($f['varname'] ) ? $f['varname'] . '_' : '' ) . ( ($f['id']) ? $f['id'] : $f['name'] );

        //minLength & maxLength
        $f['minLength'] = ($f['minLength']) ? intval($f['minLength']): '';
        $f['maxLength'] = ($f['maxLength']) ? intval($f['maxLength']): '';
        if( $f['minLength'] ) { $minLength_attr = ($f['type'] == 'date' || $f['type'] == 'number' || $f['type'] == 'range') ? ' min="' . $f['minLength'] . '" ' : ' minlength="' . $f['minLength'] . '" '; }
        if( $f['maxLength'] ) { $maxLength_attr = ($f['type'] == 'date' || $f['type'] == 'number' || $f['type'] == 'range') ? ' max="' . $f['maxLength'] . '" ' : ' maxlength="' . $f['maxLength'] . '" '; }
        $f['minmax'] = ( isset($minLength_attr) && isset($maxLength_attr) ) ? $minLength_attr . ' ' . $maxLength_attr : '';

        //attributes
        $f['attributes'] .= ' autocomplete="off"';

        return $f;
    }

    public function prepare_file_args( $file_data, $userid = 1 ){

        $file = array( 'files' => array(), 'fields' => array() );
        $userid = ( $userid ) ? $userid : WPF()->current_userid;

        if( !empty( $file_data ) ){
            $wp_upload_dir = wp_upload_dir();
            $wp_basedir = $wp_upload_dir['basedir'] . $this->field_dir;
            foreach( $file_data as $file_field_name => $file_name ){
                $field_name_folder = substr( $file_field_name, 6 );
                $file_upload_dir = $wp_basedir . $userid . '/' . $field_name_folder . '/';
                $file_path = $file_upload_dir . $file_name;
                $file['files'][ $file_field_name ] = $file_path;
                $file['fields'][ $file_field_name ] = $this->field_dir . $userid . '/' . $field_name_folder . '/' . $file_name;
                wp_mkdir_p( $file_upload_dir );
            }
            return $file;
        }
        return false;
    }

    public function check( $item_value, $checked_value ){
        if( is_array($checked_value) ){
            $checked = ( in_array($item_value, $checked_value) ) ? 'checked="checked"' : '';
        }
        else{
            $checked = ( $item_value == $checked_value ) ? 'checked="checked"' : '';
        }
        return $checked;
    }

    public function select( $item_value, $selected_value ){
        $selected = ( $item_value == $selected_value ) ? 'selected="selected"' : '';
        return $selected;
    }

    public function build_label( $item_label, $item_html, $f ){
        $field_html = '';
        if ($f['isWrapItem']) {
            $field_html .= '<label>';
            if ($f['isLabelFirst']) {
                $field_html .= '<span class="wpf-' . $f['type'] . '-label">' . stripslashes($item_label) . '</span>&nbsp;' . $item_html;
            } else {
                $field_html .= $item_html . ' <span class="wpf-' . $f['type'] . '-label">' . stripslashes($item_label) . '</span>';
            }
            $field_html .= '</label>';
        }
        else {
            if ($f['isLabelFirst']) {
                $field_html .= '<span class="wpf-' . $f['type'] . '-label">' . stripslashes($item_label) . '</span>&nbsp;' . $item_html;
            } else {
                $field_html .= $item_html . ' <span class="wpf-' . $f['type'] . '-label">' . stripslashes($item_label) . '</span>';
            }
        }
        return $field_html;
    }

    public function build_item_value_lable( $string, $sep = '=>' ){
        $item = array('value' => '', 'label' => '');
	    $string = trim($string);
        $data = explode($sep, $string);
        $item['value'] = isset($data[0]) ? $data[0] : 'no_value';
        $item['label'] = isset($data[1]) ? $data[1] : $item['value'];
        return $item;
    }

    public function build_array_value( $var, $sep = ',' ){
        if( is_serialized( $var ) ) {
            $var = unserialize( $var );
        } elseif( !is_array($var) && strpos($var, $sep) !== FALSE ) {
            $var = explode($sep, $var);
        } else {
            $var = (array)$var;
        }
        return $var;
    }

    public function build_array_using_string_rows( $string, $regexp = '' ){
        if( !$regexp ) $regexp = '#' . preg_quote(PHP_EOL) . '#isu';
        $array = preg_split($regexp, $string);
        return array_filter($array);
    }

    public function sanitize( $data, $fields ){
        $types = $this->field_types($fields);
        if( !empty($data) && !empty($types) ){
            foreach( $data as $name => $value ){
                if( wpfval($types, $name) ){
                    $data[ $name ] = $this->sanitize_field( $value, $types[ $name ], $name );
                }
            }
        }
        return $data;
    }

    public function sanitize_field( $data, $type = 'text', $name = '' ){
        if( !is_null($data) ){
            if( $type == 'text' ){
                $data = sanitize_text_field($data);
                if( $name == 'user_nicename' ){
                    $data = sanitize_user( $data, true );
                    $data = sanitize_title( $data );
                }
            }
            elseif( $type == 'url' ){
                $data = esc_url_raw($data);
            }
            elseif( $type == 'date' ){
                $data = sanitize_text_field($data);
            }
            elseif( $type == 'textarea' ){
                $data = stripslashes( wpforo_kses( trim( $data ), 'user_description' ) );
            }
            elseif( $type == 'email' ){
                $data = sanitize_email($data);
            }
            elseif( $type == 'password' ){
                $data = trim($data);
            }
            elseif( $type == 'usergroup' ){
                $data = intval($data);
            }
            elseif( $type == 'radio' ){
                $data = sanitize_text_field($data);
            }
            elseif( $type == 'checkbox' ){
                if( $name == 'secondary_groups' ){
                    $data = wpforo_sanitize_int($data);
                } else{
                    $data = wpforo_sanitize_text($data);
                }
            }
            elseif( $type == 'select' ){
                $data = sanitize_text_field($data);
            }
            elseif( $type == 'color' ){
                $data = sanitize_text_field($data);
            }
            elseif( $type == 'date' ){
                $data = sanitize_text_field($data);
            }
            elseif( $type == 'number' ){
                $data = intval($data);
            }
            elseif( $type == 'tel' ){
                $data = sanitize_text_field($data);
            }
            elseif( $type == 'html' ){
                $data = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data);
            }

            if( is_string($data) ){
                $data = stripslashes( $data );
            }
        }
        return $data;
    }

    public function esc_field( $f ){
        if( wpfkey($f, 'value') ){
            $f['value'] = wpforo_trim($f['value']);
            if( wpfval($f, 'type') && $f['type'] == 'textarea' ) {
                $f['value'] = wpautop( wpforo_kses( stripslashes( $f['value'] ) ) );
            } elseif( $f['type'] == 'select' ){
                $f['value'] = ( $f['name'] == 'timezone' ) ? str_replace('_', ' ', $f['value']) : $f['value'];
            } elseif( wpfval($f, 'type') && $f['type'] == 'checkbox' ) {
                if( wpfval($f, 'value') ){
                    if( $f['name'] == 'secondary_groups' ) {
                        $f['value'] = WPF()->usergroup->get_secondary_usergroup_names( $f['value'] );
                        $f['value'] = implode( ', ', $f['value'] );
                    } elseif ( is_array($f['value']) ){
                        $f['value'] = array_filter($f['value']);
                        $f['value'] = implode( ', ', $f['value'] );
                    }
                }
            }
            else{
                $f['value'] = esc_html($f['value']);
            }
        }
        $f['value'] = apply_filters('wpforo_display_field_value', $f['value'], $f );
        return $f;
    }

    public function validate( &$data, &$fields ){
        $type = '';
        $label = '';
        $userid = '';
        $error = array();
        $return = array();
        $is_owner = $this->owner();
        $varname = $this->varname();
        $template = ( isset(WPF()->data['template']) ) ? WPF()->data['template'] : WPF()->current_object['template'];
        if( empty($data) ) $error[] = 'No data submitted';
        if( empty($fields) ) $error[] = 'User profile fields not found';
        if( empty($error) ){
            foreach( $fields as $r_key => $rows ){
                foreach( $rows as $c_key => $cols ){
                    foreach( $cols as $key => $field ){
                        $name = ''; $value = '';
                        if( wpfval($field, 'name') ){
                            $name = $field['name'];
                            if( !$template && wpfval($field,'template') ) $template = $field['template'];
                            if( wpfval($field, 'label') ) $label = esc_html($field['label']);
                            if( wpfval($field, 'type') ) $type = $field['type'];
                            if( wpfval($data, 'userid') ) $userid = $data['userid'];
                            if( $template && $template != 'register' ){
                                if( !$this->can_edit( $field ) ) {
                                    unset($cols[$key]);
                                    unset($data[$name]);
                                    unset($fields[$r_key][$c_key][$key]);
                                }
                            }
                            if( wpfkey($cols, $key) && wpfkey( $data, $name ) ){
                                $value = $data[ $name ];
                                if( is_string($value) ){
                                    $value = trim($value);
                                    $value = htmlspecialchars_decode($value);
                                    $length = wpforo_strlen( $value );
                                }
                                if( wpfval($field, 'isRequired') && !$value){
                                    $error[] = $label . ' ' . wpforo_phrase('field is required', false, false);
                                }
                                if( $value ){
                                    if( $type == 'number' ){
                                        if( wpfval($field, 'minLength') ){
                                            if( (int)$value < $field['minLength'] ){
                                                $error[] = $label . ' ' . sprintf( wpforo_phrase('field value must be at least %d', false, false), intval($field['minLength']) );
                                            }
                                        }
                                        if( wpfval($field, 'maxLength') ){
                                            if( (int)$value > $field['maxLength'] ){
                                                $error[] = $label . ' ' . sprintf( wpforo_phrase('field value cannot be greater than %d', false, false) , intval($field['maxLength']) );
                                            }
                                        }
                                    }
                                    else{
                                        if( wpfval($field, 'minLength') ){
                                            if( $length < $field['minLength'] ){
                                                $error[] = $label . ' ' . sprintf( wpforo_phrase('field length must be at least %d characters', false, false) , intval($field['minLength']) );
                                            }
                                        }
                                        if( wpfval($field, 'maxLength') ){
                                            if( $length > $field['maxLength'] ){
                                                $error[] = $label . ' ' . sprintf( wpforo_phrase('field length cannot be greater than %d characters', false, false) , intval($field['maxLength']) );
                                            }
                                        }
                                    }
                                    if( $type == 'url' && filter_var($value, FILTER_VALIDATE_URL) === FALSE ){
                                        $error[] = $label . ' ' . wpforo_phrase('field value is not a valid URL', false, false);
                                    }
                                    if( $type == 'email' ){
                                        if ( !is_email( $value ) ) {
                                            $error[] = $label . ' ' . wpforo_phrase('Invalid Email address', false, false);
                                        }
                                        if ( $name == 'user_email' ){
                                            $email_owner = email_exists( $value );
                                            if( $email_owner && $email_owner != $userid ){
                                                $error[] = $label . ' ' . wpforo_phrase('This email address is already registered. Please insert another', false, false);
                                            }
                                        }
                                    }
                                    if( $type == 'file' ){
                                        $extension = pathinfo( $value, PATHINFO_EXTENSION );
                                        $extension = ( function_exists('mb_strtolower') ) ? mb_strtolower( $extension ) : strtolower( $extension );
                                        if( wpfval( $field, 'fileExtensions' ) ){
                                            if( $extension ){
                                                if( !in_array( $extension, $field['fileExtensions'] ) ) {
                                                    $error[] = $label . ' ' . wpforo_phrase('file type is not allowed', false, false);
                                                    $error[] = sprintf( 'Allowed file types: %s', implode(', ', $field['fileExtensions']) );
                                                }
                                            } else {
                                                $error[] = $label . ' ' . wpforo_phrase('file type is not detected', false, false);
                                            }
                                        } else {
                                            $mime_types = get_allowed_mime_types();
                                            $mime_types = array_flip( $mime_types );
                                            if( !empty( $mime_types ) ){
                                                $implode_types = implode('|', $mime_types );
                                                $explode_types = explode('|', $implode_types );
                                                if( !in_array( $extension, $explode_types ) ){
                                                    $error[] = $label . ' ' . sprintf( wpforo_phrase('file type %s is not allowed', false, false), $extension );
                                                }
                                                if( !WPF()->perm->can_attach_file_type( $extension ) ){
                                                    $error[] = 'You are not allowed to attach this file type';
                                                }
                                            }
                                        }
                                        if( wpfval( $field, 'fileSize' ) ){
                                            if ( wpfval( $_FILES, $varname, 'size', $name ) && $_FILES[ $varname ]['size'][ $name ] > ( $field['fileSize'] * 1024 * 1024 ) ) {
                                                $error[] = $label . ' ' . wpforo_phrase('file is too large', false, false);
                                                $error[] = sprintf( 'Maximum allowed file size is %s MB', $field['fileSize'] );
                                            }
                                        }
                                    }
                                    if( $name == 'user_nicename' ){
                                        $user_nicename = sanitize_title( trim( $value ) );
                                        if( is_numeric( $user_nicename ) ){
                                            $error[] = 'Numerical nicknames are not allowed. Please insert another.';
                                        }
                                        if( !$user_nicename ){
                                            $error[] = 'Nickname validation failed';
                                        }
                                        $sql = "SELECT `ID` FROM `" . WPF()->db->users . "` WHERE `ID` != " . intval( $userid ) . " AND ( `user_nicename` LIKE '" . esc_sql( $user_nicename ). "' OR `ID` LIKE '" . esc_sql( $user_nicename ) . "')";
                                        if( WPF()->db->get_var($sql)){
                                            $error[] = 'This nickname is already in use. Please insert another.';
                                        }
                                    }
                                    if( $name == 'groupid' ){
                                        if( $template != 'register' ){
                                            if( $is_owner || !wpforo_current_user_is('admin') ){
                                                $error[] = 'You have no permission to edit Usergroup field';
                                            }
                                        } else {
                                            if( $value == 1 || $value == 2 ){
                                                $error[] = 'Admin and Moderator Usergroups are not permitted';
                                            } else {
                                                if( wpfval($field, 'allowedGroupIds') ){
                                                    $allowedGroupIds = wpforo_parse_args( $field['allowedGroupIds'] );
                                                    if( !in_array( $value, $allowedGroupIds ) ) {
                                                        $error[] = 'The selected Usergroup cannot be set';
                                                    }
                                                } else {
                                                    $error[] = 'The selected Usergroup is not found in allowed list';
                                                }
                                            }
                                        }
                                    }
                                    if( $name == 'secondary_groups' ){
                                        $secondary_usergroups = WPF()->usergroup->get_secondary_usergroup_ids();
                                        if( $template != 'register' ){
                                            if( WPF()->current_user_groupid != 1 && WPF()->current_user_groupid != 2 ) {
                                                $error[] = 'You have no permission to edit Usergroup field';
                                            } else{
                                                if( !empty($value) && is_array($value) ){
                                                    foreach( $value as $secondary_usergroup_id ){
                                                        if( $secondary_usergroup_id && !in_array( $secondary_usergroup_id, $secondary_usergroups) ){
                                                            $error[] = 'One of the selected Usergroups cannot be set as Secondary';
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            if( $value == 1 || $value == 2 ){
                                                $error[] = 'Admin and Moderator Usergroups are not permitted';
                                            } else {
                                                if( !empty($value) && is_array($value) ){
                                                    foreach( $value as $secondary_usergroup_id ){
                                                        if( $secondary_usergroup_id && !in_array( $secondary_usergroup_id, $secondary_usergroups) ){
                                                            $error[] = 'One of the selected Usergroups cannot be set as Secondary';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if( !empty($error) ){
            $return['error'] = $error;
            return $return;
        } else {
            return true;
        }
    }

    public function validate_password( $data ){
        $error = array();
        $return = array( 'error' => false );
        if( wpfval($data, 'old_pass') && wpfval($data, 'user_pass1') && wpfval($data, 'user_pass2') ){
            if( $data['user_pass1'] != $data['user_pass2'] ){
                $error[] = 'New Passwords do not match';
            }
            else{
                return true;
            }
        }
        if( !empty($error) ){
            $return['error'] = $error;
            return $return;
        }
        return false;
    }

    public function field_types( $fields ){
        $name_type_fields = array();
        if( !empty($fields) ){
            foreach( $fields as $rows ){
                foreach( $rows as $cols ){
                    foreach( $cols as $field ){
                        if( wpfval($field, 'name') && wpfval($field, 'type') ){
                            $name_type_fields[ $field['name'] ] = $field['type'];
                        }
                    }
                }
            }
        }
        return $name_type_fields;
    }

    public function owner( $object_userid = false ){
        if( !$object_userid ){
            if( wpfval(WPF()->current_object, 'user', 'ID') ){
                return wpforo_is_owner( WPF()->current_object['user']['ID'] );
            } else {
                return false;
            }
        } else{
            return wpforo_is_owner( $object_userid );
        }
    }

    public function can_add( $f ){
        if( wpfval($f, 'name') ){
            if( $f['name'] == 'signature' && !wpforo_feature('signature') ){
                return false;
            }
            if( $f['name'] == 'avatar' && ( !wpforo_feature('custom-avatars') || !wpforo_feature('avatars') ) ){
                return false;
            }
        }
        return true;
    }

    public function can_view( $f ){
        $is_owner = $this->owner();
        if( !$is_owner && !in_array( WPF()->current_user_groupid, $f['canView'] ) ){
            return false;
        } else {
            return true;
        }
    }

    public function can_edit( $f ){
        if( wpfval(WPF()->current_object, 'user', 'ID') ){
            $is_owner = $this->owner();
            $value = wpfkey($f, 'value') ? $f['value'] : false;
            $can_edit = wpfkey($f, 'isEditable') ? $f['isEditable'] : false;
            $can_moderate = $this->can_moderate( $f );
            if( !$is_owner && !$can_moderate && WPF()->current_user_groupid !== 1){
                return false;
            }
            if( !$can_edit && !$can_moderate && WPF()->current_user_groupid !== 1 && !$value ){
                return false;
            }
            if( wpfkey($f, 'name') ){
                if( $f['name'] == 'signature' && ( !WPF()->perm->usergroup_can('ups') || !wpforo_feature('signature'))){
                    return false;
                }
                if( $f['name'] == 'avatar' && ( !wpforo_feature('custom-avatars') || !wpforo_feature('avatars') ) ){
                    return false;
                }
                if( $f['name'] == 'groupid' && ( WPF()->current_user_groupid != 1 || $is_owner || !current_user_can('administrator') ) ){
                    return false;
                }
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function can_moderate( $f ){
        if( empty($f) ) return false;
        $usergroups_who_can_edit = ( !empty($f['canEdit']) ) ? (array)$f['canEdit'] : array(1);
        $can_moderate = ( in_array( WPF()->current_user_groupid, $usergroups_who_can_edit) ) ? true : false;
        return $can_moderate;
    }

}