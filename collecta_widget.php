<?php
  /*
   * Plugin Name: Collecta Search Widget
   * Version: 1.0
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
    $js_filter = empty($instance['js_filter']) ? '' : '&filter='.urlencode($instance['js_filter']);
    $background = empty($instance['background']) ? '' : '&headerimg='.urlencode($instance['background']);
    $rate = empty($instance['rate']) ? '' : '&delay='.urlencode($instance['rate']);
    $archive = isset($instance['archive']) ? $instance['archive'] : true;
    $height = empty($instance['height']) ? '' : urlencode($instance['height']);
    $width = empty($instance['width']) ? '' : esc_attr($instance['width']);
    $language = empty($instance['language']) ? '' : '+language:'.urlencode($instance['language']);
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
      if ($posttags) {
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
    echo '<iframe style="border:none;width:'.$width.'; height:'.$height.';" src="http://widget.collecta.com/widget.html?&query='.$term.$language.'&alias=&nologo='.$nologo.$css.$rate.$background.$js_filter.$archive.'" id="widgetframe" frameborder="0" scrolling="no"></iframe>';

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
    $instance['js_filter'] = strip_tags(stripslashes($new_instance['js_filter']));
    $instance['rate'] = strip_tags(stripslashes($new_instance['rate']));
    $instance['archive'] = strip_tags(stripslashes($new_instance['archive']));
    $instance['width'] = strip_tags(stripslashes($new_instance['width']));
    $instance['height'] = strip_tags(stripslashes($new_instance['height']));
    $instance['language'] = strip_tags(stripslashes($new_instance['language']));
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
                                                        'js_filter'=>'', 
                                                        'width'=>'200px', 
                                                        'height'=>'450px', 
                                                        'rate'=>'', 
                                                        'language'=>'', 
                                                        'show_logo'=>true, 
                                                        'archive'=>false, 
                                                        'use_tags'=>false) );

    $title = htmlspecialchars($instance['title']);
    $term = htmlspecialchars($instance['term']);
    $css = htmlspecialchars($instance['css']);
    $js_filter = htmlspecialchars($instance['js_filter']);
    $background = htmlspecialchars($instance['background']);
    $rate = htmlspecialchars($instance['rate']);
    $width = htmlspecialchars($instance['width']);
    $height = htmlspecialchars($instance['height']);
    $language = htmlspecialchars($instance['language']);
    $show_logo = $instance['show_logo'];
    $archive = $instance['archive'];
    $use_tags = $instance['use_tags'];


    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 250px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('term') . '">' . __('Search Term:') . ' <input style="width: 200px;" id="' . $this->get_field_id('term') . '" name="' . $this->get_field_name('term') . '" type="text" value="' . $term . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('language') . '">' .  __('Language:') . ' <select id="' . $this->get_field_id( 'language' ) . '" name="' . $this->get_field_name('language') . '">';
    echo '<option value=""'; selected('',$language); echo '>--ALL--</option>';
    echo '<option value="ar"'; selected('ar',$language); echo '>Arabic</option>';
    echo '<option value="bg"'; selected('bg',$language); echo '>Bulgarian</option>';
    echo '<option value="zh"'; selected('zh',$language); echo '>Chinese</option>';
    echo '<option value="hr"'; selected('hr',$language); echo '>Croatian</option>';
    echo '<option value="cs"'; selected('cs',$language); echo '>Czech</option>';
    echo '<option value="da"'; selected('da',$language); echo '>Danish</option>';
    echo '<option value="nl"'; selected('nl',$language); echo '>Dutch</option>';
    echo '<option value="en"'; selected('en',$language); echo '>English</option>';
    echo '<option value="et"'; selected('et',$language); echo '>Estonian</option>';
    echo '<option value="fa"'; selected('fa',$language); echo '>Farsi</option>';
    echo '<option value="fi"'; selected('fi',$language); echo '>Finnish</option>';
    echo '<option value="fr"'; selected('fr',$language); echo '>French</option>';
    echo '<option value="fy"'; selected('fy',$language); echo '>Frisian</option>';
    echo '<option value="de"'; selected('de',$language); echo '>German</option>';
    echo '<option value="el"'; selected('el',$language); echo '>Greek</option>';
    echo '<option value="he"'; selected('he',$language); echo '>Hebrew</option>';
    echo '<option value="hi"'; selected('hi',$language); echo '>Hindi</option>';
    echo '<option value="hu"'; selected('hu',$language); echo '>Hungarian</option>';
    echo '<option value="is"'; selected('is',$language); echo '>Icelandic</option>';
    echo '<option value="io"'; selected('io',$language); echo '>Ido</option>';
    echo '<option value="ga"'; selected('ga',$language); echo '>Irish</option>';
    echo '<option value="it"'; selected('it',$language); echo '>Italian</option>';
    echo '<option value="ja"'; selected('ja',$language); echo '>Japanese</option>';
    echo '<option value="ko"'; selected('ko',$language); echo '>Korean</option>';
    echo '<option value="no"'; selected('no',$language); echo '>Norwegian</option>';
    echo '<option value="pl"'; selected('pl',$language); echo '>Polish</option>';
    echo '<option value="pt"'; selected('pt',$language); echo '>Portuguese</option>';
    echo '<option value="ro"'; selected('ro',$language); echo '>Romanian</option>';
    echo '<option value="ru"'; selected('ru',$language); echo '>Russian</option>';
    echo '<option value="sl"'; selected('sl',$language); echo '>Slovenian</option>';
    echo '<option value="es"'; selected('es',$language); echo '>Spanish</option>';
    echo '<option value="sv"'; selected('sv',$language); echo '>Swedish</option>';
    echo '<option value="th"'; selected('th',$language); echo '>Thai</option>';
    echo '<option value="uk"'; selected('uk',$language); echo '>Ukraninan</option>';
    echo '<option value="vi"'; selected('vi',$language); echo '>Vietnamese</option>';
    echo '</select><label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('background') . '">' . __('Header Background URL:') . ' <input style="width: 200px;" id="' . $this->get_field_id('background') . '" name="' . $this->get_field_name('background') . '" type="text" value="' . $background . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('css') . '">' . __('URL for External Stylesheet:') . ' <input style="width: 200px;" id="' . $this->get_field_id('css') . '" name="' . $this->get_field_name('css') . '" type="text" value="' . $css . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('js_filter') . '">' . __('URL for External Javascript Filter(if set to "true", no quotes, enables PG-13 profanity filter):') . ' <input style="width: 200px;" id="' . $this->get_field_id('js_filter') . '" name="' . $this->get_field_name('js_filter') . '" type="text" value="' . $js_filter . '" /></label></p>';

    echo '<p style="text-align:right;"><label for="' . $this->get_field_name('rate') . '">' . __('Scroll Rate(secs delay between new items):') . ' <input style="width: 50px;" id="' . $this->get_field_id('rate') . '" name="' . $this->get_field_name('rate') . '" type="text" value="' . $rate . '" /></label></p>';

    echo '<p style="text-align:right;margin-right:40px;"><label for="' . $this->get_field_name('archive') . '">' .__('Use archive?') . ' <input class="checkbox" type="checkbox"'; checked( $archive, 'on' ); echo ' id="'. $this->get_field_name('archive') .'" name="'. $this->get_field_name('archive'). '" /></label></p>';

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
