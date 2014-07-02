<?php
/*
Plugin Name: Real Estate Cloud
Plugin URI: http://www.realestatecloud.co
Description: Real Estate MLS IDX Plugin
Version: 1.5.1
Author: Real Estate Cloud LLC
Author URI: http://www.realestatecloud.co
License: Real Estate Cloud LLC
*/

 
class WidgetContactUs extends WP_Widget {

    public function WidgetContactUs()
    {
        $widget_ops = array('classname' => 'KenWidgetContactsUs', 'description' => 'Contact Us Form');
	    $control_ops = array(); //array('width' => 400, 'height' => 200);

	    $this->WP_Widget('KenWidgetContactsUs', 'Contact Us', $widget_ops, $control_ops);
    }

    public function widget($args, $instance)
    {
        if (!isset($args['widget_name'])) {
            $args['widget_name'] = 'Contact Us';
        }

        echo $args['before_widget'];
        echo $args['before_title'] . $args['widget_name'] . $args['after_title'];

        $out = '<form class="ground-form" method="post" action="' . KenPropertyRouting::generateUrl(array()) . '">' .
                    '<input name="propertyPart" type="hidden" value="contacts" />' .
                    '<input name="propertyAction" type="hidden" value="default" />' .
                    '<input name="username" type="text" value="" default="Full Name" class="wrg-text autofocus focus" />' .
                    '<input name="email" type="text" value="" default="Email" class="wrg-text autofocus focus" />' .
                    '<textarea name="message" default="Message" class="wrg-textarea autofocus focus"></textarea>' .
                    '<input type="submit" value="Submit" class="wrg-button-small" />' .
                    '<span style="display:none;" class="ground-form-error">Invalid email address</span>' .
                    '<div style="display:none;" class="ground-form-message">Thanks for sending your message! We\'ll get back to you shortly.</div>' .
                '</form>';

        echo $out;
        echo $args['after_widget'];
    }

    /*function update($new_instance, $old_instance)
    {
        
    }

    function form($instance)
    {
        
    }*/

}
