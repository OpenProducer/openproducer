var BG = BOLDGRID.EDITOR,
	$ = window.jQuery;

import template from '../../../../../includes/template/customize/background-color.html';

export class BackgroundColor {

	/**
	 * Render the control.
	 *
	 * @since 1.6
	 *
	 * @return {jQuery} Control element.
	 */
	render() {
		this.$target = BG.Menu.getCurrentTarget();
		this.$control = this.createControl();
		this.$input = this.$control.find( '[name="generic-bg-color"]' );

		this._bind();

		return this.$control;
	}

	/**
	 * Create a control.
	 *
	 * @since 1.6.0
	 *
	 * @return {jQuery} Control element.
	 */
	createControl() {
		let $control = $( template );

		BG.Panel.$element.find( '.panel-body .customize .generic-bg-color' ).remove();
		BG.Panel.$element.find( '.panel-body .customize' ).append( $control );

		BG.Panel.$element.on( 'bg-customize-open', () => {
			let currentBackgroundColor = this.$target.css( 'background-color' );
			if ( BG.Controls.$container.color_is( currentBackgroundColor, 'transparent' ) ) {
				currentBackgroundColor = '#FFFFFF';
			}

			this.$control.find( 'label.color-preview' ).css( 'background-color', currentBackgroundColor );
		} );

		return $control;
	}

	/**
	 * Setup background color change event.
	 *
	 * @since 1.6.0
	 */
	_bind() {
		this.$input.on( 'change', () => {
			var value = this.$input.val(),
				type = this.$input.attr( 'data-type' );

			this.$target.removeClass( BG.CONTROLS.Color.textContrastClasses.join( ' ' ) );
			this.$target.removeClass( BG.CONTROLS.Color.backgroundColorClasses.join( ' ' ) );
			BG.Controls.addStyle( this.$target, 'background-color', '' );

			if ( 'class' === type ) {
				if ( ! BG.Panel.currentControl.disabledTextContrast ) {
					this.$target.addClass( BG.CONTROLS.Color.getColorClass( 'text-contrast', value ) );
				}

				this.$target.addClass( BG.CONTROLS.Color.getColorClass( 'background-color', value ) );
			} else {
				BG.Controls.addStyle( this.$target, 'background-color', value );
			}

			BG.Panel.$element.trigger( BG.Panel.currentControl.name + '-background-color-change' );
		} );
	}
}

export { BackgroundColor as default };
