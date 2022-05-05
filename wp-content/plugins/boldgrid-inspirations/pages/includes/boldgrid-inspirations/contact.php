<?php
$lang = array(
	'Address'        => __( 'Address', 'boldgrid-inspirations' ),
	'Back'           => __( 'Back', 'boldgrid-inspirations' ),
	'Change'         => __( 'This information can be edited later.', 'boldgrid-inspirations' ),
	'Company_name'   => __( 'Company Name / Site Title', 'boldgrid-inspirations' ),
	'Do_not_display' => __( 'Do not display', 'boldgrid-inspirations' ),
	'Email'          => __( 'Email', 'boldgrid-inspirations' ),
	// translators: 1 opening strong tag, 2 closing strong tag.
	'Intro'          => __( '%1$sOPTIONAL:%2$s The information you provide below will be used to populate contact information and social media icons throughout your BoldGrid website.', 'boldgrid-inspirations' ),
	'Next'           => __( 'Next', 'boldgrid-inspirations' ),
	'Phone'          => __( 'Phone', 'boldgrid-inspirations' ),
	'Show_fewer'     => __( 'Show fewer', 'boldgrid-inspirations' ),
	'Show_more'      => __( 'Show more', 'boldgrid-inspirations' ),
	'Social_media'   => __( 'Social Media', 'boldgrid-inspirations' ),
	'Valid_email'    => __( 'Please enter a valid email address.', 'boldgrid-inspirations' ),
);

$networks = require BOLDGRID_BASE_DIR . '/includes/config/networks.config.php';

$icons = '';

foreach( $networks as $url => $network ) {
	// If this is a default network, show it. Otherwise, hide it.
	if( ! empty( $network['default-shown'] ) && true === $network['default-shown'] ) {
		$class = '';
		$data_hidden = '';
	} else {
		$class = 'hidden';
		$data_hidden = 'data-hidden';
	}

	// If this is a network that should be added by default, label it as so.
	$data_added = ( ! empty( $network['default-added'] ) && true === $network['default-added'] ) ? 'data-added' : '';

	$icons .= sprintf(
		'<span data-icon="%1$s" data-sample-url="%2$s" title="%4$s" class="%5$s" %6$s %7$s>
			<i class="%3$s" aria-hidden="true"></i>
		</span>',
		$network['class'],
		// If we have a sample-url use that, else use url/username.
		( empty( $network['sample-url'] ) ? $url . '/username' : $network['sample-url'] ),
		$network['icon'],
		$network['name'],
		$class,
		$data_hidden,
		$data_added
	);
}

$social_media_index = sprintf(
	'<div id="social-media-index">
		%1$s
		<span title="%2$s" data-alt-title="%3$s"><i class="fa fa-plus" aria-hidden="true"></i></span>
	</div>',
	$icons,
	$lang['Show_more'],
	$lang['Show_fewer']
);

$blogname = get_option( 'blogname' );
?>


<div class="boldgrid-plugin-card full-width">
	<div class="top">

		<p>
			<?php printf( $lang['Intro'], '<strong>', '</strong>' ); ?>
			<?php echo $lang['Change']; ?>
		</p>

		<div class='survey-field'>
			<span class='title'><?php echo $lang['Company_name']; ?></span>
			<input class='main-input' type='text' name="survey[blogname][value]" value="<?php echo esc_attr( $blogname ); ?>" />
		</div>

		<div class='survey-field'>
			<span class='title'><?php echo $lang['Email']; ?></span>
			<div class='option'><?php echo $lang['Do_not_display']; ?> <input type="checkbox" name="survey[email][do-not-display]" /></div>
			<input class='main-input' type='text' name="survey[email][value]" value="<?php echo esc_attr( $user_email ); ?>" />
			<div class='invalid hidden'><?php echo $lang['Valid_email']; ?></div>
		</div>

		<div class='survey-field'>
			<span class='title'><?php echo $lang['Phone']; ?></span>
			<div class='option'><?php echo $lang['Do_not_display']; ?> <input type="checkbox" name="survey[phone][do-not-display]" /></div>
			<input class='main-input' type='text' name="survey[phone][value]" value="777-765-4321" />
		</div>

		<div class='survey-field'>
			<span class='title'><?php echo $lang['Address']; ?></span>
			<div class='option'><?php echo $lang['Do_not_display']; ?> <input type="checkbox" name="survey[address][do-not-display]" /></div>
			<input class='main-input' type='text' name="survey[address][value]" value="1234 Your St, City, STATE, 12345" />
		</div>

		<div class='survey-field' id='social-media'>
			<span class='title'><?php echo $lang['Social_media']; ?></span>
			<div class='option'><?php echo $lang['Do_not_display']; ?> <input type="checkbox" name="survey[social][do-not-display]" /></div>
		</div>

		<?php echo $social_media_index; ?>

	</div>
	<div class="bottom">
		<a class="button button-secondary"><?php echo $lang['Back']; ?></a>
		<a class="button button-primary boldgrid-orange"><?php echo esc_html__( 'Finish and Install', 'boldgrid-inspirations' ); ?></a>
	</div>
</div>
