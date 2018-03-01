<?php
/*
 * Class : GPSocialStream
 * Package: Genesis Pro
 * Description: Includes all the shocial stream settings
 * Author: Swapnil Ghone
 * Since: 28-March-2016
 * Last Modified: 28-March-2016
 */

class GPSocialStream extends GPCommon {

    var $gp_social_stream_fb_setting,$gp_social_stream_twitter_setting,$gp_social_stream_gplus_setting;

    function __construct() {

        $this->gp_social_stream_fb_setting = parent::get_gp_meta('gp_social_stream_fb_setting', true);
        $this->gp_social_stream_twitter_setting = parent::get_gp_meta('gp_social_stream_twitter_setting', true);
        $this->gp_social_stream_gplus_setting = parent::get_gp_meta('gp_social_stream_gplus_setting', true);


        add_action( 'wp_ajax_wsi_facebookfeeds', array( $this, 'wsi_facebookfeeds' ) ); 
        add_action( 'wp_ajax_nopriv_wsi_facebookfeeds', array( $this, 'wsi_facebookfeeds' ) );

        //ajax for twitter
        add_action( 'wp_ajax_wsi_twitterfeeds', array( $this, 'wsi_twitterfeeds' ) ); 
        add_action( 'wp_ajax_nopriv_wsi_twitterfeeds', array( $this, 'wsi_twitterfeeds' ) );
       
        /*
         * Shortcode for social Stream
         */
        add_shortcode( 'wsi-fb-stream-feeds', array($this, 'wsi_fb_stream_feeds_callback') );

        add_shortcode( 'wsi-twitter-stream-feeds', array($this, 'wsi_twitter_stream_feeds_callback') );

        add_shortcode( 'wsi-gplus-stream-feeds', array($this, 'wsi_gplus_stream_feeds_callback') );
        
    }
   
