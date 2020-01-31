<?php
/*
 * Plugin Name:		Simple Post Views Count
 * Description:		In Admin dasbhoard or on front-end page, see how many views each post has.
 * Text Domain:		simple-post-views-count
 * Domain Path:		/languages
 * Version:			2.65
 * WordPress URI:	https://wordpress.org/plugins/simple-post-views-count/
 * Plugin URI:		https://puvox.software/wordpress/
 * Contributors: 	puvoxsoftware,ttodua
 * Author:			Puvox.software
 * Author URI:		https://puvox.software/
 * Donate Link:		https://paypal.me/puvox
 * License:			GPL-3.0
 * License URI:		https://www.gnu.org/licenses/gpl-3.0.html
 
 * @copyright:		Puvox.software
*/

namespace SimplePostViewsCount
{
  if (!defined('ABSPATH')) exit;
  $lib_final=dirname(__DIR__)."/".($name='default_library_puvox.php');
  if( file_exists($lib_start=__DIR__."/$name") && !defined("_puvox_machine_") ) { rename($lib_start, $lib_final); } require_once($lib_final);

  class PluginClass extends \default_plugin__PuvoxSoftware
  {
	private $imageurl		= 'assets/media/views-icon.png';
	public $shortcode_name1='post_views';

	public function declare_settings()
	{
		$this->initial_static_options = 
		[
			'has_pro_version'	=>0, 
			'show_opts'			=>true, 
			'show_rating_message'=>true, 
			'display_tabs'		=>true,
			'required_role'		=>'install_plugins',
			'default_managed'	=>'network',			//network, singlesite
		];
		
		$this->initial_user_options	= 
		[
			'seconds_to_read'	=> 8,
			'show_to_visitors'	=> 1,
			'use_icon'			=> 1,
			'on_pages'			=> 1,
			'admins_count'		=> 'no',
			'post_position'		=> 'start',
			'icon_or_phrase'	=> $this->helpers->pluginURL.$this->imageurl,
		];
		foreach(get_post_types() as $each){
			$this->initial_user_options['shown_on'][$each]	= in_array($each, ['post']);
		}

		
		$this->shortcodes	=[
			$this->shortcode_name1 =>[
				'description'=>__('Output the "view" counter to visitors', 'simple-post-views-count'),
				'atts'=>[
					[ 'post_types', 				'post,page',		__('On which post-types (comma delimited list) it should be visible.', 'simple-post-views-count') ],
					[ 'icon_or_phrase', 			'Views:',			__('If counter is enabled for front-end posts, what icon/phrase should visitors see for it?', 'simple-post-views-count') ],
				]
			]
		];

	} 
	
	public function __construct_my()
	{ 
		$this->styles_output = true;
		$this->table_name	= $GLOBALS['wpdb']->prefix."post_views_count__spvc";
		$this->cookiename	= 'SimplePostViewsCount_'.sanitize_key($this->helpers->homeURL);
		
		if(is_admin())
		{
			add_action('init', 	function(){
				$this->First_Time_Install();
				$this->migration_from_old_version();
			});
		} 
		add_action('wp',				[$this, 'loader_func']);
		add_action('wp_head',			[$this, 'script_head']	);
		add_action('plugins_loaded',	[$this, 'update_views'] , 1	);
				
		//load jQuery
		add_action( 'wp_enqueue_scripts', function(){ 
			if (! wp_script_is( 'jquery', 'enqueued' ) ) {	wp_enqueue_script ( 'jquery' );	} 
			}, 44
		);
		// This functions is used for tracking when a post is viewed.
		add_filter('the_content',		[$this, 'the_content_filter'] 	);

		add_action('admin_init', 		[$this, 'columns']	);
	}

	// ============================================================================================================== //
	// ============================================================================================================== //
 

	// from:    https://github.com/tazotodua/useful-php-scripts/
	public function First_Time_Install()
	{	
		global $wpdb;
				//$bla55555 = $wpdb->get_results("SELECT SUPPORT FROM INFORMATION_SCHEMA.ENGINES WHERE ENGINE = 'InnoDB'");
				$engine = ''; //'ENGINE='. ( !empty($bla55555[0]->SUPPORT) ? 'InnoDB' : 'MyISAM'  );					
		$x= $wpdb->query( "CREATE TABLE IF NOT EXISTS `".$this->table_name."` (
			`IDD` int(11) NOT NULL AUTO_INCREMENT,
			`postid` int(11) NOT NULL,
			`views` int(11) NOT NULL,
			`ips` LONGTEXT CHARACTER SET utf8 NOT NULL DEFAULT '',
			`days` LONGTEXT NOT NULL DEFAULT '', ".
			// `mytime` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			"
			PRIMARY KEY (`IDD`),
			UNIQUE KEY `IDD` (`IDD`)
		) ".$engine." ". $wpdb->get_charset_collate() ." AUTO_INCREMENT=1;"
		);
 
