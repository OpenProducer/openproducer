<?php

$title = 'Edit Post';
$post_type = get_post_type();
if ( $post_type ) {
	$post_type_object = get_post_type_object( $post_type );

	if ( ! empty( $post_type_object->labels->singular_name ) ) {
		$title = 'Edit ' . $post_type_object->labels->singular_name;
	}
}
$edit_post_link = get_edit_post_link();
if ( ! is_customize_preview() && $edit_post_link ) { ?>
<div class="bg-edit-link">
	<a title="<?php print $title ?>" href="<?php print $edit_post_link ?>"><i class="fa fa-pencil"
		aria-hidden="true"></i></a>
</div>
<?php } ?>