    public function wsi_fb_stream_feeds_callback( $atts ) {
                
                $atts = $this->gp_social_stream_fb_setting;
              // _pre($atts);
                
                $atts = shortcode_atts( array(
                        'facebook_app_id' => '',
                        'facebook_app_secret_id' => '',
                        'facebook_id' => '',
                        'intro_text' => 'Posted',
                        'image_width' => 6,
                        'stream_type' => 'limit',
                        'stream_count' => 10,
                        'height' => 500,
                        'order' => 'date',
                        'control' => 0,
                        'intro' => 1,
                        'thumb' => 0,
                        'title' => 0,
                        'text' => 0,
                        'user' => 0,
                        'shared' => 0,
                ), $atts,'wsi-fb-stream-feeds' );
                
                $opt = array();
                if($atts['intro']) $opt[] = 'intro';
                if($atts['title']) $opt[] = 'title';
                if($atts['text']) $opt[] = 'text';
                if($atts['user']) $opt[] = 'user';
                if($atts['shared']) $opt[] = 'share';
                if($atts['thumb']) $opt[] = 'thumb';
//                _pre($opt);

                if($atts['stream_type'] == 'days'){
                        $wsi_feeds_facebook_limit = 100;
                        $wsi_feeds_facebook_days = $atts['stream_count'];
                }else{
                        $wsi_feeds_facebook_limit = $atts['stream_count'];
                        $wsi_feeds_facebook_days = $atts['stream_count'];
                }

                
                $config = '{feeds: {facebook: {url:"'.GENESIS_PRO_CLASSES_URL.'social/inc/GPFacebookAPI.php?1='.$atts['facebook_app_id'].'&2='.$this->base64url_encode($atts['facebook_app_secret_id']).'",id: "'.$atts['facebook_id'].'",intro: "'.$atts['intro_text'].'" ,comments: 3,image_width: '.$atts['image_width'].',out: "'. implode(",", $opt).'"}},remove:"",rotate: {delay: 6000, direction: "up"},controls : "'.$atts['control'].'",max: "'.$atts['stream_type'].'",limit : "'.$wsi_feeds_facebook_limit.'",days : "'.$wsi_feeds_facebook_days.'",order : "'.$atts['order'].'", height : "'.$atts['height'].'",wall: false,container: "gp",cstream: "stream",content: "gp-content",imagePath: "'.GENESIS_PRO_IMAGES_URL.'social_stream/",iconPath: "'.GENESIS_PRO_IMAGES_URL.'social_stream/"}';
                         
               // echo $config;
//                $config = '{feeds: {twitter: {url: "http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/inc/dcwp_twitter.php?1=sl18VQXDWWqUxNSF85RB4Htdf&2=bdkgs9yIreTZO8rg5FaGWhpFDttSUmP0wYzgnnyOIaXHA5ZleI&3=2910650166-v6r8Dq3jcehQUzRdffJZbiikSbQdnGslVklkHsm&4=RSIR66rkcFyPesnfdmPrF0hJBfo3YxwOsjZj72hn9UQPQ",id: "swapnilghone9",intro: "Tweeted",search: "Tweeted",images: "",thumb: true,retweets: true,replies: true,out: "intro,thumb,text,share"},facebook: {id: "364766893582899,186096611425868,264313733648503,314522722030040",intro: "Posted",comments: 3,image_width: 6,out: "intro,title,text,user,share",text: "contentSnippet"}},remove:"",days: 10,limit: 100,speed: 600,rotate: {delay: 6000, direction: "up"},container: "dcwss",cstream: "stream",content: "dcwss-content",imagePath: "http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/images/dcwss-light-1/",iconPath: "http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/images/dcwss-dark/"};if(!jQuery().dcSocialStream) { $.getScript("http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/js/jquery.social.stream.1.5.4.min.js", function(){$("#social-stream-358").dcSocialStream(config);}); } else {$("#social-stream-358.dc-feed").dcSocialStream(config);}}';
//                $config ='{feeds: {facebook: {id: "364766893582899,186096611425868,264313733648503,314522722030040",intro: "Posted",comments: 3,image_width: 6,out: "intro,title,text,user,share",text: "contentSnippet"}},remove:"",rotate: {delay: 0, direction: ""},wall: false,container: "dcwss",cstream: "stream",content: "dcwss-content",imagePath: "http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/images/dcwss-dark/",iconPath: "http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/images/dcwss-dark/"}';
                $out .='<script type="text/javascript">jQuery(document).ready(function($){';
                $out .= 'var config = '.$config.';';
                $out .= 'if(!jQuery().dcSocialStream) { $.getScript("'.GENESIS_PRO_JS_URL.'wsiSocialScript.min.js", function(){$("#wsis-social-stream-fb").dcSocialStream(config);}); } else {';
                $out .= '$("#wsis-social-stream-fb").dcSocialStream(config);}});</script>'."\n";
                $out .= '<div id="wsis-social-stream-fb" class="gp-facebook-feeds gp-feeds"></div>';

                return $out;
	}	
        
