=== Simple Post Views Count ===

Tags			 : post views, page view, hits, count, view, counter
Stable tag		 : 2.65
WordPress URI	 : https://wordpress.org/plugins/simple-post-views-count/
Plugin URI		 : https://puvox.software/wordpress/
Contributors	 : puvoxsoftware,ttodua
Author			 : Puvox.software
Author URI		 : https://puvox.software/
Donate link		 : https://paypal.me/puvox
License			 : GPL-3.0
License URI		 : https://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 4.4
Tested up to	 : 5.2.1

Count how many views a single post has.

== Description ==
Count your posts' view amount, with realistic method : only after visitor spends i.e. **8** seconds (or whatever you set)  on page, only count such views.
* In admin dashboard, you can see the post views amount.
* You can also display the Views amount on the post's page too, so visitors can see it too.

**Shortcode**:
`
[post_views icon_or_phrase="Total count:" post_types="post,page"]
`

**To access programatically**: 
`
&lt;?php if (function_exists('spvc_get_viewcount'))			{ echo spvc_get_viewcount($post_id); } ?&gt;
&lt;?php if (function_exists('spvc_increase_viewcount'))	{ echo spvc_increase_viewcount($post_id); } ?&gt;
`

= Available Options =
See all available options and their description on plugin's settings page.

= Security & Efficiency =
> **Note! Puvox.Software puts maximal efforts to release plugins that:**
> • Don't add any extra load/sloweness to site.
> • Don't collect private data.
> • Are revised for security to make them free from vulnerabilities.


== Screenshots ==
1. screenshot in Dashboard
2. screenshot for visitor view


== Installation ==
A) Enter your website "Admin Dashboard > Plugins > Add New" and enter the plugin name
or
B) Download plugin from WordPress.org , Extract the zip file and upload the container folder to "wp-content/plugins/"


== Frequently Asked Questions ==
- More at <a href="https://puvox.software/software/wordpress-plugins/">our WP plugins</a> page.


== Changelog ==
= 2.20 =
* Only php >= 5.4 supported

= 1.0 =
* First release.