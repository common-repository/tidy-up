=== Tidy Up ===
Contributors: johnny5
Donate link: http://urbangiraffe.com/about/support/
Tags: post, page, tidy, html, xhtml, validate
Requires at least: 2.0
Tested up to: 3.2.1
Stable tag: trunk

This plugin provides the ability to run HTML Tidy through all your posts, pages, and comments, generating a report on just how dirty your code is.

== Description ==

This plugin provides the ability to run HTML Tidy through all your posts, pages, and comments, generating a report on just how dirty your code is. Should you want to, the plugin can also automatically update your database with the cleansed data.

If you are unaware of it's existence, HTML Tidy is a wonderful little tool that is embedded into almost everything nowadays. It's purpose is to take potentially malformed HTML code and produce clean XHTML.

Tidy Up does not require any special PHP configuration. As long as you have the ability to run executables then the plugin will work. Currently the plugin contains Tidy executables for:

* Linux
* Windows
* Mac OS X
* FreeBSD

It is likely that your web host runs one of these.
== Installation ==

The plugin is simple to install:

1. Download `tidy-up.zip`
1. Unzip
1. Upload `audit-trail` directory to your `/wp-content/plugins` directory
1. Go to the plugin management page and enable the plugin
1. Configure the plugin from `Management/Tidy Up`

You can find full details of installing a plugin on the [plugin installation page](http://urbangiraffe.com/articles/how-to-install-a-wordpress-plugin/).

== Screenshots ==

1. Tidy options
2. Tidy report

== Documentation ==

Full documentation can be found on the [Tidy Up Page](http://urbangiraffe.com/plugins/tidy-up/) page.

== Changelog ==

= 1.0 = 
* Initial release

= 1.1 = 
* Fix CSS path
* Add option for tidying excerpt
* Remember type
* Aadd view/edit icons
* AJAX support

= 1.2 = 
* Use database prefix and wpurl
* Allow tidying of drafts

= 1.3 = 
* WP 2.8 compatibility


