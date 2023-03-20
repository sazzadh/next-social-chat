<?php
namespace NextSocialChat;
use NextSocialChat\Helpers\MetaBoxBuilder;
use NextSocialChat\Helpers\SettingsBuilder;

class Admin{
    protected static $instance = null;

    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
            self::$instance->hook();
        }
        return self::$instance;
    }

    protected  function __construct(){

    }

    private function hook(){
        add_action( 'init', [$this, 'cpt'] );
        $this->MetaBox();
        $this->Settings();
    }

    public function cpt(){
        $labels = array(
            'name'                  => _x( 'Social Chats', 'Post type general name', 'next-social-chat' ),
            'singular_name'         => _x( 'Social Chat', 'Post type singular name', 'next-social-chat' ),
            'menu_name'             => _x( 'Social Chats', 'Admin Menu text', 'next-social-chat' ),
            'name_admin_bar'        => _x( 'Social Chat', 'Add New on Toolbar', 'next-social-chat' ),
            'add_new'               => __( 'Add New', 'next-social-chat' ),
            'add_new_item'          => __( 'Add New Social Chat', 'next-social-chat' ),
            'new_item'              => __( 'New Social Chat', 'next-social-chat' ),
            'edit_item'             => __( 'Edit Social Chat', 'next-social-chat' ),
            'view_item'             => __( 'View Social Chat', 'next-social-chat' ),
            'all_items'             => __( 'All Social Chats', 'next-social-chat' ),
            'search_items'          => __( 'Search Social Chats', 'next-social-chat' ),
            'parent_item_colon'     => __( 'Parent Social Chats:', 'next-social-chat' ),
            'not_found'             => __( 'No Social Chats found.', 'next-social-chat' ),
            'not_found_in_trash'    => __( 'No Social Chats found in Trash.', 'next-social-chat' ),
            'featured_image'        => _x( 'Avatar', 
                                            'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 
                                            'next-social-chat' ),
            'set_featured_image'    => _x( 'Select an image', 
                                            'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 
                                            'next-social-chat' ),
            'remove_featured_image' => _x( 'Remove Avatar image', 
                                            'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 
                                            'next-social-chat' ),
            'use_featured_image'    => _x( 'Use as Avatar image', 
                                            'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 
                                            'next-social-chat' ),
            'archives'              => _x( 'Social Chat archives', 
                                            'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 
                                            'next-social-chat' ),
            'insert_into_item'      => _x( 'Insert into Social Chat', 
                                            'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 
                                            'next-social-chat' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this Social Chat', 
                                            'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 
                                            'next-social-chat' ),
            'filter_items_list'     => _x( 'Filter Social Chats list', 
                                            'Screen reader text for the filter links heading on the post type listing screen.', 
                                            'next-social-chat' ),
            'items_list_navigation' => _x( 'Social Chats list navigation', 
                                            'Screen reader text for the pagination heading on the post type listing screen.', 
                                            'next-social-chat' ),
            'items_list'            => _x( 'Social Chats list', 
                                            'Screen reader text for the items list heading on the post type listing screen.', 
                                            'next-social-chat' ),
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'next-social-chat' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'thumbnail'),
        );
    
        register_post_type( 'next_social_chat', $args );
    }


    public function MetaBox(){
        $prefix = 'nextsocialchat_whatsapp_';
        $settings = array(
            'id' => 'NextSocialChat_WhatsApp_Account_Information',
            'title' => __('WhatsApp Account Information', 'next-social-chat' ),
            'context' => 'normal',
            'priority' => 'default',
            'post' => 'next_social_chat',
            'assets_url' => NEXTSOCIALCHAT_PLUGIN_URL.'/assets/',
            'options' => array(
                array(
                    'type' => 'text',
                    'id' => $prefix.'account_number',
                    'label' => __('Account Number or group chat URL', 'next-social-chat' ),
                    'des' => sprintf( __( 'Refer to <a href="%s" target="_blank">This Documentation</a> for a detailed explanation.', 'next-social-chat' ),'https://faq.whatsapp.com/en/general/21016748'),
                    'default_value' => '',
                ),
                array(
                    'type' => 'text',
                    'id' => $prefix.'title',
                    'label' => __('Title', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                ),
                array(
                    'type' => 'textarea',
                    'id' => $prefix.'predefined_text',
                    'label' => __('Predefined Text', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'always_available_at_online',
                    'label' => __('Always available online', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                )
            )
        );
        $meatbox = new MetaBoxBuilder($settings);



        /*
            Button style Metabox
        -----------------------*/
        $prefix = 'nextsocialchat_whatsapp_button_';
        $settings = array(
            'id' => 'NextSocialChat_WhatsApp_Button_style',
            'title' => __('Button Style', 'next-social-chat' ),
            'context' => 'normal',
            'priority' => 'default',
            'post' => 'next_social_chat',
            'extra_html' => null,
            'assets_url' => NEXTSOCIALCHAT_PLUGIN_URL.'/assets/',
            'options' => array(
                array(
                    'type' => 'html',
                    'des' => __( 'This options and styling applies only to the shortcode buttons for this account.', 'next-social-chat' ),
                ),
                array(
                    'type' => 'text',
                    'id' => $prefix.'label',
                    'label' => __('Button Label', 'next-social-chat' ),
                    'des' => __('This text applies only on shortcode button. Leave empty to use the default label.', 'next-social-chat' ),
                    'default_value' => '',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'style_round',
                    'label' => __('Make the Button Round Style', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'color',
                    'id' => $prefix.'bg_color',
                    'label' => __('Button Background Color', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '#2DB742',
                ),
                array(
                    'type' => 'color',
                    'id' => $prefix.'text_color',
                    'label' => __('Button Text Color', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '#fff',
                ),
            )
        );
        $meatbox = new MetaBoxBuilder($settings);


        /*
            Custom Availability
        -----------------------*/
        $time_blocks_array = array(
            array('value' => '00:00', 'label' => '00:00'),
            array('value' => '00:30', 'label' => '00:30'),
            array('value' => '01:00', 'label' => '01:00'),
            array('value' => '01:30', 'label' => '01:30'),
            array('value' => '02:00', 'label' => '02:00'),
            array('value' => '02:30', 'label' => '02:30'),
            array('value' => '03:00', 'label' => '03:00'),
            array('value' => '03:30', 'label' => '03:30'),
            array('value' => '04:00', 'label' => '04:00'),
            array('value' => '04:30', 'label' => '04:30'),
            array('value' => '05:00', 'label' => '05:00'),
            array('value' => '05:30', 'label' => '05:30'),
            array('value' => '06:00', 'label' => '06:00'),
            array('value' => '06:30', 'label' => '06:30'),
            array('value' => '07:00', 'label' => '07:00'),
            array('value' => '07:30', 'label' => '07:30'),
            array('value' => '08:00', 'label' => '08:00'),
            array('value' => '08:30', 'label' => '08:30'),
            array('value' => '09:00', 'label' => '09:00'),
            array('value' => '09:30', 'label' => '09:30'),
            array('value' => '10:00', 'label' => '10:00'),
            array('value' => '10:30', 'label' => '10:30'),
            array('value' => '11:00', 'label' => '11:00'),
            array('value' => '11:30', 'label' => '11:30'),
            array('value' => '12:00', 'label' => '12:00'),
            array('value' => '12:30', 'label' => '12:30'),
            array('value' => '13:00', 'label' => '13:00'),
            array('value' => '13:30', 'label' => '13:30'),
            array('value' => '14:00', 'label' => '14:00'),
            array('value' => '14:30', 'label' => '14:30'),
            array('value' => '15:00', 'label' => '15:00'),
            array('value' => '15:30', 'label' => '15:30'),
            array('value' => '16:00', 'label' => '16:00'),
            array('value' => '16:30', 'label' => '16:30'),
            array('value' => '17:00', 'label' => '17:00'),
            array('value' => '17:30', 'label' => '17:30'),
            array('value' => '18:00', 'label' => '18:00'),
            array('value' => '18:30', 'label' => '18:30'),
            array('value' => '19:00', 'label' => '19:00'),
            array('value' => '19:30', 'label' => '19:30'),
            array('value' => '20:00', 'label' => '20:00'),
            array('value' => '20:30', 'label' => '20:30'),
            array('value' => '21:00', 'label' => '21:00'),
            array('value' => '21:30', 'label' => '21:30'),
            array('value' => '22:00', 'label' => '22:00'),
            array('value' => '22:30', 'label' => '22:30'),
            array('value' => '23:00', 'label' => '23:00'),
            array('value' => '23:30', 'label' => '23:30'),
            array('value' => '23:59', 'label' => '23:59'),
        );
        $prefix = 'nextsocialchat_custom_availability_';
        $settings = array(
            'id' => 'NextSocialChat_custom_availability',
            'title' => __('Custom Availability', 'next-social-chat' ),
            'context' => 'normal',
            'priority' => 'default',
            'post' => 'next_social_chat',
            'extra_html' => null,
            'assets_url' => NEXTSOCIALCHAT_PLUGIN_URL.'/assets/',
            'options' => array(
                array(
                    'type' => 'html',
                    'des' => '<h3>'.__( 'Sunday', 'next-social-chat' ).'</h3>',
                ),

                /*== Sunday Time Block #1 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'sunday_1_enable',
                    'label' => __('Sunday Time Block #1', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'sunday_1_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'sunday_1_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),


                /*== Sunday Time Block #2 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'sunday_2_enable',
                    'label' => __('Sunday Time Block #2', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'sunday_2_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'sunday_2_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),

                /*== Sunday Time Block #3 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'sunday_3_enable',
                    'label' => __('Sunday Time Block #3', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'sunday_3_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'sunday_3_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table><hr>',
                ),


                array(
                    'type' => 'html',
                    'des' => '<h3>'.__( 'Monday', 'next-social-chat' ).'</h3>',
                ),

                /*== Monday Time Block #1 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'monday_1_enable',
                    'label' => __('Monday Time Block #1', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'monday_1_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'monday_1_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),


                /*== Monday Time Block #2 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'monday_2_enable',
                    'label' => __('Monday Time Block #2', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'monday_2_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'monday_2_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),

                /*== Monday Time Block #3 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'monday_3_enable',
                    'label' => __('Monday Time Block #3', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'monday_3_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'monday_3_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table><hr>',
                ),



                array(
                    'type' => 'html',
                    'des' => '<h3>'.__( 'Tuesday', 'next-social-chat' ).'</h3>',
                ),

                /*== Tuesday Time Block #1 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'tuesday_1_enable',
                    'label' => __('Tuesday Time Block #1', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'tuesday_1_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'tuesday_1_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),


                /*== Tuesday Time Block #2 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'tuesday_2_enable',
                    'label' => __('Tuesday Time Block #2', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'tuesday_2_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'tuesday_2_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),

                /*== Tuesday Time Block #3 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'tuesday_3_enable',
                    'label' => __('Tuesday Time Block #3', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'tuesday_3_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'tuesday_3_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table><hr>',
                ),



                array(
                    'type' => 'html',
                    'des' => '<h3>'.__( 'Wednesday', 'next-social-chat' ).'</h3>',
                ),

                /*== Wednesday Time Block #1 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'wednesday_1_enable',
                    'label' => __('Wednesday Time Block #1', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'wednesday_1_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'wednesday_1_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),


                /*== Wednesday Time Block #2 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'wednesday_2_enable',
                    'label' => __('Wednesday Time Block #2', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'wednesday_2_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'wednesday_2_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),

                /*== Wednesday Time Block #3 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'wednesday_3_enable',
                    'label' => __('Wednesday Time Block #3', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'wednesday_3_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'wednesday_3_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table><hr>',
                ),


                array(
                    'type' => 'html',
                    'des' => '<h3>'.__( 'Thursday', 'next-social-chat' ).'</h3>',
                ),

                /*== Thursday Time Block #1 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'thursday_1_enable',
                    'label' => __('Thursday Time Block #1', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'thursday_1_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'thursday_1_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),


                /*== Thursday Time Block #2 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'thursday_2_enable',
                    'label' => __('Thursday Time Block #2', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'thursday_2_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'thursday_2_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),

                /*== Thursday Time Block #3 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'thursday_3_enable',
                    'label' => __('Thursday Time Block #3', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'thursday_3_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'thursday_3_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table><hr>',
                ),



                array(
                    'type' => 'html',
                    'des' => '<h3>'.__( 'Friday', 'next-social-chat' ).'</h3>',
                ),

                /*== Friday Time Block #1 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'friday_1_enable',
                    'label' => __('Friday Time Block #1', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'friday_1_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'friday_1_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),


                /*== Friday Time Block #2 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'friday_2_enable',
                    'label' => __('Friday Time Block #2', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'friday_2_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'friday_2_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),

                /*== Friday Time Block #3 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'friday_3_enable',
                    'label' => __('Friday Time Block #3', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'friday_3_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'friday_3_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table><hr>',
                ),



                array(
                    'type' => 'html',
                    'des' => '<h3>'.__( 'Saturday', 'next-social-chat' ).'</h3>',
                ),

                /*== Saturday Time Block #1 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'saturday_1_enable',
                    'label' => __('Saturday Time Block #1', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'saturday_1_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'saturday_1_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),


                /*== Saturday Time Block #2 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'saturday_2_enable',
                    'label' => __('Saturday Time Block #2', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'saturday_2_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'saturday_2_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table>',
                ),

                /*== Saturday Time Block #3 ==*/
                array(
                    'type' => 'html',
                    'des' => '<table class="form-table" style="margin-top: 0;"><tbody><tr><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'checkbox',
                    'id' => $prefix.'saturday_3_enable',
                    'label' => __('Saturday Time Block #3', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '1',
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'saturday_3_start',
                    'label' => __('Start Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td><td style="padding: 0px;">',
                ),
                array(
                    'type' => 'select',
                    'id' => $prefix.'saturday_3_end',
                    'label' => __('End Time', 'next-social-chat' ),
                    'des' => '',
                    'default_value' => '',
                    'options' => $time_blocks_array,
                ),
                array(
                    'type' => 'html',
                    'des' => '</td></tr></tbody></table><hr>',
                ),


            )
        );
        $meatbox = new MetaBoxBuilder($settings);
    }


    public function Settings(){
        $settings = array(
            'id' => 'next_social_chat',
            'page_title' => 'Next Social Chat Settings',
            'menu_title' => 'Settings',
            'capability' => 'manage_options',
            'slug' => 'next-social-chat',
            'parent_slug' => 'edit.php?post_type=next_social_chat',
            'icon_url' => false,
            'assets_url' => NEXTSOCIALCHAT_PLUGIN_URL.'/assets/',
            'tabs' => array(
                'general' => 'Geleral Settings',
                'advance' => 'Advance Settings'
            ),
            'options' => array(
                array(
                    'type' => 'text',
                    'id' => 'nextsocialchat_field_1',
                    'label' => 'Field 1',
                    'des' => 'Simple description',
                    'default_value' => 'Name',
                    'sanitize' => 'sanitize_text_field',
                    'esc' => 'esc_attr',
                    'tab' => 'general',
                ),
                array(
                    'type' => 'textarea',
                    'id' => 'nextsocialchat_field_2',
                    'label' => 'Field 2',
                    'des' => 'Simple description',
                    'default_value' => 'Name',
                    'sanitize' => 'sanitize_textarea_field',
                    'esc' => 'esc_textarea', 
                    'tab' => 'advance',
                ),
                array(
                    'type' => 'color',
                    'id' => 'nextsocialchat_field_3',
                    'label' => 'Field 3',
                    'des' => 'Simple description',
                    'default_value' => 'Name',
                    'sanitize' => 'sanitize_hex_color', 
                    'esc' => 'esc_attr', 
                    'tab' => 'general',
                )
            )
        );
        $settings = new SettingsBuilder($settings);
    }
}