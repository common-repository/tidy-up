<?php
/*
Plugin Name: Tidy Up
Plugin URI: http://urbangiraffe.com/plugins/tidy-up/
Description: Runs an HTML Tidy on all posts and comments.  See Manage screen for usage.
Author: John Godley
Version: 1.3
Author URI: http://urbangiraffe.com/
*/

define ('TIDY_ADMIN_LEVEL', 'edit_plugins');

function tidy_up_admin ()
{
	if (isset ($_GET['post']) && current_user_can (TIDY_ADMIN_LEVEL))
		tidy_show_post (intval ($_GET['post']));
	else
		tidy_show_all ();
}

function tidy_show_post ($post)
{
	include ('tidy_class.php');

	$save = false;
	if (isset ($_POST['clean']) && current_user_can (TIDY_ADMIN_LEVEL))
	{
		$input  = $_POST['input'];
		$output = $_POST['output'];

		update_option ('tidy_input', $input);
		update_option ('tidy_output', $output);
		$save = true;
	}

	$input = get_option ('tidy_input');
	$output = get_option ('tidy_output');

	if (!$input)
		$input = 'wordpress';

	if (!$output)
		$output = 'wordpress';

	$returnpage = get_bloginfo ('wpurl').'/wp-admin/edit.php';
	if ($save)
	{
		?>
		<div class="fade updated" id="message">
		  <p>Post has been cleaned and updated</p>
		</div>
		<?php
	}
	?>
	<div class="wrap">
	  <h2>Report for post <?php echo $post ?></h2>

	<?php if ($post > 0) : ?>
		<ul class="report"><?php tidy_post ($post, $save, $input, $output); ?>
	<?php else : ?>
		<p>Invalid post</p>
	<?php endif; ?>
		<a href="<?php echo $returnpage ?>">Return to manage page</a>
	</div>

	<div class="wrap">
	  <h2>Clean and update post <?php echo $post ?></h2>
		<p>Note that use of this option can seriously affect the health of your blog.  Please be aware of what you are doing, and <strong>backup your database</strong> before saving.  Absolutley no liability is taken for any damage caused.</strong></p>
		<ul>
		  <li>Default WordPress - Let the default WordPress auto-formatting routines handle the data.</li>
		  <li>Raw XHTML - Perform no additional WordPress auto-formatting.</li>
		</ul>

		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']?>">
		<table>
			<tr>
				<td>Input:</td>
				<td>
					<select name="input">
				  	<option value="wordpress"<?php if (get_option ('tidy_input') == 'wordpress') :?>selected="selected"<?php endif; ?>>Default WordPress</option>
				    <option value="xhtml"<?php if (get_option ('tidy_input') == 'xhtml') :?>selected="selected"<?php endif; ?>>Raw XHTML</option>
				  </select> Output:   <select name="output">
					    <option value="wordpress"<?php if (get_option ('tidy_output') == 'wordpress') :?>selected="selected"<?php endif; ?>>Default WordPress</option>
					    <option value="xhtml"<?php if (get_option ('tidy_output') == 'xhtml') :?>selected="selected"<?php endif; ?>>Raw XHTML</option>
					  </select>
				</td>
			</tr>
			<tr>
			  <td></td>
				<td><input type="submit" name="clean" value="<?php echo __('Clean', 'tidy_up')?> &raquo;" /></td>
			</tr>
		</table>

		</form>
	</div>
	<?php
}

