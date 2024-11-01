<?php
/*
Plugin Name: Themes4WP Social Counter Widget
Plugin URI: http://themes4wp.com/
Description: Social counter widget - A beautiful widget to be used in sidebar or footer, it allows you to add your Twitter , Facebook, Google Plus and Feeds Subscription in it.
Version: 1.0
Author: Themes4WP
Author URI: http://themes4wp.com/
License: GPLv2
*/

class twp_social extends WP_Widget {

	public function __construct() {
    global $control_ops;
    
    require_once('inc/TwitterAPIExchange.php'); 
    
    load_plugin_textdomain( 'twp-social', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	  add_action('wp_enqueue_scripts', array(&$this, 'twp_social_enque_styles'));
    	 
		$widget_ops = array('classname' => 'twp_social', 'description' => __(' Add Twitter, Facebook, G+ and Feeds in Sidebar Widget By themes4wp.com'), 'version' =>'1.0.0',);
		parent::__construct('twp_social', __('Themes4WP Social Counter Widget'), $widget_ops, $control_ops);	
	}

		
	
	function widget( $args, $instance ) {
    $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		$facebook_page = $instance['facebook'];
		$facebook_key = $instance['facebook_key'];
		$facebook_secret = $instance['facebook_secret'];
		$rss_id = $instance['rss'] ;
		$twitter_id =  $instance['twitter'];
		$gpluspage = $instance['gpluspage'];
		$gpluspage_key = $instance['gpluspage_key'];
		$author_credit_opt = $instance['author_credit'];
    $google_like = $instance['google_like'];
    $facebook_like = $instance['facebook_like'];
    $post_count = $instance['post_count'];
    $comments_count = $instance['comments_count'];  
		$consumer_key = $instance['consumer_key'];
		$consumer_secret = $instance['consumer_secret'];
		$access_token = $instance['access_token'];
		$access_token_secret = $instance['access_token_secret'];
		$layout = $instance['layout'];
		$item_counter = 0;
		if ($twitter_id && $consumer_key && $consumer_secret && $access_token && $access_token_secret) $item_counter++;
		if ($rss_id) $item_counter++; 
		if ($gpluspage && $gpluspage_key) $item_counter++; 
		if ($google_like) $item_counter++; 
		if ($facebook_like) $item_counter++; 
		if ($post_count) $item_counter++; 
		if ($comments_count) $item_counter++; 
		if ($facebook_page && $facebook_key && $facebook_secret) $item_counter++; 
		
    global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));
 
		
    echo $args['before_widget'];
    if ( ! empty( $title ) ) {
			echo $args['before_title'];
			echo esc_html( $title );
			echo $args['after_title'];
		}
		?>
		<div class="twp-social-widget layout-<?php echo $layout; ?>">
			<ul>
			<?php if( $rss_id ): ?>
				<li class="rss-subscribers item-<?php echo $item_counter; ?>">
					<a href="<?php echo esc_url($rss_id) ?>" target="_blank">
						<span><?php _e('Subscribe' , 'twp-social' ) ?><?php __('Subscribers' , 'twp-social' ) ?></span>
						<small><?php _e('To RSS Feed' , 'twp-social' ) ?></small>
					</a>
				</li>
			<?php endif; ?>
			<?php if( $gpluspage && $gpluspage_key ): ?>
			  <li class="google-fans item-<?php echo $item_counter; ?>">
          <a href="<?php echo esc_url('https://plus.google.com/'.$gpluspage) ?>" target="_blank">					
            <span><?php echo twp_social_google_plus_counts($gpluspage, $gpluspage_key ) ?></span>
						<small><?php _e('Followers' , 'twp-social' ) ?></small>
				  </a>
				</li>
			<?php endif; ?>
      <?php if( $facebook_page && $facebook_key && $facebook_secret ): ?>
				<li class="facebook-fans item-<?php echo $item_counter; ?>">
          <a href="<?php echo esc_url($facebook_page) ?>" target="_blank">					
            <span><?php echo twp_social_facebook_like_counts( $facebook_page, $facebook_key, $facebook_secret ) ?></span>
						<small><?php _e('Fans' , 'twp-social' ) ?></small>
				  </a>
				</li>
			<?php endif; ?>
			<?php if( $twitter_id && $consumer_key && $consumer_secret && $access_token && $access_token_secret ): ?>
      	<li class="twitter-followers item-<?php echo $item_counter; ?>">
          <a href="<?php echo esc_url('https://twitter.com/'.$twitter_id) ?>" target="_blank">
            <span><?php echo twp_social_tweet_counts( $twitter_id, $consumer_key, $consumer_secret, $access_token, $access_token_secret ); ?></span>
						<small><?php _e('Followers' , 'twp-social' ) ?></small>
			    </a>
				</li>
			<?php endif; ?>
		