		//i.e......................................CHARSET=latin1 COLLATE=utf8_general_ci;
		//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); dbDelta($command);
	}




	public function post_views($atts, $content=false)
	{
		global $post;
		$args			= $this->helpers->shortcode_atts( $this->shortcode_name1, $this->shortcodes[$this->shortcode_name1]['atts'], $atts);

		$args['icon_or_phrase'] = isset($args['icon_or_phrase'])? $args['icon_or_phrase'] : $this->helpers->plugin_URL.$this->imageurl; 
		$args['post_types']		= isset($args['post_types'])	? array_filter(explode(',',$args['post_types']) )	  : ['post']; 
		$addition = $this->styles_output ? "" : $this->inline_styles(); 

		if(!empty($post->ID) && in_array($post->post_type, $args['post_types']) )
			return $addition.$this->output_view($post->ID);
		else
			return "";
	}



	/*
//add metabox for each page/post particularly
add_action('add_meta_boxes', function(){  add_meta_box('Hide Post-View', 'Hide Post-View','myfunc555_SPVC', get_post_types(),'normal'); }, 9);
function myfunc555_SPVC($post){ 
	$disabled_globally =  !empty($this->opts['shown_on'][$post->post_type]) ?  1 : 0 ;
	if($disabled_globally){
		echo "disabled for [".$post->post_type.'s]';
	}
	else{
		if(!empty($_POST['hide_views']))	{	update_post_meta($post->ID,'hide_postviews',1);	}
		$disabled_individual = get_post_meta($post->ID,'hide_postviews',true);
		echo 'Dont show "Post Views" for this page <input type="checkbox" name="hide_views" '. checked($disabled_globally || $disabled_individual,1 , false) . ' value="1" />';
	}
}
*/











	// ==================================================  VISITOR functions  ==============================================
	
	public function loader_func() {  	global $post; 
		$this->styles_output = false;
		$singular				= is_singular();
		$this->post_id			= $singular ? $post->ID : false;
		$enabled_globally		= $singular && !empty($this->opts['shown_on'][$post->post_type]);
		$enabled_individually	= $singular && empty(get_post_meta($post->ID, 'hide_postviews', true));
		$this->show_page		= ( $enabled_globally && $enabled_individually);
		$this->allow_for_user	= $this->opts['admins_count'] || current_user_can('edit_others_posts');
	}

	public function script_head() { 
		// Check if its a single, and  user isnt an admin
		if ($this->show_page) 
		{
			$this->styles_output = true;
			echo $this->inline_styles();

			if($this->allow_for_user)
			{ ?>
			<script>
			function mInlineJsLoadFunc(){	window.setTimeout(function(){ jQuery.post({ url:"<?php echo $this->homeFOLDER;?>",  data:{ call__SPVC:'ok', pid:<?php echo $this->post_id;?> }, complete : function(e,d){ if(window.spvc_log){console.log(e.responseText);} }   });  if(window.spvc_log){console.log("sent");} }, <?php echo $this->opts['seconds_to_read'];?>000); 	}

			var exec_for_user = <?php echo ( $this->allow_for_user ? "true" :"false") ;?>;
			if (exec_for_user) { window.addEventListener ? window.addEventListener("load", mInlineJsLoadFunc,false) : window.attachEvent && window.attachEvent("onload",mInlineJsLoadFunc); }
			</script>
			<?php 
			}

		}
	}

	public function inline_styles(){
		return '<style>.spvc_views{display:inline;} .spvc_icon{display:inline;} .spvc_area{display:block; text-align:right; text-align:right; background:#dadada; float:right; clear:both;  margin:-20px 0 0 0; padding:2px 5px; font-size:14px; border-radius:5px;} </style>';
	}
	
	public function update_views(){
		if(!empty($_POST['call__SPVC'])){
			$id= (int) $_POST['pid'];
			// Check if the cookie is empty (because, although we se cookies to 1, that value can only be read after the again re-visit...that is default behaviour of cookies, unlike "session"-s). If so, the post has to be counted.
			$COOKIE_SPECIAL=$this->SetCookieForPost($id);
			if ($COOKIE_SPECIAL[$id]==1){ $this->update_counts($id);	echo 'post_view added'; 	}
			else						{ echo 'post_views not updated, just increased for user:'. $COOKIE_SPECIAL[$id];	}
			exit;
		}
	}