function tidy_show_all ()
{
	if (current_user_can (TIDY_ADMIN_LEVEL) && isset( $_POST['input'] ) ) {
		$input  = $_POST['input'];
		$output = $_POST['output'];

		update_option ('tidy_input', $input);
		update_option ('tidy_output', $output);
	}

	$offset = 0;
	if ( isset( $_POST['offset'] ) )
		$offset = intval( $_POST['offset'] );

	$max = 1000;
	if ( isset( $_POST['maximum'] ) )
		$max = intval( $_POST['maximum'] );

	$current = 'posts';
	if ( isset( $_POST['type'] ) && in_array( $_POST['type'], array( 'posts', 'pages', 'comments', 'excerpts' ) ) )
		$current = $_POST['type'];
?>

<div class="wrap">
  <h2>Tidy Up</h2>
	<p>Tidy Up will run <a href="http://tidy.sourceforge.net/">HTML Tidy</a> through all your posts or comments and produce a report.  You can use this to clean up your HTML, or you can have the plugin automatically update the data with cleaned XHTML from Tidy.</p>
	<p>NOTE: Cleaning your data can seriously affect the health of your blog.  Be aware of what you are doing and <strong>please do backup your database</strong>.  Absolutley no liability is taken for any damage caused.</p>

	<form method="post" action="">
	<table>
	  <tr>
	    <td>Source:</td>
	    <td>
				<select name="type">
		     <option value="posts"<?php selected( $current, 'posts' ) ?>>Posts</option>
		     <option value="pages"<?php selected( $current, 'pages' ) ?>>Pages</option>
		     <option value="comments"<?php selected( $current, 'comments' ) ?>>Comments</option>
		     <option value="excerpts"<?php selected( $current, 'excerpts' ) ?>>Excerpts</option>
			 	</select>
			</td>
		</tr>
		<tr>
			<td>Input:</td>
			<td>
				<select name="input">
			  	<option value="wordpress"<?php if (get_option ('tidy_input') == 'wordpress') :?>selected="selected"<?php endif; ?>>Default WordPress</option>
			    <option value="xhtml"<?php if (get_option ('tidy_input') == 'xhtml') :?>selected="selected"<?php endif; ?>>Raw XHTML</option>
			  </select> Output:   <select name="output">
				    <option value="wordpress"<?php if (get_option ('tidy_output') == 'wordpress') :?>selected="selected"<?php endif; ?>>Default WordPress</option>
				    <option value="xhtml"<?php if (get_option ('tidy_output') == 'xhtml') :?>selected="selected"<?php endif; ?>>Raw XHTML</option>
				  </select>

				Offset: <input type="text" name="offset" value="<?php echo esc_attr( $offset ); ?>" size="5"/>
				Maximum items: <input type="text" name="maximum" value="<?php echo esc_attr( $max ) ?>" size="5"/>
			</td>
		</tr>
		<tr>
		  <td></td>
			<td><input class="goodbutton" type="submit" name="report" value="Report"/>
				<input type="submit" name="clean" value="<?php echo __('Clean', 'tidy_up')?>" class="deathbutton" /></td>
		</tr>
	</table>
	</form>
</div>

<?php

	$input = get_option ('tidy_input');
	$output = get_option ('tidy_output');
	if (!$input) $input = 'wordpress';
	if (!$output) $output = 'wordpress';

	if (current_user_can (TIDY_ADMIN_LEVEL) && (isset ($_POST['report']) || isset ($_POST['clean']))) {
		include ('tidy_class.php');

		if (!ini_get ('safe_mode'))
			set_time_limit (0);
		?>
		<div class="wrap">
		<h2>Report</h2>

		<ul class="report">
		<?php
			if ($_POST['type'] == "posts")
		  		tidy_run_posts (isset ($_POST['clean']) ? true : false, $input, $output, $offset, $max);
			elseif ($_POST['type'] == 'pages')
				tidy_run_pages (isset ($_POST['clean']) ? true : false, $input, $output, $offset, $max);
			elseif ($_POST['type'] == 'excerpts')
				tidy_run_excerpts (isset ($_POST['clean']) ? true : false, $input, $output, $offset, $max);
			else
				tidy_run_comments (isset ($_POST['clean']) ? true : false, $input, $output, $offset, $max);
		?>
		</ul>
		</div>
		<?php
	}
}

function tidy_up_menu ()
{
	add_management_page ("Tidy Up", "Tidy Up", TIDY_ADMIN_LEVEL, basename (__FILE__), "tidy_up_admin");
}

function tidy_up_head ()
{
	wp_enqueue_style( 'tidy-up', plugin_dir_url( __FILE__ ).'tidy_up.css' );
	wp_enqueue_script( 'tidy-up', plugin_dir_url( __FILE__ ).'tidy_up.js', array( 'prototype' ) );
	wp_localize_script( 'tidy-up', 'tidy', array(
		'wp_base'    => plugins_url( 'ajax.php', __FILE__ ),
		'wp_loading' => __( 'Loading...', 'tidy-up' ),
	) );
}

if (is_admin()) {
	add_filter( 'admin_menu', 'tidy_up_menu');
	add_action( 'load-tools_page_tidy_up', 'tidy_up_head' );
}
