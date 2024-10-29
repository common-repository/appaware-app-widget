<?php

/*
Plugin Name: Playboard Android App Widget
Plugin URI: http://playboard.me/widgets
Description: The Playboard Android App Widget (formerly AppAware App Widget) allows to easily add Android App details to blog-posts through a minimalistic and easy-to-use HTML widget. The app information is always up-to-date with Google Play. Perfect for roundups - see how AndroidPolice is using it (http://www.androidpolice.com/2013/10/24/bonus-round-random-heroes-arena-alpha-hello-hero-and-strategy-tactics-ussr-vs-usa/ ) 
Version: 3.5.1
Author: Playboard
Author URI: http://playboard.me
*/

class PlayboardAppShortcode {

    static $add_aa_script;
    static $add_pb_script;

    static function init() {
        add_shortcode('appaware-app', array(__CLASS__, 'add_appaware_app'));
        add_shortcode('pb-app-box', array(__CLASS__, 'add_pb_app_box'));
        add_shortcode('pb-app-list', array(__CLASS__, 'add_pb_app_list'));
        add_action('admin_menu', array(__CLASS__, 'pb_options_box'));
        add_action( 'admin_enqueue_scripts',  array(__CLASS__, 'pb_options_box_ajax'));

        add_action('init', array(__CLASS__, 'register_script'));
        add_action('wp_footer', array(__CLASS__, 'print_script'));
    }

    static function pb_options_box_ajax($hook) {

        wp_enqueue_script( 'ajax-script-pb-app-box', plugins_url( '/js/pb_options_box_ajax.js', __FILE__ ), array('jquery'));

    }

    static function add_appaware_app($atts) {

        self::$add_aa_script = true;

        extract(shortcode_atts(array('pname' => '', 'qrcode' => 'true', 'users' => '0', 'name' => ''), $atts));

        if ($qrcode == 'false'){
            $qrcode_text = '';
        }else{
            $qrcode_text = 'data-qrcode="true"';
        }

        $users_text = '';
        if (intval($users) > 0){
            $users_text = 'data-users="' . $users . '"';
        }

        if ($name == ''){
            $name = 'Android App ' . $pname . ' on Playboard';
        }else{
            $name .= ' on Playboard';
        }

        if ($pname != ''){
            return '<div class="appaware-app" ' . $qrcode_text . ' ' . $users_text .'><a href="http://playboard.me/android/apps/' . $pname . '">' . $name . '</a></div>';
        }else{
            return "";
        }
    }

    static function add_pb_app_list($atts) {

        self::$add_pb_script = true;

        extract(shortcode_atts(array('pnames' => '', 'lang' => 'en', 'title' => '', 'author' => '', 'width' => 'auto'), $atts));

        if ($lang != ''){
            $lang_text = 'data-lang="' . $lang . '"';
        }else{
            $lang_text = '';
        }

        if ($title != ''){
            $title_text = 'data-list-title="' . $title . '"';
        }else{
            $title_text = '';
        }

        if ($author != ''){
            $author_text = 'data-list-author="' . $author . '"';
        }else{
            $author_text = '';
        }

        if (!is_numeric($width)){
            $width_text = "";
        }else{
            $width_text = "style='width: " . $width . "px !important'";
        }

        $pnames_array = explode(',',$pnames);
        $html = '<div class="pb-app-box" ' . $width_text . ' ' . $lang_text . ' ' . $title_text . ' ' . $author_text . '>';
        foreach ($pnames_array as $p){
            $p = trim($p);
            $html .= '<a href="http://playboard.me/android/apps/' . $p . '">' . 'Android App ' . $p . ' on Playboard' . '</a>';
        }
        $html .= '</div>';

        return $html;
    }

