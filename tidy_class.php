<?php

function tidy_print_details ($retval, $id, $link, $messages, $single = '', $short = false)
{
  if (!$short)
    echo '<li id="tidy_'.$id.'">';
	if ($retval == 0)
	{
		echo '<div class="ok">';
		echo "<h3>$link: Success, no warnings</h3>\r\n\r\n";
		echo '</div>';
	}
	else if ($retval == 1)
	{
		echo '<div class="warning">';
		echo "$single<h3>$link: Success with warnings</h3>\r\n<pre id=\"tidyc_$id\">".htmlentities ($messages)."</pre>\r\n\r\n";
		echo '</div>';

	}
	else if ($retval >= 2)
	{
		echo '<div class="err">';
		echo "$single<h3>$link: Failed</h3>\r\n<pre id=\"tidyc_$id\">".htmlentities ($messages)."</pre>\r\n\r\n";
		echo '</div>';
	}
	if (!$short)
	  echo '</li>';
}

function tidy_run_comments ($save, $input = 'wordpress', $output = 'wordpress', $offset, $max)
{
	global $wpdb;

	// SQL to get all posts
	$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->comments} WHERE comment_approved='1' ORDER BY comment_ID LIMIT %d,%d", $offset, $max );
	$results = $wpdb->get_results ($sql);

	if (count ($results) > 0)
	{
		foreach ($results AS $result)
	  {
			if ($input == 'wordpress')
				$result->comment_content = wptexturize (wpautop ($result->comment_content));

			$result->comment_content = stripslashes ($result->comment_content);
			$retval = tidy_text ($result->comment_content, $content, $messages, $output);
			if ($output == 'wordpress')
				$content = clean_pre ($content);
			$content = $wpdb->escape ($content);
			$sql = $wpdb->prepare( "UPDATE {$wpdb->comments} SET comment_content=%s WHERE comment_ID=%d", $content, $result->comment_ID );

  	  $link = "#{$result->comment_ID}";
  	  $single = '<div class="special"><a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=editcomment&comment='.$result->comment_ID.'" onclick="return tidy('.$result->comment_ID.',\'comment\',\''.$input.'\',\''.$output.'\')">tidy</a> | <a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=editcomment&comment='.$result->comment_ID.'" onclick="return edit('.$result->comment_ID.',\'comment\',\''.$input.'\',\''.$output.'\')">edit</a></div>';

  		tidy_print_details ($retval, $result->comment_ID, $link, $messages, $single, $short);
  		if (($retval == 0 || $retval == 1) && $save && strlen ($content) > 0)
  			$wpdb->query ($sql);
		}
	}
	else
		echo '<p>No suitable comments.</p>';
}

function tidy_comment ($postid, $save, $input = 'wordpress', $output = 'wordpress', $short = false)
{
	global $wpdb;

	// SQL to get one post
	$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->comments} WHERE comment_ID=%d", $postid );;
	$result = $wpdb->get_row ($sql);

	if ($result)
	{
		if ($input == 'wordpress')
			$result->comment_content = wptexturize (wpautop ($result->comment_content));

		$result->comment_content = stripslashes ($result->comment_content);
		$retval = tidy_text ($result->comment_content, $content, $messages, $output);

		if ($output == 'wordpress')
			$content = clean_pre ($content);
		$content = $wpdb->escape ($content);
		$sql = $wpdb->prepare( "UPDATE {$wpdb->comments} SET comment_content=%s WHERE comment_ID=%d", $content, $result->comment_ID );
	  $link = "#{$result->comment_ID}";
	  $single = '<div class="special"><a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=editcomment&comment='.$result->comment_ID.'" onclick="return tidy('.$result->comment_ID.',\'comment\',\''.$input.'\',\''.$output.'\')">tidy</a> | <a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=editcomment&comment='.$result->comment_ID.'" onclick="return edit('.$result->comment_ID.',\'comment\',\''.$input.'\',\''.$output.'\')">edit</a></div>';

		tidy_print_details ($retval, $result->comment_ID, $link, $messages, $single, $short);
		if (($retval == 0 || $retval == 1) && $save && strlen ($content) > 0)
			$wpdb->query ($sql);
	}
}