			<?php if( $google_like ): ?> 
        <li class="google-likes item-<?php echo $item_counter; ?>">
          <div class="g-plusone" data-size="tall"></div> 
			  </li>
			<?php endif; ?>
		  <?php if( $facebook_like ): ?>	  
			  <li class="facebook-likes item-<?php echo $item_counter; ?>">
          <div class="fb-like" data-href="'.$current_url.'" data-layout="box_count" data-action="like" data-show-faces="false" data-share="false"></div>
			  </li>
			<?php endif; ?>
      <?php if( $post_count ): ?>  
			  <li class="post-count item-<?php echo $item_counter; ?>">
          <a href="#">
            <span><?php echo wp_count_posts()->publish; ?></span>
						<small><?php _e('Posts' , 'twp-social' ) ?></small>
			    </a>
				</li>
			<?php endif; ?>
      <?php if( $comments_count ): ?>	
				<li class="comments-count item-<?php echo $item_counter; ?>">
          <a href="#">
            <span><?php echo wp_count_comments()->approved; ?></span>
						<small><?php _e('Comments' , 'twp-social' ) ?></small>
			    </a>
				</li>
			<?php endif; ?>	
      </ul>

			<?php if( $author_credit_opt ): ?>
  			<div class="author-credit">
  			  <?php printf( __( 'Plugin by %1$s', 'twp-social' ), '<a href="'.esc_url("http://themes4wp.com").'" target="_blank">Themes4WP</a>' ); ?>
        </div>
			<?php endif; ?>
		</div>
		