        public function wsi_twitter_stream_feeds_callback($atts){
            
            $atts = $this->gp_social_stream_twitter_setting;

            $atts = shortcode_atts( array(
                        'consumer_key' => '',
                        'consumer_secret' => '',
                        'access_token' => '',
                        'access_secret' => '',
                        'id' => '',
                        'intro_text' => 'tweeted',
                        'search_text' => '',
                        'images' => 'thumb',
                        'retweets' => 0,
                        'replies' => 0,
                        'stream' => 'limit',
                        'stream_count' => 10,
                        'height' => 500,
                        'order' => 'date',
                        'controls' => 0,
                        'intro' => 1,
                        'thumb' => 0,
                        'text' => 0,
                        'shared' => 0,
            ), $atts,'wsi-twitter-stream-feeds' );
             
            $opt = array();
            if($atts['intro']) $opt[] = 'intro';
            if($atts['thumb']) $opt[] = 'thumb';
            if($atts['text']) $opt[] = 'text';
            if($atts['shared']) $opt[] = 'share';

             if($atts['stream'] == 'days'){
                    $wsi_feeds_twitter_limit = 100;
                    $wsi_feeds_twitter_days = $atts['stream_count'];
            }else{
                    $wsi_feeds_twitter_limit = $atts['stream_count'];
                    $wsi_feeds_twitter_days = $atts['stream_count'];
            }
             
            $config = ' {feeds: {twitter: {url: "'.GENESIS_PRO_CLASSES_DIR.'social/inc/GPTwitterAPI.php?1='.$atts['consumer_key'].'&2='.$atts['consumer_secret'].'&3='.$atts['access_token'].'&4='.$atts['access_secret'].'",id: "'.$atts['id'].'",intro: "'.$atts['intro_text'].'",search: "'.$atts['search_text'].'",images: "'.$atts['images'].'",thumb: true,retweets: '.$atts['retweets'].',replies: '.$atts['replies'].',out: "'. implode(",", $opt) .'"}},remove:"",controls : "'.$atts['controls'].'",max : "'.$atts['stream'].'" , days: "'.$wsi_feeds_twitter_days.'",limit: "'.$wsi_feeds_twitter_limit.'",order : "'.$atts['order'].'", height : "'.$atts['height'].'" ,speed: 600,rotate: {delay: 6000, direction: "up"},container: "gp",cstream: "stream",content: "gp-content",imagePath: "'.GENESIS_PRO_IMAGES_URL.'social_stream/",iconPath: "'.GENESIS_PRO_IMAGES_URL.'social_stream/"}';
          //  echo $config;

            $out .='<script type="text/javascript">jQuery(document).ready(function($){';
                $out .= 'var config = '.$config.';';
                $out .= 'if(!jQuery().dcSocialStream) { $.getScript("'.GENESIS_PRO_JS_URL.'wsiSocialScript.min.js", function(){$("#wsis-social-stream-twitter").dcSocialStream(config);}); } else {';
                $out .= '$("#wsis-social-stream-twitter").dcSocialStream(config);}});</script>'."\n";
                $out .= '<div id="wsis-social-stream-twitter" class="gp-twitter-feeds gp-feeds"></div>';

                return $out;
        }
        
        
        public function wsi_gplus_stream_feeds_callback($atts){
            
            $atts = $this->gp_social_stream_gplus_setting;
            
            $atts = shortcode_atts( array(
                        'page_id' => '',
                        'intro_text' => 'Shared',
                        'api_key' => '',
                        'stream' => 'limit',
                        'stream_count' => 10,
                        'height' => 500,
                        'order' => 'date',
                        'controls' => 0,
                        'intro' => 1,
                        'thumb' => 0,
                        'title' => 0,
                        'text' => 0,
                        'shared' => 0,
            ), $atts,'wsi-gplus-stream-feeds' );
             
            $opt = array();
            if($atts['intro']) $opt[] = 'intro';
            if($atts['thumb']) $opt[] = 'thumb';
            if($atts['title']) $opt[] = 'title';
            if($atts['text']) $opt[] = 'text';
            if($atts['shared']) $opt[] = 'share';

            if($atts['stream'] == 'days'){
                    $wsi_feeds_google_limit = 100;
                    $wsi_feeds_google_days = $atts['stream_count'];
            }else{
                    $wsi_feeds_google_limit = $atts['stream_count'];
                    $wsi_feeds_google_days = $atts['stream_count'];
            }
//            $config ='{feeds: {
//    google: {id: "112313362976539022740",intro: "Shared",out: "intro,thumb,title,text,share",api_key: "AIzaSyB2Mj64_2ASCLd6NdyVNSQwcjhdXh4zUMQ"}},remove:"",rotate: {delay: 0, direction: ""} ,container: "dcwss",cstream: "stream",content: "dcwss-content",imagePath: "http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/images/dcwss-light-1/",iconPath: "http://base.wsigenesis.com/wp-content/plugins/wordpress-social-stream/images/dcwss-dark/"}';
            $config = ' {feeds: {google: {id: "'.$atts['page_id'].'",intro: "'.$atts['intro_text'].'",out: "'. implode(",", $opt) .'",api_key: "'.$atts['api_key'].'"}},remove:"",controls : "'.$atts['controls'].'",max : "'.$atts['stream'].'" , days: "'.$wsi_feeds_google_days.'",limit: "'.$wsi_feeds_google_limit.'",order : "'.$atts['order'].'", height : "'.$atts['height'].'" ,speed: 600,rotate: {delay: 6000, direction: "up"},container: "gp",cstream: "stream",content: "gp-content",imagePath: "'.GENESIS_PRO_IMAGES_URL.'social_stream/",iconPath: "'.GENESIS_PRO_IMAGES_URL.'social_stream/"}';
          //  echo $config;

            $out .='<script type="text/javascript">jQuery(document).ready(function($){';
                $out .= 'var config = '.$config.';';
                $out .= 'if(!jQuery().dcSocialStream) { $.getScript("'.GENESIS_PRO_JS_URL.'wsiSocialScript.min.js", function(){$("#wsis-social-stream-google").dcSocialStream(config);}); } else {';
                $out .= '$("#wsis-social-stream-google").dcSocialStream(config);}});</script>'."\n";
                $out .= '<div id="wsis-social-stream-google" class="gp-google-feeds gp-feeds"></div>';

                return $out;
        }
                
