<?php
/*
 * Plugin Name: Collecta Search Widget
 * Version: 0.1
 * Plugin URI: http://widget.collecta.com/wp
 * Description: Collecta.com Real-time widget.
 * Author: Matthew Gregg
 * Author URI: http://braintube.com/
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
      $term = empty($instance['term']) ? 'title' : $instance['term'];
      $use_tags = isset($instance['use_tags']) ? $instance['use_tags'] : true;

      # Before the widget
      echo $before_widget;

      # The title
      if ( $title )
      echo $before_title . $title . $after_title;

      if ( is_single() and $use_tags ) {
        //$term = get_the_title();
        //$title = $term;
        $posttags = get_the_tags();
        $terms = array();
        foreach($posttags as $tag) {
            $terms[] = $tag->name;
        }
        $term = join(' OR ',$terms);
        $title = join(', ',$terms);
      }
      echo '<iframe style="border:none;width:100%; height:450px;" src="http://widget.collecta.com/widget.html?query='.$term.'&alias='.$title.'" id="widgetframe"></iframe>';

      # After the widget
      echo $after_widget;
  }

    //Save state
    function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance['title'] = strip_tags(stripslashes($new_instance['title']));
      $instance['term'] = strip_tags(stripslashes($new_instance['term']));
      $instance['use_tags'] = strip_tags(stripslashes($new_instance['use_tags']));

    return $instance;
  }

    //Edit Form
    function form($instance){
      //Defaults
      $instance = wp_parse_args( (array) $instance, array('title'=>'', 'term'=>'', 'use_tags'=>false) );

      $title = htmlspecialchars($instance['title']);
      $term = htmlspecialchars($instance['term']);
      $use_tags = $instance['use_tags'];


      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
      echo '<p style="text-align:right;"><label for="' . $this->get_field_name('term') . '">' . __('Search Term:') . ' <input style="width: 200px;" id="' . $this->get_field_id('term') . '" name="' . $this->get_field_name('term') . '" type="text" value="' . $term . '" /></label></p>';
      echo '<p style="text-align:right;margin-right:40px;"><label for="' . $this->get_field_name('use_tags') . '">' .__('Use post tags in single posts view?') . ' <input class="checkbox" type="checkbox"'; checked( $use_tags, true ); echo ' id="'. $this->get_field_name('use_tags') .'" name="'. $this->get_field_name('use_tags'). '" /></label></p>';

  }

}// END class

    //Register

  function CollectaInit() {
  register_widget('CollectaWidget');
  }
  add_action('widgets_init', 'CollectaInit');
?>
