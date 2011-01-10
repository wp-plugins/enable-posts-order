=== Enable posts order ===
Contributors: ben.yitzhaki
Donate link: http://www.benyitzhaki.co.il
Tags: posts,managment,custom fields
Requires at least: 2.0.2
Tested up to: 3.04
Stable tag: 0.1

Gains you the ability to custom order posts, using ajax and custom fields.

== Description ==
Order your posts (similar to the generic order option for pages).
The plugins adds another column in the posts managment area named "order" that contains a simple text input that defines the posts order.
The order is dynamicly updated using ajax.
In order to show posts by this order, simply order your query_posts by meta_value_num (or meta_value for lower versions of wp) and also add "meta_key=custom_posts_order" to the query.
In example:
query_posts("orderby=meta_value_num&meta_key=custom_posts_order");
more info at http://codex.wordpress.org/Function_Reference/query_posts

== Installation ==

1. Upload `posts_ordering` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress



Hey, you can always check [my personal page](http://benyitzhaki.co.il "benyitzhaki.co.il") for updates or just to say hi .
