/**
 * Handle page attributes within the editor.
 *
 * Specifically, handle the edit, ok, and cancel options. This replicates the options native to
 * meta boxes (such as the status and visibility settings in the publish meta box).
 *
 * @since 2.7.0
 */

/* global jQuery */

var BOLDGRID = BOLDGRID || {};
BOLDGRID.LIBRARY = BOLDGRID.LIBRARY || {};

( function( $ ) {
	'use strict';

	var self;

	BOLDGRID.LIBRARY.Attributes = {

		/**
		 * @summary Get the default option element.
		 *
		 * Either we explicity flagged an element with data-default-option="1", or we get the
		 * selected value.
		 *
		 * @since 2.7.0
		 *
		 * @param  Object $section A ".misc-pub-section" section.
		 * @return Object          The default option.
		 */
		getDefaultOption: function( $section ) {
			var $defaultOption = $section.find( '[data-default-option="1"]' );

			if ( 0 === $defaultOption.length ) {
				$defaultOption = self.getSectionChecked( $section );
				$defaultOption.attr( 'data-default-option', '1' );
			}

			return $defaultOption;
		},

		/**
		 * @summary Get the display value of an element.
		 *
		 * @see self.initValueDisplayed().
		 *
		 * @since 2.7.0
		 *
		 * @param  Object $element The element to determine the display value of.
		 * @return string
		 */
		getDisplayValue: function( $element ) {
			var displayValue = $element.attr( 'data-value-displayed' ),
				inputType,
				$parent;

			if ( displayValue === undefined ) {
				$parent = $element.closest( '.bglib-misc-pub-section' );
				inputType = self.getSectionInput( $parent );

				switch ( inputType ) {
					case 'select':
						displayValue = $element.text();
						break;
					case 'radio':

						// This is a guess.
						displayValue = $element.parent().text();
						break;
				}
			}

			return displayValue;
		},

		/**
		 * @summary Get the selected value of a section.
		 *
		 * Determine whether we're dealing with a radio button or a select, and get the selected /
		 * checked option.
		 *
		 * @since 2.7.0
		 *
		 * @param  Object $section A ".misc-pub-section" section.
		 * @return Object
		 */
		getSectionChecked: function( $section ) {
			var inputType = self.getSectionInput( $section ),
				$checked;

			switch ( inputType ) {
				case 'select':
					$checked = $section.find( ':selected' );
					break;
				case 'radio':
					$checked = $section.find( '[type="radio"]:checked' );
					break;
			}

			return $checked;
		},

		/**
		 * @summary Get the type of input available in this section.
		 *
		 * For example, when selecting a status value, you may have a "select". Or, when selecting
		 * visibility value, you may have a "radio".
		 *
		 * @since 2.7.0
		 *
		 * @param  Object $section A ".misc-pub-section" section.
		 * @return string
		 */
		getSectionInput: function( $section ) {
			var types = {
					select: 'select',
					radio: 'input[type="radio"]'
				},
				inputType = false,
				key;

			for ( key in types ) {
				if ( 0 < $section.find( types[key] ).length ) {
					inputType = key;
					break;
				}
			}

			return inputType;
		},

		/**
		 * @summary Initialize the "value displayed" element.
		 *
		 * The value displayed is the "VALUE" in the below example:
		 * Key: VALUE Edit
		 *
		 * The above example can be further broken down with the following markup example:
		 * <div class="misc-pub-section bglib-misc-pub-section">
		 *     Status: <span class="value-displayed">Published</span> <a>Edit</a>
		 * </div>
		 *
		 * The reason we need to initialize it is because there may be no value set at all, but we
		 * need to show the user what the default value is. The text for the value-displayed element
		 * is retrieved via self.getDisplayValue.
		 *
		 * @since 2.7.0
		 *
		 * @param  Object $section A ".misc-pub-section" section.
		 */
		initValueDisplayed: function( $section ) {
			var $defaultOption = self.getDefaultOption( $section ),
				displayValue = self.getDisplayValue( $defaultOption ),
				inputType = self.getSectionInput( $section );

			// Set the "value-displayed" text.
			$section.find( '.value-displayed' ).html( displayValue );

			/*
			 * Usually the $defaultOption is already selected. In the event of the user clicking
			 * cancel, we'll have to reset the selected value.
			 */
			switch ( inputType ) {
				case 'select':
					$defaultOption.prop( 'selected', true );
					break;
				case 'radio':
					$defaultOption.prop( 'checked', true );
					break;
			}
		},

		/**
		 * Initialize all .bglib-misc-pub-section feilds.
		 *
		 * @since 2.7.0
		 */
		initValuesDisplayed: function() {
			$( '.bglib-misc-pub-section' ).each( function() {
				self.initValueDisplayed( $( this ) );
			} );
		},

		/**
		 * @summary Handle the click of the Edit link.
		 *
		 * When the Edit link is clicked, we need to show the available options to the user.
		 *
		 * @since 2.7.0
		 *
		 * @memberOf BOLDGRID.LIBRARY.Attributes
		 */
		onClickEdit: function() {
			var $edit = $( this ),
				$section = $edit.closest( '.bglib-misc-pub-section' );

			$section.find( '.options' ).slideToggle( 'fast' );
			$edit.toggle();

			// This is a button / anchor click. Return false.
			return false;
		},

		/**
		 * @summary Handle the click of the Cancel link.
		 *
		 * When the cancel link is clicked, the selected value needs to be reset.
		 *
		 * @since 2.7.0
		 */
		onClickCancel: function() {
			var $cancel = $( this ),
				$section = $cancel.closest( '.bglib-misc-pub-section' );

			$section
				.find( '.options' )
				.slideToggle( 'fast' )
				.end()
				.find( '.edit' )
				.toggle();

			self.initValueDisplayed( $section );

			// This is a button / anchor click. Return false.
			return false;
		},

		/**
		 * @summary Handle the click of the OK button.
		 *
		 * @since 2.7.0
		 */
		onClickOk: function() {
			var $ok = $( this ),
				$section = $ok.closest( '.bglib-misc-pub-section' ),
				$selected = self.getSectionChecked( $section ),
				displayValue = self.getDisplayValue( $selected );

			$section
				.find( '.options' )
				.slideToggle( 'fast' )
				.end()
				.find( '.edit' )
				.toggle()
				.end()
				.find( '.value-displayed' )
				.html( displayValue );

			// This is a button / anchor click. Return false.
			return false;
		}
	};

	self = BOLDGRID.LIBRARY.Attributes;

	$( function() {
		$( 'body' ).on(
			'click',
			'.bglib-misc-pub-section a.edit',
			BOLDGRID.LIBRARY.Attributes.onClickEdit
		);
		$( 'body' ).on(
			'click',
			'.bglib-misc-pub-section a.button-cancel',
			BOLDGRID.LIBRARY.Attributes.onClickCancel
		);
		$( 'body' ).on(
			'click',
			'.bglib-misc-pub-section a.button',
			BOLDGRID.LIBRARY.Attributes.onClickOk
		);
		self.initValuesDisplayed();
	} );
} )( jQuery );
