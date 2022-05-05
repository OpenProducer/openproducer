var $ = window.jQuery;

export class View {

	/**
	 * Initialize the class.
	 *
	 * @since 1.7.0
	 */
	init() {
		this.renamePublishButton();
	}

	/**
	 * Change the text of the publish button on a new block.
	 *
	 * @since 1.7.0
	 */
	renamePublishButton() {
		$( '.post-type-bg_block.locale-en-us.post-new-php #submitdiv #publish' ).val( 'Save New Block' );
	}
}
