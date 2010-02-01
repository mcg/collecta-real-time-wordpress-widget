<?php
  /*
   * Plugin Name: Collecta Search Widget
   * Version: 0.5
   * Plugin URI: http://widget.collecta.com/
   * Description: Collecta.com Real-time widget.
   * Author: Matthew Gregg, Mick Thompson
   * Author URI: http://collecta.com/
   */
class CollectaWidget extends WP_Widget
{
  function CollectaWidget(){
    $widget_ops = array('classname' => 'widget_collecta', 'description' => __( "Collecta Real-time Search") );
    $control_ops = array('width' => 300, 'height' => 300);
    $this->WP_Widget('collecta', __('Collecta Real-time Search'), $widget_ops, $control_ops);
  }

  //Display
  function widget($args, $instance){
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : esc_attr($instance['title']));
    $term = empty($instance['term']) ? 'title' : urlencode($instance['term']);
    $css = empty($instance['css']) ? '' : '&stylesheet='.urlencode($instance['css']);
    $background = empty($instance['background']) ? '' : '&headerimg='.urlencode($instance['background']);
    $rate = empty($instance['rate']) ? '' : '&delay='.urlencode($instance['rate']);
    $height = empty($instance['height']) ? '' : urlencode($instance['height']);
    $width = empty($instance['width']) ? '' : esc_attr($instance['width']);
    $show_logo = isset($instance['show_logo']) ? $instance['show_logo'] : true;
    $use_tags = isset($instance['use_tags']) ? $instance['use_tags'] : true;

# Before the widget
    echo $before_widget;

# The title
    if ( $title )
      echo $before_title . $title . $after_title;

    if ( is_single() and $use_tags ) {
      $posttags = get_the_tags();
      $terms = array();
      if (count($posttags) > 0) {
          foreach($posttags as $tag) {
            $terms[] = $tag->name;
          }
          $term = join(' OR ',$terms);
        }
    }
    $nologo = 'true';
    if ($show_logo == 'on') {
      $nologo = '';
    }
    echo '<iframe style="border:none;width:'.$width.'; height:'.$height.';" src="http://widget.collecta.com/widget.html?&query='.$term.'&alias=&nologo='.$nologo.$css.$rate.$background.'" id="widgetframe" frameborder="0" scrolling="no"></iframe>';

# After the widget
    echo $after_widget;
  }

  //Save state
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['term'] = strip_tags(stripslashes($new_instance['term']));
    $instance['background'] = strip_tags(stripslashes($new_instance['background']));
    $instance['css'] = strip_tags(stripslashes($new_instance['css']));
    $instance['rate'] = strip_tags(stripslashes($new_instance['rate']));
    $instance['width'] = strip_tags(stripslashes($new_instance['width']));
    $instance['height'] = strip_tags(stripslashes($new_instance['height']));
    $instance['show_logo'] = strip_tags(stripslashes($new_instance['show_logo']));
    $instance['use_tags'] = strip_tags(stripslashes($new_instance['use_tags']));

    return $instance;
  }

  //Edit Form
  function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('title'=>'',
                                                        'term'=>'', 
                                                        'background'=>'', 
                                                        'css'=>'', 
                                                        'width'=>'200px', 
                                                        'height'=>'450px', 
                                                        'rate'=>'', 
                                                        'show_logo'=>true, 
                                                        'use_tags'=>false) );

    $title = htmlspecialchars($instance['title']);
    $term = htmlspecialchars($instance['term']);
    $css = htmlspecialchars($instance['css']);
    $background = htmlspecialchars($instance['background']);
    $rate = htmlspecialchars($instance['rate']);
    $width = htmlspecialchars($instance['width']);
    $height = htmlspecialchars($instance['height']);
    $show_logo = $instance['show_logo'];
    $use_tags = $instance['use_tags'];


    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('term') . '">' . __('Search Term:') . ' <input style="width: 200px;" id="' . $this->get_field_id('term') . '" name="' . $this->get_field_name('term') . '" type="text" value="' . $term . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('background') . '">' . __('Header Background URL:') . ' <input style="width: 200px;" id="' . $this->get_field_id('background') . '" name="' . $this->get_field_name('background') . '" type="text" value="' . $background . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('css') . '">' . __('URL for External Stylesheet:') . ' <input style="width: 200px;" id="' . $this->get_field_id('css') . '" name="' . $this->get_field_name('css') . '" type="text" value="' . $css . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('rate') . '">' . __('Scoll Rate(secs delay between new items):') . ' <input style="width: 200px;" id="' . $this->get_field_id('rate') . '" name="' . $this->get_field_name('rate') . '" type="text" value="' . $rate . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('width') . '">' . __('Width:') . ' <input style="width: 50px;" id="' . $this->get_field_id('width') . '" name="' . $this->get_field_name('width') . '" type="text" value="' . $width . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('height') . '">' . __('Height:') . ' <input style="width: 50px;" id="' . $this->get_field_id('height') . '" name="' . $this->get_field_name('height') . '" type="text" value="' . $height . '" /></label></p>';

    echo '<p style="text-align:right;margin-right:40px;"><label for="' . $this->get_field_name('use_tags') . '">' .__('Use post tags in single posts view?') . ' <input class="checkbox" type="checkbox"'; checked( $use_tags, 'on' ); echo ' id="'. $this->get_field_name('use_tags') .'" name="'. $this->get_field_name('use_tags'). '" /></label></p>';

    echo '<p style="text-align:right;margin-right:40px;"><label for="' . $this->get_field_name('show_logo') . '">' .__('Show Collecta logo and link?') . ' <input class="checkbox" type="checkbox"'; checked( $show_logo, 'on' ); echo ' id="'. $this->get_field_name('show_logo') .'" name="'. $this->get_field_name('show_logo'). '" /></label></p>';

  }

}// END class

//Register

function CollectaInit() {
  register_widget('CollectaWidget');
}
add_action('widgets_init', 'CollectaInit');
?>
