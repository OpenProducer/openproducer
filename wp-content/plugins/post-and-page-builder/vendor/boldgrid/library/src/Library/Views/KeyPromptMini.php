<?php
/**
 * File: KeyPromptMini.php
 *
 * @package    Boldgrid\Library
 * @subpackage Library\Views
 * @version    2.6.0
 * @author     BoldGrid <support@boldgrid.com>
 */

namespace Boldgrid\Library\Library\Views;

use Boldgrid\Library\Library;

/**
 * Class: KeyPromptMini
 *
 * Print a mini Connect Key entry prompt.
 *
 * @since 2.6.0.
 */
class KeyPromptMini {
	/**
	 * Display form.
	 *
	 * @since 2.6.0
	 *
	 * @static
	 *
	 * @see self::enqueue()
	 */
	public static function displayForm() {
		KeyPrompt::enqueue();
		?>
		<div id="container_boldgrid_api_key_notice" class="library is-dismissible keyprompt-mini"
			data-notice-id="bg-key-prompt"
			data-notice-state="<?php echo \Boldgrid\Library\Library\Notice\KeyPrompt::getState() ?>">
			<div>
				<form id="boldgrid-api-form" autocomplete="off">
					<?php wp_nonce_field( 'boldgrid_set_key', 'set_key_auth' ); ?>
					<input id="tos-box" class="hidden" type="checkbox" value="0" checked="checked" />
					<input type="text" id="boldgrid_api_key" maxlength="37" placeholder="XXXXXXXX - XXXXXXXX - XXXXXXXX - XXXXXXXX" autocomplete="off" />
					<button id="submit_api_key" class="button button-primary">
						<?php esc_html_e( 'Submit', 'boldgrid-library' ); ?>
					</button>
					<span class="spinner spinner-left"></span>

					<p id="boldgrid_api_key_notice_message"></p>
				</form>
			</div>
		</div>
		<?php
	}
}
