<?php
namespace NextSocialChat\Helpers;

class SettingsBuilder{
    public $kses_allowed_tags = null; 
    public $settings = array();
    public $assets_url = null;

    public function __construct($settings){
        $this->settings = $settings;
        $this->assets_url = $settings['assets_url'];
        $this->kses_allowed_tags = array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
        );

        $this->hook();
    }


    private function sample_settings(){
        $settings = array(
            'id' => 'option_name',
            'page_title' => 'Custom Metabox',
            'menu_title' => 'normal',
            'capability' => 'manage_options',
            'slug' => 'option-slug',
            'parent_slug' => false,
            'icon_url' => false,
            'assets_url' => '',
            'options' => array(
                array(
                    'type' => 'text',
                    'id' => 'nextsocialchat_field_1',
                    'label' => 'Field 1',
                    'des' => 'Simple description',
                    'default_value' => 'Name',
                    'sanitize' => 'sanitize_text_field',
                    'esc' => 'esc_attr',
                ),
                array(
                    'type' => 'textarea',
                    'id' => 'nextsocialchat_field_2',
                    'label' => 'Field 2',
                    'des' => 'Simple description',
                    'default_value' => 'Name',
                    'sanitize' => 'sanitize_textarea_field',
                    'esc' => 'esc_textarea', 
                ),
                array(
                    'type' => 'color',
                    'id' => 'nextsocialchat_field_3',
                    'label' => 'Field 3',
                    'des' => 'Simple description',
                    'default_value' => 'Name',
                    'sanitize' => 'sanitize_hex_color', 
                    'esc' => 'esc_attr', 
                )
            )
        );
    }

    private function hook(){
        add_action('admin_menu', [$this, 'settings_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        
    }


    function enqueue_scripts() {
        wp_enqueue_script( __NAMESPACE__.'-settings-page-script', $this->assets_url.'settings-page-script.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_style( __NAMESPACE__.'-settings-page-style', $this->assets_url.'settings-page-style.css' );
    }



    function settings_page() {
        $settings = $this->settings;
        if($settings['parent_slug'] != false){
            add_submenu_page( $settings['parent_slug'], $settings['page_title'], $settings['menu_title'], $settings['capability'], $settings['slug'], [$this, 'page_callback'] );
        }else{
            add_menu_page( $settings['page_title'], $settings['menu_title'], $settings['capability'], $settings['slug'], [$this, 'page_callback'] );
        }
    }

    
    // Define the settings page callback
    function page_callback() {
        $settings = $this->settings;
        
        $this->save();
        $get_option = get_option($settings['id']);
        echo '<div class="wrap">';
            echo '<form method="post">';
            wp_nonce_field( basename(__FILE__), $settings['id'].'_settings_nonce');
            echo '<h1>'.esc_html( get_admin_page_title() ).'</h1>';
            echo '<h2 class="nav-tab-wrapper">';
                if(isset($settings['tabs']) && !empty($settings['tabs'])){
                    $tab_i = 1;
                    foreach($settings['tabs'] as $tab_key => $tab){
                       echo '<a class="nav-tab '.( ($tab_i == 1) ?  'nav-tab-active' : '').'" href="#'.$tab_key.'-settings">'.$tab.'</a>'; 
                       $tab_i++;
                    }
                }
            echo ' </h2>';
            
            $tab_i = 1;
            foreach($settings['tabs'] as $tab_key => $tab){
                echo '<div id="'.$tab_key.'-settings" class="tab-content" '.( ($tab_i != 1) ?  'style="display:none;"' : '').'>';
                    echo '<table class="form-table"><tbody>';
                        foreach($settings['options'] as $option){
                            $value = null;
                            if(isset($get_option[$option['id']])){
                                $value = $get_option[$option['id']];
                            }

                            if($option['tab'] == $tab_key){
                                $this->fields($option, $value);
                            }
                        }
                    echo '</tbody></table>';
                echo '</div>';
            }

            submit_button();
            echo '</form>';
        echo '</div>';
    }


    private function fields($option, $value){
        echo '<tr>';
            echo '<th>';
                echo '<label for="'.$option['id'].'_field">'.$option['label'].':</label>';
            echo '</th>';
            echo '<td>';
                if($option['type'] == 'text'){
                    $this->field_text($option, $value); 
                }
                elseif($option['type'] == 'textarea'){
                    $this->field_textarea($option, $value); 
                }
                elseif($option['type'] == 'color'){
                    $this->field_color($option, $value); 
                }
                if($option['des'] != ''){ 
                    echo '<p class="description">'.wp_kses($option['des'], $this->kses_allowed_tags).'</p>'; 
                }
            echo '</td>';
        echo '</tr>';
    }


    private function esc_field_value($option, $value){
        if($option['esc'] == 'esc_html'){
            echo esc_html($value);
        }elseif($option['esc'] == 'esc_js'){
            echo esc_js($value);
        }if($option['esc'] == 'esc_url'){
            echo esc_url($value);
        }if($option['esc'] == 'esc_attr'){
            echo esc_attr($value);
        }if($option['esc'] == 'wp_kses'){
            echo wp_kses($value, $this->kses_allowed_tags);
        }
    }

    private function field_text($option, $value){
        echo '<p><input type="text" id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="widefat" value="';
            $this->esc_field_value($option, $value);
            echo '"></p>';
    }

    private function field_textarea($option, $value){
        echo '<p><textarea id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="widefat">';
            $this->esc_field_value($option, $value);
        echo '</textarea></p>';
    }

    private function field_color($option, $value){
        echo '<p><input type="text" id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="mbbjs_wp_color_picker widefat" value="';
            $this->esc_field_value($option, $value);
            echo '"></p>';
    }


    private function save(){
        $settings = $this->settings;

        // Verify the nonce
        if (!isset($_POST[$settings['id'].'_settings_nonce']) || !wp_verify_nonce($_POST[$settings['id'].'_settings_nonce'], basename(__FILE__))) {
            return;
        }
        if ( !isset( $_POST['submit'] ) ) {
            return;
        }

        $data = array();

        foreach($settings['options'] as $option){
            $field_id = $option['id'];
            if (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_email')) {
                $data[$field_id] = sanitize_email($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_file_name')) {
                $data[$field_id] = sanitize_file_name($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_hex_color')) {
                $data[$field_id] = sanitize_hex_color($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_hex_color_no_hash')) {
                $data[$field_id] = sanitize_hex_color_no_hash($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_html_class')) {
                $data[$field_id] = sanitize_html_class($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_key')) {
                $data[$field_id] = sanitize_key($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_meta')) {
                $data[$field_id] = sanitize_meta($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_mime_type')) {
                $data[$field_id] = sanitize_mime_type($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_sql_orderby')) {
                $data[$field_id] = sanitize_sql_orderby($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_term')) {
                $data[$field_id] = sanitize_term($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_term_field')) {
                $data[$field_id] = sanitize_term_field($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_text_field')) {
                $data[$field_id] = sanitize_text_field($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_textarea_field')) {
                $data[$field_id] = sanitize_textarea_field($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_title')) {
                $data[$field_id] = sanitize_title($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sdsanitize_title_for_queryddddd')) {
                $data[$field_id] = sdsanitize_title_for_queryddddd($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_title_with_dashes')) {
                $data[$field_id] = sanitize_title_with_dashes($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_user')) {
                $data[$field_id] = sanitize_user($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'sanitize_url')) {
                $data[$field_id] = sanitize_url($_POST[$field_id.'_field']);
            }
            elseif (isset($_POST[$field_id.'_field']) && ($option['sanitize'] == 'wp_kses')) {
                $data[$field_id] = wp_kses($_POST[$field_id.'_field'], $this->kses_allowed_tags);
            }
        }

        //var_dump($data);
        delete_option( $settings['id']);
        update_option( $settings['id'], $data);
    }
}