	<?php echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['facebook'] = $new_instance['facebook'];
    $instance['facebook_key'] = $new_instance['facebook_key'] ;
    $instance['facebook_secret'] = $new_instance['facebook_secret'] ;
		$instance['rss'] =  $new_instance['rss'];
		$instance['twitter'] =  $new_instance['twitter'];
		$instance['gpluspage'] =  $new_instance['gpluspage'];
		$instance['gpluspage_key'] =  $new_instance['gpluspage_key'];
		$instance['consumer_key'] 			= $new_instance['consumer_key'];
		$instance['consumer_secret'] 		= $new_instance['consumer_secret'];
		$instance['access_token'] 			= $new_instance['access_token'];
		$instance['access_token_secret']	= $new_instance['access_token_secret'];
    $instance['author_credit'] = isset($new_instance['author_credit']) ? 1 : 0 ;
    $instance['google_like'] = isset($new_instance['google_like']) ? 1 : 0 ;
    $instance['facebook_like'] = isset($new_instance['facebook_like']) ? 1 : 0 ;
    $instance['post_count'] = isset($new_instance['post_count']) ? 1 : 0 ;
    $instance['comments_count'] = isset($new_instance['comments_count']) ? 1 : 0 ;
    $instance['layout']	= $new_instance['layout']; 
		return $instance;
	}

	function form( $instance ) { 
		$instance = wp_parse_args( (array) $instance, array( 
    'gpluspage_key' =>	'', 
    'facebook_key' =>	'', 
    'facebook_secret' =>	'', 
    'title' =>	'',
    'consumer_key' => '', 
    'consumer_secret' => '', 
    'access_token' => '', 
    'access_token_secret' => '', 
    'rss' => '', 
    'facebook' => 'https://facebook.com/themes4wp', 
    'twitter' => 'themes4_wp', 
    'gpluspage' => '+themes4wp', 
    'author_credit'=> 0,
    'google_like'=> 0,
    'facebook_like'=> 0,
    'post_count'=> 0,
    'comments_count'=> 0,
    'layout'=> 'clear',
     ) ); 
		$title 	= htmlspecialchars($instance['title']);	
    $rss = htmlspecialchars($instance['rss']);
		$facebook = htmlspecialchars($instance['facebook']);
		$facebook_key = htmlspecialchars($instance['facebook_key']);
		$facebook_secret = htmlspecialchars($instance['facebook_secret']);
		$twitter = htmlspecialchars($instance['twitter']);
		$gpluspage = htmlspecialchars($instance['gpluspage']);
    $gpluspage_key = htmlspecialchars($instance['gpluspage_key']);
		$author_credit = htmlspecialchars($instance['author_credit']);
    $google_like = htmlspecialchars($instance['google_like']);
    $facebook_like = htmlspecialchars($instance['facebook_like']);
    $post_count = htmlspecialchars($instance['post_count']);
    $comments_count = htmlspecialchars($instance['comments_count']);
    $consumer_key = htmlspecialchars($instance['consumer_key']);
		$consumer_secret = htmlspecialchars($instance['consumer_secret']);
		$access_token = htmlspecialchars($instance['access_token']);
		$access_token_secret = htmlspecialchars($instance['access_token_secret']);
		$layout = htmlspecialchars($instance['layout']);
		?>
    <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<h3><?php _e('RSS' , 'twp-social' ) ?></h3><hr />
		<p>
			<label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php _e('RSS Feed URL' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" value="<?php echo $instance['rss']; ?>" class="widefat" type="text" />
		</p>
		<h3><?php _e('Facebook' , 'twp-social' ) ?></h3><hr />
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e('Facebook Page URL' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php echo $instance['facebook']; ?>" class="widefat" type="text" />
			<small><?php _e('Link must be like https://www.facebook.com/username/ or https://www.facebook.com/PageID/' , 'twp-social' ) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook_key' ); ?>"><?php _e('Facebook App ID' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'facebook_key' ); ?>" name="<?php echo $this->get_field_name( 'facebook_key' ); ?>" value="<?php echo $instance['facebook_key']; ?>" class="widefat" type="text" />
		   <small><?php printf( __( 'Create an app on Facebook through this link %1$s.', 'twp-social' ), '<a href="'.esc_url("https://developers.facebook.com/apps").'" target="_blank">(Facebook apps)</a>' ); ?></small>
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook_secret' ); ?>"><?php _e('Facebook App Secret' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'facebook_secret' ); ?>" name="<?php echo $this->get_field_name( 'facebook_secret' ); ?>" value="<?php echo $instance['facebook_secret']; ?>" class="widefat" type="text" />
		  <small><?php printf( __( 'Create an app on Facebook through this link %1$s.', 'twp-social' ), '<a href="'.esc_url("https://developers.facebook.com/apps").'" target="_blank">(Facebook apps)</a>' ); ?></small>
    </p>
    <h3><?php _e('Twitter' , 'twp-social' ) ?></h3><hr />
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e('Twitter Username' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="<?php echo $instance['twitter']; ?>" class="widefat" type="text" />
		  <small><?php _e('Please enter the Twitter username. For example: themes4_wp' , 'twp-social' ) ?></small>
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'consumer_key' ); ?>"><?php _e('Twitter Consumer Key' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'consumer_key' ); ?>" value="<?php echo $instance['consumer_key']; ?>" class="widefat" type="text" />
		  <small><?php printf( __( 'Create an app on Twitter through this link %1$s.', 'twp-social' ), '<a href="'.esc_url("https://dev.twitter.com/apps").'" target="_blank">(Twitter apps)</a>' ); ?></small>
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'consumer_secret' ); ?>"><?php _e('Twitter Consumer Secret' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'consumer_secret' ); ?>" value="<?php echo $instance['consumer_secret']; ?>" class="widefat" type="text" />
		  <small><?php printf( __( 'Create an app on Twitter through this link %1$s.', 'twp-social' ), '<a href="'.esc_url("https://dev.twitter.com/apps").'" target="_blank">(Twitter apps)</a>' ); ?></small>
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'access_token' ); ?>"><?php _e('Twitter Access Token' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'access_token' ); ?>" name="<?php echo $this->get_field_name( 'access_token' ); ?>" value="<?php echo $instance['access_token']; ?>" class="widefat" type="text" />
		  <small><?php printf( __( 'Create an app on Twitter through this link %1$s.', 'twp-social' ), '<a href="'.esc_url("https://dev.twitter.com/apps").'" target="_blank">(Twitter apps)</a>' ); ?></small>
    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'access_token_secret' ); ?>"><?php _e('Twitter Access Token Secret' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'access_token_secret' ); ?>" name="<?php echo $this->get_field_name( 'access_token_secret' ); ?>" value="<?php echo $instance['access_token_secret']; ?>" class="widefat" type="text" />
		  <small><?php printf( __( 'Create an app on Twitter through this link %1$s.', 'twp-social' ), '<a href="'.esc_url("https://dev.twitter.com/apps").'" target="_blank">(Twitter apps)</a>' ); ?></small>
    </p>
    <h3><?php _e('Google' , 'twp-social' ) ?></h3><hr />
    <p>
			<label for="<?php echo $this->get_field_id( 'gpluspage' ); ?>"><?php _e('Google Plus Page ID' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'gpluspage' ); ?>" name="<?php echo $this->get_field_name( 'gpluspage' ); ?>" value="<?php echo $instance['gpluspage']; ?>" class="widefat" type="text" />
		  <small><?php _e('Please enter the Google page name. For example: +Themes4WP' , 'twp-social' ) ?></small>
    </p>
    <p>
			<label for="<?php echo $this->get_field_id( 'gpluspage_key' ); ?>"><?php _e('Google Plus API Key' , 'twp-social' ) ?></label>
			<input id="<?php echo $this->get_field_id( 'gpluspage_key' ); ?>" name="<?php echo $this->get_field_name( 'gpluspage_key' ); ?>" value="<?php echo $instance['gpluspage_key']; ?>" class="widefat" type="text" />
		  <small><?php printf( __( 'Create a project on Google through this link %1$s.', 'twp-social' ), '<a href="'.esc_url("https://developers.google.com/maps/documentation/javascript/get-api-key").'" target="_blank">(Google dev)</a>' ); ?></small>
    </p>
    <h3><?php _e('Others' , 'twp-social' ) ?></h3><hr />
		<p>
			<label for="<?php echo $this->get_field_id('google_like'); ?>"><?php _e('G+ Like Button' , 'twp-social' ) ?></label>
			<input type="checkbox" <?php checked( $instance['google_like'], 1 ); ?> id="<?php echo $this->get_field_id('google_like'); ?>" name="<?php echo $this->get_field_name('google_like'); ?>" value="<?php echo $instance['google_like']; ?>" />			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('facebook_like'); ?>"><?php _e('Facebook Like Button' , 'twp-social' ) ?></label>
			<input type="checkbox" <?php checked( $instance['facebook_like'], 1 ); ?> id="<?php echo $this->get_field_id('facebook_like'); ?>" name="<?php echo $this->get_field_name('facebook_like'); ?>" value="<?php echo $instance['facebook_like']; ?>" />			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Posts Count' , 'twp-social' ) ?></label>
			<input type="checkbox" <?php checked( $instance['post_count'], 1 ); ?> id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" value="<?php echo $instance['post_count']; ?>" />			
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('comments_count'); ?>"><?php _e('Comments Count' , 'twp-social' ) ?></label>
			<input type="checkbox" <?php checked( $instance['comments_count'], 1 ); ?> id="<?php echo $this->get_field_id('comments_count'); ?>" name="<?php echo $this->get_field_name('comments_count'); ?>" value="<?php echo $instance['comments_count']; ?>" />			
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('author_credit'); ?>"><?php _e('Give credit to the plugin author?' , 'twp-social' ) ?></label>
			<input type="checkbox" <?php checked( $instance['author_credit'], 1 ); ?> id="<?php echo $this->get_field_id('author_credit'); ?>" name="<?php echo $this->get_field_name('author_credit'); ?>" value="<?php echo $instance['author_credit']; ?>" />			
		</p>
    <p>
      <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e( 'Layout', 'twp-social' );?></label> 
        <select class='widefat' id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>" type="text">
          <option value='clear'<?php echo ($layout=='clear')?'selected':''; ?>><?php _e( 'Clear', 'twp-social' );?></option>
          <option value='bordered'<?php echo ($layout=='bordered')?'selected':''; ?>><?php _e( 'Bordered', 'twp-social' );?></option> 
          <option value='bordered-shadow'<?php echo ($layout=='bordered-shadow')?'selected':''; ?>><?php _e( 'Bordered with shadow', 'twp-social' );?></option>
          <option value='bordered-rounded'<?php echo ($layout=='bordered-rounded')?'selected':''; ?>><?php _e( 'Rounded (bordered)', 'twp-social' );?></option>
          <option value='bordered-rounded-shadow'<?php echo ($layout=='bordered-rounded-shadow')?'selected':''; ?>><?php _e( 'Rounded (bordered) with shadow', 'twp-social' );?></option> 
        </select>                
    </p>
			<?php
	}	
  function twp_social_enque_styles() {
		
		wp_register_style('twp_styles', plugins_url('/css/style.css', __FILE__), array(), $this->widget_options['version'], 'all');
		wp_enqueue_style('twp_styles');
	}		

}
function twp_social() {
		register_widget('twp_social');
	}	
