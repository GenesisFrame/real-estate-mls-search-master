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

 
class WidgetAgent extends WP_Widget {

    public function WidgetSearchForm()
    {
        $widget_ops = array('classname' => 'KenWidgetAgent', 'description' => 'Show Agent');
	    $control_ops = array(); //array('width' => 400, 'height' => 200);

	    $this->WP_Widget('KenWidgetSearchProperty', 'Your Agent', $widget_ops, $control_ops);
    }

    public function widget($args, $instance)
    {
        if (!isset($args['widget_name'])) {
            $args['widget_name'] = 'Your Agent';
        }

        echo $args['before_widget'];
        echo $args['before_title'] . $args['widget_name'] . $args['after_title'];

        $out = '
        <div class="content-agent">
            <div class="content-right-message">Willard Cunningham</div>
            <div class="content-agent-photo">
                <img src="http://recloud.me/agent_photo/71/original.jpg" alt="" width="254" height="254" />
            </div>
        </div>
        ';

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