function tidy_post ($postid, $save, $input = 'wordpress', $output = 'wordpress', $short = false)
{
	global $wpdb;

	// SQL to get one post
	$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ID=%d", $postid );
	$result = $wpdb->get_row ($sql);

	if ($result)
	{
		if ($input == 'wordpress')
			$result->post_content = wptexturize (wpautop ($result->post_content));

		$result->post_content = stripslashes ($result->post_content);
		$retval = tidy_text ($result->post_content, $content, $messages, $output);

		if ($output == 'wordpress')
			$content = clean_pre ($content);
		$content = $wpdb->escape ($content);
		$sql = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content=%s WHERE ID=%d", $content, $result->ID );
	  $link = "#{$result->ID}";
	  $single = '<div class="special"><a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return tidy('.$result->ID.',\'post\',\''.$input.'\',\''.$output.'\')">tidy</a> | <a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return edit('.$result->ID.',\'post\',\''.$input.'\',\''.$output.'\')">edit</a></div>';

		tidy_print_details ($retval, $result->ID, $link, $messages, $single, $short);
		if (($retval == 0 || $retval == 1) && $save && strlen ($content) > 0)
			$wpdb->query ($sql);
	}
}

function tidy_excerpt ($postid, $save, $input = 'wordpress', $output = 'wordpress', $short = false)
{
	global $wpdb;

	// SQL to get one post
	$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ID=%d", $postid );
	$result = $wpdb->get_row ($sql);

	if ($result)
	{
		if ($input == 'wordpress')
			$result->post_excerpt = wptexturize (wpautop ($result->post_excerpt));

		$result->post_excerpt = stripslashes ($result->post_excerpt);
		$retval = tidy_text ($result->post_excerpt, $content, $messages, $output);

		if ($output == 'wordpress')
			$content = clean_pre ($content);
		$content = $wpdb->escape ($content);
		$sql = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_excerpt=%s WHERE ID=%d", $content, $result->ID );
	  $link = "#{$result->ID}";
	  $single = '<div class="special"><a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return tidy('.$result->ID.',\'excerpt\',\''.$input.'\',\''.$output.'\')">tidy</a> | <a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return edit('.$result->ID.',\'excerpt\',\''.$input.'\',\''.$output.'\')">edit</a></div>';

		tidy_print_details ($retval, $result->ID, $link, $messages, $single, $short);
		if (($retval == 0 || $retval == 1) && $save && strlen ($content) > 0)
			$wpdb->query ($sql);
	}
}

function tidy_run_excerpts ($save, $input = 'wordpress', $output = 'wordpress', $offset, $max)
{
	global $wpdb;

	// SQL to get all posts
	$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_excerpt != '' ORDER BY ID LIMIT %d,%d", $offset,$max );
	$results = $wpdb->get_results ($sql);

	if (count ($results) > 0)
	{
		foreach ($results AS $result)
	  {
			if ($input == 'wordpress')
				$result->post_excerpt = wptexturize (wpautop ($result->post_excerpt));

			$result->post_excerpt = stripslashes ($result->post_excerpt);

		  $link = "#{$result->ID}";
			$single = '<div class="special"><a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return tidy('.$result->ID.',\'excerpt\',\''.$input.'\',\''.$output.'\')">tidy</a> | <a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return edit('.$result->ID.',\'excerpt\',\''.$input.'\',\''.$output.'\')">edit</a></div>';
			$retval = tidy_text ($result->post_excerpt, $content, $messages, $output);

			if ($output == 'wordpress')
				$content = clean_pre ($content);
			$content = $wpdb->escape ($content);
			$sql = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_excerpt=%s WHERE ID=%d", $content, $result->ID );

			tidy_print_details ($retval, $result->ID, $link, $messages, $single);
			if (($retval == 0 || $retval == 1) && $save && strlen ($content) > 0)
				$wpdb->query ($sql);
		}
	}
	else
		echo '<p>No suitable posts.</p>';
}

