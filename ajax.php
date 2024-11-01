<?php

// First we check we are an admin user logged in so to prevent abuse
include ('../../../wp-config.php');

if (!current_user_can ('edit_plugins'))
{
	echo 'You are not allowed access to this resource';
	return;
}

$id   = intval ($_GET['id']);
$cmd  = $_GET['cmd'];
$type = $_GET['type'];
$input = $_GET['input'];
$output = $_GET['output'];

include ('tidy_class.php');

if ($cmd == 'tidy')
{
  // Run it twice - first time we save, 2nd time we display
  ob_start ();
  
  if ($type == 'excerpt')
    tidy_excerpt ($id, true, $input, $output, true);
  else if ($type == 'post')
    tidy_post ($id, true, $input, $output, true);
  else if ($type == 'comment')
    tidy_comment ($id, true, $input, $output, true);

  ob_end_clean ();
  
  if ($type == 'excerpt')
    tidy_excerpt ($id, false, $input, $output, true);
  else if ($type == 'post')
    tidy_post ($id, false, $input, $output, true);
  else if ($type == 'comment')
    tidy_comment ($id, false, $input, $output, true);
}
else if ($cmd == 'edit')
{
  global $wpdb;

  if ($type == 'post')
  {
    $post = $wpdb->get_row ( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE id=%d", $id ) );
    $content = htmlentities (stripslashes ($post->post_content));
  }
  else if ($type == 'excerpt')
  {
    $post = $wpdb->get_row ($wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE id=%d", $id ) );
    $content = htmlentities (stripslashes ($post->post_excerpt));    
  }
  else if ($type == 'comment')
  {
    $comment = $wpdb->get_row ($wpdb->prepare( "SELECT * FROM {$wpdb->comments} WHERE comment_ID=%d", $id ) );
    $content = htmlentities (stripslashes ($comment->comment_content));      
  }

  ?>
  <div class="edit">
    <h3>#<?php echo $id ?>: Editing <?php echo $type ?></h3>
    <form method="post" action="" onsubmit="return save_it(this,<?php echo $id ?>,'<?php echo $type ?>','<?php echo $input ?>','<?php echo $output ?>')">
    <textarea name="edit" rows="10"><?php echo $content ?></textarea>
    <div class="editbuttons">
      <input type="submit" name="save" value="Update"/> 
      <input type="button" name="Cancel" value="Cancel" onclick="show_it(<?php echo $id ?>,'<?php echo $type ?>','<?php echo $input ?>','<?php echo $output ?>')"/>
    </div>
    </form>
  </div>
  <?php
  
  if ($type == 'excerpt')
    tidy_excerpt ($id, false, $input, $output, true);
  else if ($type == 'post')
    tidy_post ($id, false, $input, $output, true);
  else if ($type == 'comment')
    tidy_comment ($id, false, $input, $output, true);
}
else if ($cmd == 'save')
{
  global $wpdb;
  
  $edit = stripslashes ($wpdb->escape ($_POST['edit']));
  
  if ($type == 'excerpt')
  {
    $wpdb->query ($wpdb->prepare( "UPDATE {$wpdb->posts} SET post_excerpt=%s WHERE ID=%d", $edit, $id ) );
    tidy_excerpt ($id, false, $input, $output, true);
  }
  else if ($type == 'post')
  {
    $wpdb->query ($wpdb->prepare( "UPDATE {$wpdb->posts} SET post_content=%s WHERE ID=%d", $edit, $id ) );
    tidy_post ($id, false, $input, $output, true);
  }
  else if ($type == 'comment')
  {
    $wpdb->query ($wpdb->prepare( "UPDATE {$wpdb->comments} SET comment_content=%s WHERE comment_ID=%d", $edit, $id ) );
    tidy_comment ($id, false, $input, $output, true);
  }
}
else if ($cmd == 'show')
{
  if ($type == 'excerpt')
    tidy_excerpt ($id, false, $input, $output, true);
  else if ($type == 'post')
    tidy_post ($id, false, $input, $output, true);
  else if ($type == 'comment')
    tidy_comment ($id, false, $input, $output, true);
}