add_action('widgets_init', 'twp_social');

function twp_social_facebook_like_counts($facebook_page, $facebook_key, $facebook_secret ){		
		//Construct a Facebook URL
  	$url 		= str_replace('https://www.facebook.com/', '', $facebook_page);
    $json_url ='https://graph.facebook.com/v2.5/'.$facebook_page.'?access_token='.$facebook_key.'|'.$facebook_secret.'&fields=likes';
  	$json = file_get_contents($json_url);
  	$json_output = json_decode($json);
   
  	//Extract the likes count from the JSON object
  	if($json_output->likes){
  		return $likes = $json_output->likes;
  	}else{
  		return 0;
  	}

}

function twp_social_google_plus_counts($gpluspage, $gpluspage_key) {
    if($gpluspage && $gpluspage_key) { 
  		$gUrl = "https://www.googleapis.com/plus/v1/people/".$gpluspage."?key=".$gpluspage_key;            
      $count = 0; 
      $response = file_get_contents($gUrl);           
      $fb = json_decode($response);
      if ( isset( $fb->circledByCount)) {              
                  $count = intval($fb->circledByCount);                    
      }                   
      return $count ;   
    }
}
	
function twp_social_tweet_counts($twitter_id, $consumer_key, $consumer_secret, $access_token, $access_token_secret ){
		$social_counter_settings = array(      
            'twitter_user' => $twitter_id,
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'oauth_access_token' => $access_token,
            'oauth_access_token_secret' => $access_token_secret,
        );
    $count = 0;
    $settings = $social_counter_settings;
      $apiUrl = "https://api.twitter.com/1.1/users/show.json";
        $requestMethod = 'GET';
        $getField = '?screen_name=' .  $settings['twitter_user']; 
        $twitter = new TwitterAPIExchange($settings);
        $response = $twitter->setGetfield($getField)->buildOauth($apiUrl, $requestMethod)->performRequest(); 
        $followers = json_decode($response);
        $count = $followers->followers_count;
    return $count ;
}
function twp_social_social_js() { 
  ?>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
          <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, "script", "facebook-jssdk"));</script>
  <?php
}
// Add hook for front-end <head></head>
add_action('wp_head', 'twp_social_social_js');