	public function ReadSpecialCookie($pID){
		if (!empty($_COOKIE[$this->cookiename]) && is_JSON_string($_COOKIE[$this->cookiename]))
			{ $ar = json_decode($_COOKIE[$this->cookiename], true); $ar[$pID]=!empty($ar[$pID]) ? $ar[$pID]:0; }
		else
			{ $ar = []; $ar[$pID]=0; }
		return $ar;
	}

	public function SetCookieForPost($pID){
		$ar			= $this->ReadSpecialCookie($pID); 
		$ar[$pID]	= $ar[$pID]+1;
		setcookie( $this->cookiename, json_encode($ar), time() + 9999999, $this->homeFOLDER);
		return $ar;
	}

	public function icon_src($imgUrl){
		return '<img src="'.$imgUrl.'" style="width:30px;height:20px; margin:0 5px;" alt="views" class="spvc_icon" />';
	}

	public function the_content_filter($content=false) 
	{
		if(!is_admin() && !empty($GLOBALS['wp_query']->is_main_query) )
		{
			if ( $this->show_page ) 
			{
				if(in_the_loop()){ 
					$addition	= $this->output_view($GLOBALS['post']->ID);
					$content	= $this->opts['post_position'] == 'start' ? $addition.$content : $content.$addition;
				}
			}
		}
		return $content;
	}

	public function output_view($postID){
		$src = stripos($this->opts['icon_or_phrase'], '://')!==false ? $this->icon_src($this->opts['icon_or_phrase']) : $this->opts['icon_or_phrase'];
		$phraze		= '<div class="spvc_icon">'. $src .'</div>';
		$views		= '<div class="spvc_views">'. $this->GetCounts($postID) .'</div>';
		$addition	= '<div class="spvc_area">'. $phraze . $views . '</div>';   // &olarr; &rdsh;
		return $addition;
	}


