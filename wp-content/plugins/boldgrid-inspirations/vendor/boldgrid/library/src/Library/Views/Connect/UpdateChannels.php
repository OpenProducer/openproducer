<?php
/**
 * File: UpdateChannels.php
 *
 * @package    Boldgrid\Library
 * @subpackage Library\Views
 * @version    2.5.0
 * @author     BoldGrid <support@boldgrid.com>
 */

// Get settings.
\Boldgrid\Library\Util\Option::init();
$pluginReleaseChannel = \Boldgrid\Library\Util\Option::get( 'release_channel' );
$themeReleaseChannel  = \Boldgrid\Library\Util\Option::get( 'theme_release_channel' );

// Validate release channel settings.
$channels = array(
	'stable',
	'edge',
	'candidate',
);

if ( ! in_array( $pluginReleaseChannel, $channels, true ) ) {
	$pluginReleaseChannel = 'stable';
}

if ( ! in_array( $themeReleaseChannel, $channels, true ) ) {
	$themeReleaseChannel = 'stable';
}

$showCandidateChoice = false;

if ( 'candidate' === $pluginReleaseChannel || 'candidate' === $themeReleaseChannel ||
	! empty( $_GET['channels'] ) ) {
		$showCandidateChoice = true;
}

$helpMarkup = esc_html__(
	'Update release channels determine which versions are retrieved from the BoldGrid Connect system.',
	'boldgrid-library'
);

$return = '
<div class="bg-box">
	<div class="bg-box-top">
		' . esc_html__( 'Plugins', 'boldgrid-library' ) . '
		<span class="dashicons dashicons-editor-help" data-id="plugins-update-channels"></span>
	</div>
	<div class="bg-box-bottom">

		<p class="help" data-id="plugins-update-channels">' . $helpMarkup . '</p>

<div class="auto-update-management div-table">
	<div class="auto-update-settings div-table-body">
		<div class="div-table-row">
			<div class="div-tableCell">
				<div class="div-table"><div class="div-table-body">
					<div class="div-table-row plugin-update-channel">
						<div class="div-tableCell">
							<input type="radio" name="plugin_release_channel" value="stable"';

if ( 'stable' === $pluginReleaseChannel ) {
	$return .= ' checked="checked"';
}

$return .= '> ' . esc_html__( 'Stable', 'boldgrid-library' ) . '
						</div>
						<div class="div-tableCell">
							<input type="radio" name="plugin_release_channel" value="edge"';

							if ( 'edge' === $pluginReleaseChannel ) {
								$return .= ' checked="checked"';
							}

$return .='> ' . esc_html__( 'Edge', 'boldgrid-library' ) . '
						</div>
';

if ( $showCandidateChoice ) {
	$return .='
						<div class="div-tableCell">
							<input type="radio" name="plugin_release_channel" value="candidate"';

	if ( 'candidate' === $pluginReleaseChannel ) {
		$return .= ' checked="checked"';
	}

	$return .= '> ' . esc_html__( 'Candidate', 'boldgrid-library' ) . '
						</div>
';
}

$return .= '
					</div>
				</div></div>
			</div>
		</div>
	</div>
</div>

	</div>
</div>

<div class="bg-box">
	<div class="bg-box-top">
		' . esc_html__( 'Themes', 'boldgrid-library' ) . '
		<span class="dashicons dashicons-editor-help" data-id="themes-update-channels"></span>
	</div>
	<div class="bg-box-bottom">

		<p class="help" data-id="themes-update-channels">' . $helpMarkup . '</p>

<div class="auto-update-management div-table">
	<div class="auto-update-settings div-table-body">
		<div class="div-table-row">
			<div class="div-tableCell">
				<div class="div-table"><div class="div-table-body">
					<div class="div-table-row theme-update-channel">
						<div class="div-tableCell">
							<input type="radio" name="theme_release_channel" value="stable"';

if ( 'stable' === $themeReleaseChannel ) {
	$return .= ' checked="checked"';
}

$return .= '> ' . esc_html__( 'Stable', 'boldgrid-library' ) . '
						</div>
						<div class="div-tableCell">
							<input type="radio" name="theme_release_channel" value="edge"';

if ( 'edge' === $themeReleaseChannel ) {
	$return .= ' checked="checked"';
}

$return .= '> ' . esc_html__( 'Edge', 'boldgrid-library' ) . '
						</div>
';

if ( $showCandidateChoice ) {
	$return .= '
						<div class="div-tableCell">
							<input type="radio" name="theme_release_channel" value="candidate"';

	if ( 'candidate' === $themeReleaseChannel ) {
		$return .= ' checked="checked"';
	}

	$return .= '> ' . esc_html__( 'Candidate', 'boldgrid-library' ) . '
						</div>
';
}

$return .= '
					</div>
				</div></div>
			</div>
		</div>
	</div>
</div>

	</div>
</div>
';

return $return;
