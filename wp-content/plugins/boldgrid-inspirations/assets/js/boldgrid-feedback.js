/**
 * The BoldGrid Feedback class is to manipulate the admin notice used to ask for
 * feedback, and handle success and failure messages.
 *
 * @summary BoldGrid Feedback manipulation.
 *
 * @since 1.1
 * @class IMHWPB.BoldGridFeedback
 * @classdesc The BoldGrid Feedback class is to manipulate the admin notice used
 *            to ask for feedback, and handle success and failure messages.
 */

// Import IMHWPB.
var IMHWPB = IMHWPB || {};

/**
 * BoldGrid Feedback class.
 *
 * The BoldGrid Feedback class is to manipulate the admin notice used to ask for
 * feedback, and handle success and failure messages.
 *
 * @since 1.1
 *
 * @param object $
 *            jQuery.
 */
IMHWPB.BoldGridFeedback = function( $ ) {

	// Declare vars.
	var self = this;

	self.lang = BoldGridFeedback;

	// Initialize self.diagnosticData.
	self.diagnosticData = '';

	// Use jQuery to check events and modify the form content.
	$( function() {

		// Define a context selector for id "feedback-notice-1-1".
		$feedbackNotice11 = $( '#feedback-notice-1-1' );

		// Define a context selector for id "feedback-type".
		$feedbackNotice11Type = $feedbackNotice11.find( '#feedback-type' );

		// Define a context selector for id "feedback-contact-checkbox".
		$feedbackNotice11ContactCheckbox = $feedbackNotice11.find( '#feedback-contact-checkbox' );

		// Define a context selector for id "feedback-submit" (the submit
		// button).
		$feedbackSubmit = $feedbackNotice11.find( '#feedback-submit' );

		// Get the wpnonce and referer values.
		self.wpnonce = $feedbackNotice11.find( '#feedback_auth' ).val();

		self.wpHttpReferer = $feedbackNotice11.find( '[name="_wp_http_referer"]' ).val();

		// Define a context selector for id "feedback-notice-1-1-content".
		$feedbackContent = $feedbackNotice11.find( '#feedback-notice-1-1-content' );

		// Define a context selector for id "boldgrid-feedback-form".
		$feedbackForm = $feedbackContent.find( '#boldgrid-feedback-form' );

		// Define a context selector for id "feedback-error-message".
		$feedbackError = $feedbackContent.find( '#feedback-error-message' );

		// When the id "feedback-type" selection value changes, then modify form
		// content.
		$feedbackNotice11Type.change( self.toggleType );

		// When the id "feedback-contact-checkbox" checkbox is checked, display
		// "feedback-email-address".
		$feedbackNotice11ContactCheckbox.change( self.toggleFeedbackEmail );

		// Handle when the feedback form submit button is clicked.
		$feedbackSubmit.on( 'click', function() {
			if ( self.validateForm() ) {
				self.submit_feedback_form();
			}
			return false;
		} );
	} );

	/**
	 * Toggle the display of the feedback email address.
	 *
	 * @since 1.1
	 */
	self.toggleFeedbackEmail = function() {

		// Define a context selector.
		$feedbackEmailAddress = $feedbackNotice11.find( '#feedback-email-address' );

		// Toggle the display of the email address line.
		if ( $feedbackNotice11ContactCheckbox.is( ':checked' ) ) {
			$feedbackEmailAddress.show();
		} else {
			$feedbackEmailAddress.hide();
		}
	};

	/**
	 * Toggle the display of the diagnostic report.
	 *
	 * @since 1.1
	 */
	self.toggleType = function() {

		// Define a context selector for id "feedback-comment-area".
		$feedbackComment = $feedbackNotice11.find( '#feedback-comment-area' );

		// Define a context selector for id "feedback-diagnostic-report".
		$feedbackDiagnosticReport = $feedbackNotice11.find( '#feedback-diagnostic-report' );

		// Define a context selector for id "feedback-diagnostic-report".
		$feedbackDiagnosticReportText = $feedbackNotice11.find( '#feedback-diagnostic-text' );

		// Modify content based on selected feedback type.
		if ( '' === $feedbackNotice11Type.val() ) {

			// Hide the comment area.
			$feedbackComment.hide();

			// Hide the diagnostic report area.
			$feedbackDiagnosticReport.hide();

			// Disable the diagnostic report text area.
			$feedbackDiagnosticReportText.prop( 'disabled', 'disabled' );

			// Hide the submit button.
			$feedbackSubmit.hide();

			// Disable the submit button.
			$feedbackSubmit.prop( 'disabled', 'disabled' );
		} else {

			// Show the comment area.
			$feedbackComment.show();

			// Enable the submit button.
			$feedbackSubmit.prop( 'disabled', false );

			// Show the submit button.
			$feedbackSubmit.show();

			// Toggle the display of the diagnostic report area.
			if ( 'Bug report' == $feedbackNotice11Type.val() ) {

				// Enable the diagnostic report text area.
				$feedbackDiagnosticReportText.prop( 'disabled', false );

				// Show the diagnostic report area.
				$feedbackDiagnosticReport.show();

				// Populate diagnostic data, if needed.
				if ( '' === $feedbackDiagnosticReportText.val() ) {

					// Retrieve the data.
					self.populateDiagnosticData();
				}
			} else {

				// Hide the diagnostic report area.
				$feedbackDiagnosticReport.hide();

				// Disable the diagnostic report text area.
				$feedbackDiagnosticReportText.prop( 'disabled', 'disabled' );
			}
		}
	};

	/**
	 * Populate diagnostic data.
	 *
	 * This function can be called independently to retrieve diagnostic data
	 * (text).
	 *
	 * @since 1.1
	 *
	 * @return string Diagnostic information in standard text.
	 */
	self.populateDiagnosticData = function() {

		// Initialize variables.
		var data, diagnosticData;

		// Check if data was already retreived.
		if ( 0 < self.diagnosticData.length ) {

			// Update the form.
			$feedbackDiagnosticReportText.val( self.diagnosticData );

			return;
		}

		// Retrieve the data via AJAX.
		// Generate the data array.
		data = {
			action: 'boldgrid_feedback_diagnostic_data',
			feedback_auth: self.wpnonce,
			_wp_http_referer: self.wpHttpReferer
		};

		// Make the call.
		$.ajax( {
			url: ajaxurl,
			data: data,
			type: 'post',
			dataType: 'text',
			success: function( diagnosticData ) {

				// Set self.diagnosticData.
				self.diagnosticData = diagnosticData;

				// Update the form.
				$feedbackDiagnosticReportText.val( self.diagnosticData );
			}
		} );
	};

	/**
	 * Validate form fields before submitting ajax request.
	 *
	 * @since 1.2.12
	 */
	self.validateForm = function() {
		var $validated = true,

			// Comment box content.
			$comment = $feedbackForm
				.find( '#feedback-comment' )
				.val()
				.trim(),

			// Comment label.
			$commentLabel = $feedbackForm
				.find( '#feedback-comment' )
				.parent()
				.prev( '.feedback-form-label' );

		// Reset error highlighting for element.
		$commentLabel.removeClass( 'error-color' );

		if ( ! $comment ) {

			// Show error message.
			$feedbackError.find( '.feedback-form-field' ).html( '<p>' + self.lang.enterComment + '</p>' );

			// Unhide the error message.
			$feedbackError.show();
			$commentLabel.addClass( 'error-color' );

			// Hide the spinner.
			$feedbackForm.find( '.spinner' ).removeClass( 'is-active' );

			// Enable the submit button.
			$feedbackSubmit.prop( 'disabled', false );

			$validated = false;
		}

		return $validated;
	};

	/**
	 * Submit feedback form.
	 *
	 * @since 1.1
	 */
	self.submit_feedback_form = function() {

		// Define a var object for the form data.
		var formData = {},
			markup,
			errorCallback;

		// Define a context selector for id "feedback-notice-1-1-intro".
		$feedbackHeader = $feedbackNotice11.find( '#feedback-notice-1-1-intro' );

		// Disable the submit button.
		$feedbackSubmit.prop( 'disabled', 'disabled' );

		// Show the spinner.
		$feedbackForm.find( '.spinner' ).addClass( 'is-active' );

		// Get the form data.
		formData.feedbackType = $feedbackForm.find( '#feedback-type' ).val();

		formData.comment = $feedbackForm.find( '#feedback-comment' ).val();

		formData.contactMe = $feedbackForm.find( '#feedback-contact-checkbox' );

		if ( formData.contactMe.is( ':checked' ) ) {
			formData.emailAddress = $feedbackForm.find( '#feedback-email' ).val();
			formData.contactMe = 'Yes';
		} else {
			formData.contactMe = 'No';
		}

		if ( 'Bug report' == formData.feedbackType ) {
			formData.diagnosticReport = $feedbackForm.find( '#feedback-diagnostic-text' ).val();
		}

		// Add feedback.
		errorCallback = function() {

			// Show error message.
			$feedbackError.find( '.feedback-form-field' ).html( '<p>' + self.lang.tryAgain + '</p>' );

			// Unhide the error message.
			$feedbackError.show();
		};

		// Generate the data array.
		data = {
			action: 'boldgrid_feedback_submit',
			form_data: formData,
			feedback_auth: self.wpnonce,
			_wp_http_referer: self.wpHttpReferer
		};

		// Make the call.
		$.ajax( {
			url: ajaxurl,
			data: data,
			type: 'post',
			dataType: 'text',
			success: function( response ) {

				// Check response.
				if ( 'Success' == response ) {

					// Hide error message.
					$feedbackError.hide();

					// Replace the form with a success message.
					markup =
						'<h2>' + self.lang.thanks + '</h2>\n' + '<p>' + self.lang.weAreListening + '</p>';

					// Empty the notice area.
					$feedbackContent.empty();

					// Insert markup in the notice.
					$feedbackHeader.html( markup );
				} else {
					errorCallback();
				}
			},
			error: errorCallback,
			complete: function() {

				// Hide the spinner.
				$feedbackForm.find( '.spinner' ).removeClass( 'is-active' );

				// Enable the submit button.
				$feedbackSubmit.prop( 'disabled', false );
			}
		} );

		// Return false so the page does not reload.
		return false;
	};
};

new IMHWPB.BoldGridFeedback( jQuery );