        function wsig_ent( $text = '' ) {
		return apply_filters( 'g_ent', $text );
	}
        
        function base64url_encode($data) { 
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
        } 
        
        public function __destruct(){}

    function dc_curl_get_contents($url)
        {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
        }

        function wsi_base64url_decode($data) { 
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));} 


        function wsi_facebookfeeds()
        {
         
          // echo $_POST['feedtype'];
         // echo '<pre>';print_r($_POST['facebookdata']); DIE;
             
            if($_POST['feedtype']=="facebook"){
               $atts = $this->gp_social_stream_fb_setting;
              $app_id = isset($atts['facebook_app_id']) ? $atts['facebook_app_id'] : '';
              $app_secret = $atts['facebook_app_secret_id']!="" ? $this->wsi_base64url_decode($atts['facebook_app_secret_id']) : '';
            //$app_access_token = $app_id.'|'.$app_secret;
            $app_access_token = $app_id.'|'.$atts['facebook_app_secret_id'];
            $page_id = isset($atts['facebook_id']) ? $atts['facebook_id'] : '';
            $limit = isset($wsi_feeds_facebook_limit) ? $wsi_feeds_facebook_limit : 20;
            $limit = $limit > 250 ? 250 : $limit;
            $feed =  'feed';
            $fields = "id,message,picture,link,name,description,type,icon,created_time,from,object_id,likes,comments";
            $graphUrl = 'https://graph.facebook.com/v2.3/'.$page_id.'/'.$feed.'?key=value&access_token='.$app_access_token.'&fields='.$fields.'&limit='.$limit;
            $pageUrl = 'https://graph.facebook.com/v2.3/'.$page_id.'?key=value&access_token='.$app_access_token.'&fields=id,link,name';

            // get page details
            $pageObject = file_get_contents($pageUrl);

            if ( $pageObject === false )
            {
            $pageObject = $this->dc_curl_get_contents($pageUrl);
            }
          //   echo '<pre>';print_r($pageObject ); die;
            $pageDetails  = json_decode($pageObject);
            $pageLink = isset($pageDetails->link) ? $pageDetails->link : '';
            $pageName = isset($pageDetails->name) ? $pageDetails->name : '';

            // // get page feed
            $graphObject = file_get_contents($graphUrl);

            if ( $graphObject === false )
            {
            $graphObject = $this->dc_curl_get_contents($graphUrl);
            }

            $parsedJson  = json_decode($graphObject);
            $pagefeed = $parsedJson->data;
            $count = 0;
            $link_url = '';
            $json_decoded = array();

            $json_decoded['responseData']['feed']['link'] = "";
            if(is_array($pagefeed)) {

            foreach($pagefeed as $data)
            {
            //echo '<pre>';print_r($data); die;
            if(isset($data->message))
            {
            $message = str_replace("\n","</br>",$data->message);
            } else if(isset($data->story))
            {
            $message = $data->story;
            } else {
            $message = '';
            }

            if(isset($data->description))
            {
            $message .= ' ' . $data->description;
            }

            $link = isset($data->link) ? $data->link : '';
            $image = isset($data->picture) ? $data->picture : null;
            $type = isset($data->type) ? $data->type : '';

            if($link_url == $link){
            //  continue;
            }

            $link_url = $link;

            if($message == '' || $link == '') {
            //  continue;
            }

            if($type == 'status' && isset($data->story)) {
            continue;
            }
            if(!isset($data->object_id) && $type != 'video') {
            $pic_id = explode("_", $image); 
            if(isset($pic_id[1])){
            $data->object_id = $pic_id[1];
            }
            }

            if(isset($data->object_id)){

            if(strpos($image, 'safe_image.php') === false && is_numeric($data->object_id)) {
            $image = 'https://graph.facebook.com/'.$data->object_id.'/picture?type=normal';
            }

            }
            $json_decoded['responseStatus'] = 200;
            $json_decoded['responseData']['feed']['entries'][$count]['pageLink'] = $pageLink;
            $json_decoded['responseData']['feed']['entries'][$count]['pageName'] = $pageName;
            $json_decoded['responseData']['feed']['entries'][$count]['link'] = $link;
            $json_decoded['responseData']['feed']['entries'][$count]['content'] = $message;
            $json_decoded['responseData']['feed']['entries'][$count]['thumb'] = $image;
            $json_decoded['responseData']['feed']['entries'][$count]['publishedDate'] = date("D, d M Y H:i:s O", strtotime($data->created_time));
            $count++;
            }
            }
             header("Content-Type: application/json; charset=UTF-8");
            echo json_encode($json_decoded);die;
           }
          
        }

    //call back function for twitter feeds

    function wsi_twitterfeeds()
    {

            if($_POST['feedtype']=="twitter"){
                $atts = $this->gp_social_stream_twitter_setting;
               // echo '<pre>';print_r($atts); die;

                $atts = shortcode_atts( array(
                        'consumer_key' => '',
                        'consumer_secret' => '',
                        'access_token' => '',
                        'access_secret' => '',
                        'id' => '',
                        'intro_text' => 'tweeted',
                        'search_text' => '',
                        'images' => 'thumb',
                        'retweets' => 0,
                        'replies' => 0,
                        'stream' => 'limit',
                        'stream_count' => 10,
                        'height' => 500,
                        'order' => 'date',
                        'controls' => 0,
                        'intro' => 1,
                        'thumb' => 0,
                        'text' => 0,
                        'shared' => 0,
            ), $atts,'wsi-twitter-stream-feeds' );
             
            $opt = array();
            if($atts['intro']) $opt[] = 'intro';
            if($atts['thumb']) $opt[] = 'thumb';
            if($atts['text']) $opt[] = 'text';
            if($atts['shared']) $opt[] = 'share';

             if($atts['stream'] == 'days'){
                    $wsi_feeds_twitter_limit = 100;
                    $wsi_feeds_twitter_days = $atts['stream_count'];
            }else{
                    $wsi_feeds_twitter_limit = $atts['stream_count'];
                    $wsi_feeds_twitter_days = $atts['stream_count'];
            }


                $consumer_key = isset($atts['consumer_key']) ? $atts['consumer_key'] : '';
                $consumer_secret = isset($atts['consumer_secret']) ? $atts['consumer_secret'] : '';
                $oauth_access_token = isset($atts['access_token']) ? $atts['access_token'] : NULL;
                $oauth_access_token_secret = isset($atts['access_secret']) ? $atts['access_secret'] : NULL;
                $replies=$atts['replies'] == true ? "&exclude_replies=false" : "&exclude_replies=true";
                $include_rts=$atts['retweets'].$replies;
                $screen_name=$atts['id'];
                $rest = 'statuses/user_timeline' ;
                $params = Array('count' => $wsi_feeds_twitter_limit, 'include_rts' => $include_rts, 'exclude_replies' => false, 'screen_name' => $screen_name);
                include(GENESIS_PRO_CLASSES_DIR . '/social/inc/GPTwitterAPI.php');
                $auth = new dcwss_TwitterOAuth($consumer_key,$consumer_secret,$oauth_access_token,$oauth_access_token_secret);
                $get = $auth->get( $rest, $params );
                if( ! $get ) {
                echo 'An error occurs while reading the feed, please check your connection or settings';
                }
                if( isset( $get->errors ) ) {
                } else {
                header("Content-Type: application/json; charset=UTF-8");
                echo $get; die;
                }
            }

    }
}
