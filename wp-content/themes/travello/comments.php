
<?php
//Get only the approved comments
$args = array(
    'status' => 'approve'
);
 
if ( have_comments() ) :?>
<div class="comments-area">
	<h4><?php _e( get_comments_number() ) ?> Comments</h4>
	<?php
	    wp_list_comments( 'type=comment&callback=format_comment' );
    ?>
	<?php 
	else:
	 _e('No comments found.');
	endif;
		 comment_form(); ?>
</div>