	//Get counts 
	public function GetCounts($postid){  
		if (!$postid) return -1;
		if (!empty($this->viewcache[$postid])) return $this->viewcache[$postid];
		global $wpdb;
		$res	= $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$this->table_name." WHERE postid = '%d'", $postid)); 
		$count	= !empty($res[0]->views) ? $res[0]->views : 0;
		$this->viewcache[$postid]= $count;
		return $count;
	}

	//Update counts
	public function update_counts($postid){
		return $this->update_or_insert($this->table_name, ['views'=> $this->GetCounts($postid) +1], ['postid'=>$postid] );
	}		


	//==== ADMIN COLUMN along the posts list
	public function columns(){
		foreach (get_post_types() as $e){
			// add column to post list
			add_filter('manage_'.$e.'s_columns',		function ($defaults) { $defaults['SPCV']='Views'; return $defaults; } );
			// add content to the column
			add_action('manage_'.$e.'s_custom_column',	function ($column_name, $postid) {   if( $column_name == 'SPCV' ) {  echo $this->GetCounts($postid); } }, 10, 2 );		
		}
	}


	//migration from old code
	public function migration_from_old_version()
	{
		$old_opts= get_site_option('spvcopts__SPVC');
		if($old_opts){
			global $wpdb;

			$this->opts['seconds_to_read'] = $old_opts['seconds_to_read'];
			foreach(get_post_types() as $each){
				$this->opts['shown_on'][$each]	= !empty($old_opts['hiddens'][$each]);
			}
			$this->update_opts();

			//add column
			$row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$this->table_name."' AND column_name = 'days'");
			if(empty($row)){
				$wpdb->query("ALTER TABLE ".$this->table_name." ADD days LONGTEXT NOT NULL DEFAULT ''");
			}
			add_action('wp_footer', function(){ ?><script>alert('<?php echo $this->opts['name'] .__(' > plugin has been updated with many changes, so, please check it\s options page to re-check its functionalities.', 'simple-post-views-count');?>');</script> <?php });
			delete_site_option('spvcopts__SPVC');
		}
	}


	public function opts_page_output() 
	{ 
		$this->settings_page_part("start"); 
		?>

		<style>
		p.submit { text-align:center; }
		.settingsTitle{display:none;}
		.myplugin {padding:10px;}
		zzz#mainsubmit-button{display:none;}
		.plugin-title{text-align:center;}
		#icon_or_phrase{width:100%;}
		</style>
		
		<?php if ($this->active_tab=="Options") 
		{ ?>

			<?php 
			//if form updated
			if( isset($_POST["_wpnonce"]) && check_admin_referer("nonce_".$this->plugin_slug) ) 
			{
				$this->opts = array_merge($this->opts, $this->helpers->array_map_recursive('sanitize_file_name', $_POST[ $this->plugin_slug ]) );
				$this->opts['icon_or_phrase']	= sanitize_text_field( $_POST[ $this->plugin_slug ]['icon_or_phrase'] );
				$this->update_opts(); 
			}
			?> 

			<form class="mainForm" method="post" action="">

			<table class="form-table">
				<tbody>
				<tr class="def">
					<th scope="row">
						<label for="howmany_seconds">
							<?php _e('How many seconds should the visitor spend on post, to be considered as a "page read"', 'simple-post-views-count');?>
						</label>
					</th>
					<td>
						<input type="text" id="howmany_seconds" name="<?php echo $this->plugin_slug;?>[seconds_to_read]" value="<?php echo $this->opts['seconds_to_read'];?>" />
					</td>
				</tr>
				<tr class="def">
					<th scope="row">
						<label for="">
							<?php _e('Count logged-in members (editor or above) PAGEVIEW?', 'simple-post-views-count');?>
						</label>
					</th>
					<td>
						<?php _e('No', 'simple-post-views-count');?><input type="radio"	name="<?php echo $this->plugin_slug;?>[admins_count]" value="no" <?php checked($this->opts['admins_count'], "no");?> /> 
						<?php _e('Yes', 'simple-post-views-count');?><input type="radio" name="<?php echo $this->plugin_slug;?>[admins_count]" value="yes" <?php checked($this->opts['admins_count'], "yes");?> /> 
					</td>
				</tr>
				<tr class="def">
					<th scope="row">
						<label>
							<?php _e('Show counter to visitors on following post-types', 'simple-post-views-count');?>
						</label>
					</th>
					<td>
					<?php foreach (get_post_types() as $each){ echo $each.'<input type="hidden" name="'.$this->plugin_slug.'[shown_on]['.$each.']" value="0" /><input type="checkbox" name="'.$this->plugin_slug.'[shown_on]['.$each.']" value="1" '. (!empty($this->opts['shown_on'][$each]) ? checked($this->opts['shown_on'][$each], 1 ,false) : '') .' />, '; } ?>
					</td>
				</tr>
				<tr class="def">
					<th scope="row">
						<label>
							<?php _e('If above enabled, where it should show in post', 'simple-post-views-count');?>
						</label>
					</th>
					<td>
					<?php _e('In the beggining', 'simple-post-views-count');?><input type="radio"	name="<?php echo $this->plugin_slug;?>[post_position]" value="start" <?php checked($this->opts['post_position'], 'start');?> />   <?php _e('In the end', 'simple-post-views-count');?><input type="radio"	name="<?php echo $this->plugin_slug;?>[post_position]" value="end" <?php checked($this->opts['post_position'], 'end');?> />
					</td>
				</tr>
				<tr class="def">
					<th scope="row">
						<label for="icon_or_phrase">
							<?php _e('If counter is enabled for front-end posts, what icon should visitors see for it?', 'simple-post-views-count');?>
							<br/> ( <?php _e('Default is: ', 'simple-post-views-count');?>: <?php echo $this->icon_src($this->helpers->pluginURL.$this->imageurl);?> </label> )
					</th>
					<td>
						<input type="text" class="regular-text" id="icon_or_phrase" name="<?php echo $this->plugin_slug;?>[icon_or_phrase]" value="<?php echo $this->opts['icon_or_phrase'];?>" />
					</td>
				</tr>
				</tbody>
			</table>

			<?php submit_button( false, 'button-primary', '', true, $attrib= ['id'=>'mainsubmit-button'] ); ?>
			<?php wp_nonce_field( "nonce_".$this->plugin_slug); ?>
 
			<div>
				<h3><?php _e('(Alternatives to shortcode)', 'simple-post-views-count'); ?></h3>
				<?php _e('To <b>get</b> the views count programatically : ', 'simple-post-views-count'); ?> <code>spvc_get_viewcount($post_id);</code>
				<br/><br/>
				<?php _e('To <b>increase</b> the views count programatically: ', 'simple-post-views-count'); ?> <code>spvc_increase_viewcount($post_id);</code>
			</div>

			</form>

		<?php 
		} 


		$this->settings_page_part("end");
	}





  } // End Of Class

  $GLOBALS[__NAMESPACE__] = new PluginClass();

} // End Of NameSpace




// shortcut for global use
namespace 
{
	if (!function_exists('spvc_get_viewcount'))
	{
		function spvc_get_viewcount($postid)
		{
			return $GLOBALS["SimplePostViewsCount"]->GetCounts($postid);
		}
	}

	if (!function_exists('spvc_increase_viewcount'))
	{
		function spvc_increase_viewcount($postid)
		{
			return $GLOBALS["SimplePostViewsCount"]->GetCounts($postid);
		}
	}
}


?>