<?php
namespace NextSocialChat\Helpers;

class MetaBoxBuilder{
    protected static $instance = null;
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
            'h3' => array(),
            'hr' => array(),
            'table' => array(
                'class' => array(),
                'style' => array()
            ),
            'tr' => array(
                'class' => array(),
                'style' => array()
            ),
            'th' => array(
                'class' => array(),
                'style' => array()
            ),
            'td' => array(
                'class' => array(),
                'style' => array()
            ),
        );

        $this->hook();
    }

    private function hook(){
        add_action('add_meta_boxes', [$this, 'metabox']);
        add_action('save_post', [$this, 'save_metabox_fields']);
        add_action('admin_enqueue_scripts', [$this, 'metabox_scripts']);
    }

    public function metabox() {
        $settings = $this->settings;
        add_meta_box(
            $settings['id'].'_metabox_id',
            $settings['title'],
            [$this, 'metabox_callback'],
            $settings['post'],
            $settings['context'],
            $settings['priority']
        );
    }


    // Define the metabox callback
    public function metabox_callback($post) {
        $settings = $this->settings;

        wp_nonce_field( basename(__FILE__), $settings['id'].'_metabox_nonce');

        if(!empty($settings['extra_html'])){
            echo '<div class="nsc_metaboxbuilder_row">';
                echo '<div class="nsc_metaboxbuilder_col">';
                    
                    foreach($settings['options'] as $option){
                        $this->field($option, $post);
                    }
                    
                echo '</div>';
                echo '<div class="nsc_metaboxbuilder_col">';
                    echo $settings['extra_html'];
                echo '</div>';
            echo '</div>';
        }else{
            
            foreach($settings['options'] as $option){
                
                        $this->field($option, $post);
                    
            }
            
        }
        
    }


    // Save the metabox fields
    public function save_metabox_fields($post_id) {
        $settings = $this->settings;

        // Verify the nonce
        if (!isset($_POST[$settings['id'].'_metabox_nonce']) || !wp_verify_nonce($_POST[$settings['id'].'_metabox_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        foreach($settings['options'] as $option){
            $this->sanitize_field_value_and_save($post_id, $option);
        }
    }


    // Enqueue the necessary scripts
    public function metabox_scripts() {
        wp_enqueue_style( __NAMESPACE__.'-metabox-style', $this->assets_url.'metabox-style.css' );

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script(__NAMESPACE__.'-metabox-script', $this->assets_url . 'metabox-script.js', array('wp-color-picker'), false, true);
    }


    public function field($option, $post){
        if($option['type'] == 'html'){
            echo wp_kses($option['des'], $this->kses_allowed_tags);
        }else{
            echo '<table class="form-table" style="margin-top: 0;"><tbody>';
            echo '<tr>';
                echo '<th>';
                    echo '<label for="'.$option['id'].'_field">'.$option['label'].':</label>';
                echo '</th>';
                echo '<td>';
                    if($option['type'] == 'text'){
                        $this->field_text($option, $post); 
                    }
                    elseif($option['type'] == 'textarea'){
                        $this->field_textarea($option, $post); 
                    }
                    elseif($option['type'] == 'textarea_html'){
                        $this->field_textarea($option, $post); 
                    }
                    elseif($option['type'] == 'color'){
                        $this->field_color($option, $post); 
                    }
                    elseif($option['type'] == 'checkbox'){
                        $this->field_checkbox($option, $post); 
                    }
                    elseif($option['type'] == 'url'){
                        $this->field_url($option, $post); 
                    }
                    elseif($option['type'] == 'select'){
                        $this->field_select($option, $post); 
                    }
                    if($option['des'] != ''){ 
                        echo '<p class="description">'.wp_kses($option['des'], $this->kses_allowed_tags).'</p>'; 
                    }
                echo '</td>';
            echo '</tr>';
            echo '</tbody></table>';
        }
        
    }

    public function field_text($option, $post){
        $value = get_post_meta($post->ID, $option['id'], true);

        echo '<p><input type="text" id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="widefat" value="'.esc_attr($value).'"></p>';
    }

    public function field_textarea($option, $post){
        $value = get_post_meta($post->ID, $option['id'], true);

        echo '<p><textarea id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="widefat">';
            echo wp_kses($value, $this->kses_allowed_tags);
        echo '</textarea></p>';
    }

    public function field_color($option, $post){
        $value = get_post_meta($post->ID, $option['id'], true);

        echo '<p><input type="text" id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="mbbjs_wp_color_picker widefat" value="'.esc_attr($value).'"></p>';
    }

    public function field_checkbox($option, $post){
        $value = get_post_meta($post->ID, $option['id'], true);

        echo '<p><input type="checkbox" id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="widefat" ';
            echo 'value="1"';
            echo ($value === '1' ? 'checked' : '');
        echo '></p>';
    }

    public function field_url($option, $post){
        $value = get_post_meta($post->ID, $option['id'], true);

        echo '<p><input type="text" id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="widefat" value="'.esc_url($value).'"></p>';
    }

    public function field_html($option, $post){
        echo wp_kses($option['des'], $this->kses_allowed_tags);
    }


    public function field_select($option, $post){
        $value = get_post_meta($post->ID, $option['id'], true);
        if(!empty($option['options'])){
            echo '<select  id="'.$option['id'].'_field" name="'.$option['id'].'_field" class="widefat" value="'.esc_attr($value).'">';
                foreach($option['options'] as $g_option){
                    $checked = ($value === $g_option['value'] ? 'selected' : '');
                    echo '<option value="'.esc_attr($g_option['value']).'" '.$checked.'>'.esc_attr($g_option['label']).'</option>';
                }
            echo '</select>';
        }

        
    }



    public function sanitize_field_value_and_save($post_id, $option){
        $field_id = $option['id'];
        $prev_value = get_post_meta($post->ID, $option['id'], true);


        if($option['type'] == 'checkbox'){
            update_post_meta( $post_id,  $field_id, sanitize_text_field($_POST[$field_id.'_field']) );
        }
        elseif($option['type'] == 'text'){
            update_post_meta($post_id,  $field_id, sanitize_text_field($_POST[$field_id.'_field']));
        }
        elseif($option['type'] == 'textarea'){
            update_post_meta($post_id,  $field_id, sanitize_textarea_field($_POST[$field_id.'_field']));
        }
        elseif($option['type'] == 'color'){
            update_post_meta($post_id,  $field_id, sanitize_hex_color($_POST[$field_id.'_field']));
        }
        elseif($option['type'] == 'textarea_html'){
            update_post_meta($post_id,  $field_id, wp_kses($_POST[$field_id.'_field'], $this->kses_allowed_tags));
        }
        elseif($option['type'] == 'url'){
            update_post_meta($post_id,  $field_id, sanitize_url($_POST[$field_id.'_field']));
        }
        elseif($option['type'] == 'select'){
            update_post_meta($post_id,  $field_id, sanitize_text_field($_POST[$field_id.'_field']));
        }
        
    }
}