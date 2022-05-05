var $ = jQuery,
	BG = BOLDGRID.EDITOR;

import template from '../../../../includes/template/gridblock/lead.html';

export class Lead {
	constructor() {
		this.$template = $();
	}

	/**
	 * Initialize the GridBlock Lead.
	 *
	 * @since 1.6
	 */
	init() {
		if ( BoldgridEditor.display_gridblock_lead ) {
			this.$template = this._insert();
			this._bind();
		}
	}

	/**
	 * Dismiss the prompt for adding a GridBlock.
	 *
	 * @since 1.6
	 */
	dismissPrompt() {
		this.$template.hide();
	}

	/**
	 * Bind event listeners.
	 *
	 * @since 1.6
	 */
	_bind() {
		this._bindDismiss();
		this._bindAddGridblock();
	}

	/**
	 * Dismiss the prompt.
	 *
	 * @since 1.6
	 */
	_bindDismiss() {
		this.$template
			.find( '.add-blank' )
			.add( '#insert-media-button, #insert-gridblocks-button' )
			.one( 'click', () => {
				this.dismissPrompt();
				BG.VALIDATION.Section.updateContainers( BOLDGRID.EDITOR.Controls.$container );
			} );
	}

	/**
	 * Add GridBlock will open the GridBlock Area.
	 *
	 * @since 1.6
	 */
	_bindAddGridblock() {
		this.$template.find( '.add-gridblock' ).one( 'click', () => {
			BG.CONTROLS.Section.enableSectionDrag();
			this.dismissPrompt();
		} );

		$( '#content-html' ).one( 'click', () => {
			this.dismissPrompt();
		} );
	}

	/**
	 * Insert the template into the page.
	 *
	 * @since 1.6
	 */
	_insert() {
		let $iframe = BG.Controls.$container.$iframe,
			$template = $( template );

		$iframe.after( $template );

		return $template;
	}
}

export { Lead as default };
