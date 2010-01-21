<?php
  /*
   * Plugin Name: Collecta Search Widget
   * Version: 0.1
   * Plugin URI: http://widget.collecta.com/
   * Description: Collecta.com Real-time widget.
   * Author: Matthew Gregg
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
    $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
    $term = empty($instance['term']) ? 'title' : esc_attr($instance['term']);
    $css = empty($instance['css']) ? '' : '&stylesheet='.esc_attr($instance['css']);
    $background = empty($instance['background']) ? '' : '&headerimg='.esc_attr($instance['background']);
    $rate = empty($instance['rate']) ? '' : '&delay='.esc_attr($instance['rate']);
    $show_logo = isset($instance['show_logo']) ? $instance['show_logo'] : true;
    $use_tags = isset($instance['use_tags']) ? $instance['use_tags'] : true;

# Before the widget
    echo $before_widget;

# The title
    if ( $title )
      echo $before_title . $title . $after_title;

    if ( is_single() and $use_tags ) {
      //title instead of tags as term
      //$term = get_the_title();
      //$title = $term;
      $posttags = get_the_tags();
      $terms = array();
      $nologo = 'true';
      if (count($posttags) > 0) {
          foreach($posttags as $tag) {
            $terms[] = $tag->name;
          }
          $term = join(' OR ',$terms);
          $title = join(', ',$terms);
        }
    }
    if ($show_logo == 'on') {
      $nologo = '';
    }
    echo '<iframe style="border:none;width:100%; height:450px;" src="http://widget.collecta.com/widget.html?notitle=true&query='.$term.'&alias='.$title.'&nologo='.$nologo.$css.$rate.$background.'" id="widgetframe"></iframe>';

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
                                                        'rate'=>'', 
                                                        'show_logo'=>false, 
                                                        'use_tags'=>false) );

    $title = htmlspecialchars($instance['title']);
    $term = htmlspecialchars($instance['term']);
    $css = htmlspecialchars($instance['css']);
    $background = htmlspecialchars($instance['background']);
    $rate = htmlspecialchars($instance['rate']);
    $show_logo = $instance['show_logo'];
    $use_tags = $instance['use_tags'];


    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('term') . '">' . __('Search Term:') . ' <input style="width: 200px;" id="' . $this->get_field_id('term') . '" name="' . $this->get_field_name('term') . '" type="text" value="' . $term . '" /></label></p>';
    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('background') . '">' . __('Header Background URL:') . ' <input style="width: 200px;" id="' . $this->get_field_id('background') . '" name="' . $this->get_field_name('background') . '" type="text" value="' . $background . '" /></label></p>';
    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('css') . '">' . __('URL for External Stylesheet:') . ' <input style="width: 200px;" id="' . $this->get_field_id('css') . '" name="' . $this->get_field_name('css') . '" type="text" value="' . $css . '" /></label></p>';
    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('rate') . '">' . __('Scoll Rate(secs delay between new items):') . ' <input style="width: 200px;" id="' . $this->get_field_id('rate') . '" name="' . $this->get_field_name('rate') . '" type="text" value="' . $rate . '" /></label></p>';
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