    static function add_pb_app_box($atts) {

        self::$add_pb_script = true;

        extract(shortcode_atts(array('pname' => '', 'theme' => 'discover', 'lang' => 'en', 'name' => ''), $atts));


        if ($theme != ''){
            $theme_text = 'data-theme="' . $theme . '"';
        }else{
            $theme_text = '';
        }

        if ($lang != ''){
            $lang_text = 'data-lang="' . $lang . '"';
        }else{
            $lang_text = '';
        }

        if ($name == ''){
            $name = $pname;
        }

        if ($pname != ''){
            return '<div class="pb-app-box" ' . $theme_text . ' ' . $lang_text .'><a href="http://playboard.me/android/apps/' . $pname . '">' . $name . ' (Playboard)</a> | <a href="https://play.google.com/store/apps/details?id=' . $pname . '&hl=' . $lang . '&referrer=utm_source%3DPlayboard%26utm_medium%3DWidgetWeb%26utm_campaign%3DPlayboard" target="_blank" rel="nofollow">' . $name . ' (Play Store)</a></div>';
        }else{
            return "";
        }
    }



    static function pb_options_box() {
        add_meta_box('pb_options_box', 'Playboard App Widget', array(__CLASS__, 'pb_options_box_display'), 'post', 'side', 'high');

    }



    static function pb_options_box_display() {

        $example_pn = 'Whatsapp Messenger';
        $example = "[pb-app-box pname='com.whatsapp' name='WhatsApp' theme='discover' lang='en']";

        echo '<p><label><strong>How to use the Playboard App Widget:</strong></label><br>
        <hr>

         <ul>
           <li>
               <label for="package_name">1. Give a package name or app name to generate the shortcode:</label>
               <div>
               <input id="pb_app_box_example_pn" type="text" name="package_name" value="' . $example_pn . '" style="width:100%"></input>
                <label for="pb_app_box_themes_select">2. Choose a theme&nbsp;&nbsp;&nbsp;&nbsp;</label>
               <select id="pb_app_box_themes_select" name="pb_app_box_themes_select" style="width:50%">
                  <option value="discover">Discover</option>
                  <option value="light">Light</option>
                  <option value="dark">Dark</option>
                </select><br/>
               <label for="pb_app_box_themes_select" >3. Choose language&nbsp;</label>
               <select id="pb_app_box_lang_select" name="pb_app_box_lang_select" style="width:50%">
                  <option value="en">English</option>
                  <option value="de">German</option>
                  <option value="zh">Chinese</option>
                  <option value="it">Italian</option>
                  <option value="es">Spanish</option>
                  <option value="fr">French</option>
                  <option value="ar">Arabic</option>
                  <option value="gr">Greek</option>
                  <option value="pl">Polish</option>
                  <option value="ku">Kurdish</option>
                </select><br/>
               4. <a href="javascript:;" class="button button-large" id="pb_app_box_generate_pn" name="ajax_get_shortcode">Generate</a>
               </div><br/>
               <label for="shortcode">5. Embed this shortcode in your post:</label>
               <textarea id="pb_app_box_shortcode_textarea" name="shortcode" style="width:100%" rows="4" disabled></textarea>
           </li>

        </ul>
        <a href="http://playboard.me/wordpress-app-widget" target="_blank">More Examples</a><br><hr>

        Need more widgets or even a custom one? Visit <a href="http://playboard.me/widgets" target="_blank">http://playboard.me/widgets</a> or contact us at <a href="mailto:feedback@playboard.me">feedback@playboard.me</a>' ;

    }

    static function register_script() {
        wp_register_script('aa-js','//appaware.com/widgets/aa.js', null, null, false);
        wp_register_script('pb-app-box-js','https://playboard.me/widgets/pb-app-box/1/pb_load_app_box_wp.js', array('jquery'), null, false);
    }

    static function print_script() {
        if (self::$add_aa_script ){
            wp_print_scripts('aa-js');
        }

        if (self::$add_pb_script ){
            wp_print_scripts('pb-app-box-js');
        }

    }


}

PlayboardAppShortcode::init();

?>