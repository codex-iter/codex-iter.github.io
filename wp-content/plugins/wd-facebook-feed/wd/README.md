Version: 1.0.16



Usage:

Copy and paste wd library into your plugin folder.
In your plugin main file check if library main DoradoWeb class doesn't exist, include it
	if( !class_exists("DoradoWeb") ){
		require_once(PATH_TO_YOUR_PLUGIN_DIR . '/wd/start.php');
	}

Then call dorado_web_init($options) function.
$options = array (
	"prefix" => "your_plugin_prefix",
	"wd_plugin_id" => your_plugin_id,
	"plugin_title" => "your plugin titile", 
	"plugin_wordpress_slug" => "your plugin wordpress slug", 
	"plugin_dir" => 'path to your plugin dir',
	"plugin_main_file" => 'path to your plugin main file',  
	"description" => 'your plugin description', 

   "plugin_features" => array(
		0 => array(
			"title" => "feature title 1",
			"description" => "feature 1 description",
		),
		1 => array(
			"title" => "feature title 2",
			"description" => "feature 2 description",
		),
		...          
   ),
   "user_guide" => array(
		0 => array(
			"main_title" => "user guide step 1",
			"url" => "link to step 1",
			"titles" => array(
				array(
					"title" => "step 1 sub title",
					"url" => "link to step 1 sub"
				) 
			)
		),
		...
   ),
   "overview_welcome_image" => null, 
   "video_youtube_id" => "your plugin youtube video id",  // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
   "plugin_wd_url" => "https://web-dorado.com/products/your plugin", 
   "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com/your plugin", 	 
   "plugin_wd_addons_link" => "https://web-dorado.com/products/your plugin addons", 
   "after_subscribe" => "after subsribe page", // this can be plagin overview page or set up page admin.php?page=overview_YOUR_PREFIX
   "plugin_wizard_link" => "your plugin wizard page", 
   "plugin_menu_title" => "Your plugin menu title", 
   "plugin_menu_icon" => "path to menu icon", 
   "deactivate" => true, 
   "subscribe" => true, 
   "custom_post" => false,  // if true => edit.php?post_type=contact
   "menu_capability" => "manage_options",  
   "menu_position" => null,  
);

Fully documentation of dorado_web_init options:

prefix - (type string) your plugin prefix 
wd_plugin_id - (type int) plugin id ( in web-dorado database, you use it for update functionality) 
plugin_wd_zip_name - (type string) plugin zip name (in web-dorado database, ask Armen or Sergey )	
plugin_title - type string) plugin title (
plugin_wordpress_slug - (type string) plugin slug 
plugin_dir - (type string) full file path to your plugin directory 			 	 
plugin_main_file - (type string) path to your plugin main file (__FILE__) 
description - (type string) plugin short description 
		
plugin_features - (type array) plugin top 5 features from web-dorado.com 
e.g. (for google maps plugin)
array(
	0 => array(
		"title" => __("Easy set up", "gmwd"),
		"description" => __("After installation a set-up guide will help you configure general options and get started on the dashboard. The plugin also displays tooltips in the whole admin area and settings. Moreover, you get instant live previews of changes you make in the working area, so you don’t have to save and publish maps to see the results.", "gmwd"),
	),
	1 => array(
		"title" => __("Unlimited Everything", "gmwd"),
		"description" => __("Display unlimited maps on any page or post. Same is true for markers, rectangles, circles, polygons and polylines.", "gmwd"),
	),
	....
)

user_guide - (type array) plugin user guide links from 	web-dorado.com     
e.g. (for google maps plugin)
array(
	0 => array(
		"main_title" => __("Installation Wizard/ Options Menu", "gmwd"),
		"url" => "https://web-dorado.com/wordpress-google-maps/installation-wizard-options-menu.html",
		"titles" => array(
			array(
				"title" => __("Configuring Map API Key", "gmwd"),
				"url" => "https://web-dorado.com/wordpress-google-maps/installation-wizard-options-menu/configuring-api-key.html"
			) 
		)
	),
	1 => array(
		"main_title" => __("Creating Map", "gmwd"),
		"url" => "https://web-dorado.com/wordpress-google-maps/creating-map.html",
		"titles" => array()
	),
)

video_youtube_id - (type string) if your plugin has video, video's id, else  null 
( e.g. for https://www.youtube.com/watch?v=acaexefeP7o , youtube id is the 'acaexefeP7o' ) 

plugin_wd_url - (type string) plugin page url 
(e.g. https://web-dorado.com/products/wordpress-google-maps-plugin.html) 

plugin_wd_demo_link  - (type string) plugin demo url 
plugin_wd_addons_link -	(type string) if plugin has addons , plugin addons link, else  null
plugin_wizard_link 	- (type string) if plugin has wizard,  wizard page ,   else  null
( e.g.  admin_url( 'index.php?page=gmwd_setup' )  )

plugin_menu_title - (type string) plugin wordpress backend menu title  
plugin_menu_icon - (type string) path to plugin wordpress backend menu icon  
( e.g.  GMWD_URL . '/images/icon-map-20.png'  )
deactivate - (type bool) if plugin free deactivate = true, else deactivate = false
subscribe - (type bool)  if plugin free subscribe = true, else subscribe = false
custom_post - ( type string) if plugin has not custom posts it must be top level menu slug, else toplevel menu url , e.g.  edit.php?post_type=contact
menu_capability - (type string) top level menu capability e.g. manage_options	
menu_position - (type string) top level menu position , default is null
	    
	    
	   