function tidy_run_pages ($save, $input = 'wordpress', $output = 'wordpress', $offset, $max)
{
	global $wpdb;

	// SQL to get all posts
	$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_type='page' ORDER BY ID LIMIT %d,%d", $offset,$max );
	$results = $wpdb->get_results ($sql);

	if (count ($results) > 0)
	{
		foreach ($results AS $result)
	  {
			if ($input == 'wordpress')
				$result->post_content = wpautop ($result->post_content);

			$result->post_content = stripslashes ($result->post_content);

		  $link = "{$result->ID}";
  	  $single = '<div class="special"><a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return tidy('.$result->ID.',\'post\',\''.$input.'\',\''.$output.'\')">tidy</a> | <a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return edit('.$result->ID.',\'post\',\''.$input.'\',\''.$output.'\')">edit</a></div>';
			$retval = tidy_text ($result->post_content, $content, $messages, $output);

			if ($output == 'wordpress')
				$content = clean_pre ($content);
			$content = $wpdb->escape ($content);
			$sql = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content=%s WHERE ID=%d", $content, $result->ID );

			tidy_print_details ($retval, $result->ID, $link, $messages, $single);
			if (($retval == 0 || $retval == 1) && $save && strlen ($content) > 0)
				$wpdb->query ($sql);
		}
	}
	else
		echo '<p>No suitable posts.</p>';
}

function tidy_run_posts ($save, $input = 'wordpress', $output = 'wordpress', $offset, $max)
{
	global $wpdb;

	// SQL to get all posts
	$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_type='post' ORDER BY ID LIMIT %d,%d", $offset,$max );
	$results = $wpdb->get_results ($sql);

	if (count ($results) > 0)
	{
		foreach ($results AS $result)
	  {
			if ($input == 'wordpress')
				$result->post_content = wpautop ($result->post_content);

			$result->post_content = stripslashes ($result->post_content);

		  $link = "{$result->ID}";
  	  $single = '<div class="special"><a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return tidy('.$result->ID.',\'post\',\''.$input.'\',\''.$output.'\')">tidy</a> | <a href="'.get_bloginfo ('wpurl').'/wp-admin/post.php?action=edit&post='.$result->ID.'" onclick="return edit('.$result->ID.',\'post\',\''.$input.'\',\''.$output.'\')">edit</a></div>';
			$retval = tidy_text ($result->post_content, $content, $messages, $output);

			if ($output == 'wordpress')
				$content = clean_pre ($content);
			$content = $wpdb->escape ($content);
			$sql = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content=%s WHERE ID=%d", $content, $result->ID );

			tidy_print_details ($retval, $result->ID, $link, $messages, $single);
			if (($retval == 0 || $retval == 1) && $save && strlen ($content) > 0)
				$wpdb->query ($sql);
		}
	}
	else
		echo '<p>No suitable posts.</p>';
}

// Function performs a Tidy on some text by spawning a Tidy process (and not using the PHP Tidy Library)
// Return: 0 - no warnings or errors
//         1 - warnings
//         2 - errors
//         3 - failed (no tidy command etc)

function tidy_text ($text, &$output, &$messages, $config)
{
	// Determine which version of Tidy to use
	$machine = php_uname ('s');
	if ($machine == 'Darwin')
		$tidy_command = 'tidy.osx';
	else if ($machine == 'Windows')
		$tidy_command = 'tidy.exe';
	else if ($machine == 'Linux')
		$tidy_command = 'tidy.linux';
	else if ($machine == 'FreeBSD')
		$tidy_command = 'tidy.freebsd';
	else
		return 3;

	$tidy_command = dirname (__FILE__)."/".$tidy_command;
	$tidy_command .= ' -config '.dirname (__FILE__)."/$config.config";
	$tidy_head    = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	$tidy_head   .= '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>tidy</title></head><body>';
	$tidy_foot    = '</body></html>';

	$desc = array (0 => array ('pipe', 'r'), 1 => array ('pipe', 'w'), 2 => array ('pipe', 'w'));

  // Open tidy process
	$process = proc_open ($tidy_command, $desc, $pipes);
echo $tidy_command;
	if (is_resource ($process))
	{
		// Write data into it
		fwrite ($pipes[0], $tidy_head);
		fwrite ($pipes[0], $text);
		fwrite ($pipes[0], $tidy_foot);
		fclose ($pipes[0]);

		$output = $messages = '';

		while ($ret = fread ($pipes[1], 1024))
			$output .= $ret;

		fclose ($pipes[1]);

		while ($ret = fread ($pipes[2], 1024))
			$messages .= $ret;

		fclose ($pipes[2]);

		$messages = preg_replace ('/were found!(.*)/s', '', $messages);
		$messages = preg_replace ('/No warnings(.*)/s', '', $messages);
		$messages = preg_replace ('/Info(.*)/', '', $messages);
echo $messages;
		return proc_close ($process);
	}
	else
		return 3;
}
