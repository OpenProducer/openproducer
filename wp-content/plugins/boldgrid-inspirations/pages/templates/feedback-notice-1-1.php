<div id='feedback-notice-1-1'
	class='updated notice is-dismissible fade boldgrid-admin-notice'
	data-admin-notice-id='feedback-notice-1-1'>
	<div id='feedback-notice-1-1-header'>
		<div class='boldgrid-icon'></div>
		<div id='feedback-notice-1-1-intro'>
			<h2><?php echo esc_html__( 'BoldGrid Feedback Request', 'boldgrid-inspirations' ); ?></h2>
			<p><?php echo esc_html__( 'We love feedback, both positive and negative.  It helps us build a better tool.', 'boldgrid-inspirations' ); ?></p>
			<p><?php echo esc_html__( 'Please take a moment to send us some of your thoughts about BoldGrid.', 'boldgrid-inspirations' ); ?></p>
		</div>
	</div>
	<div id='feedback-notice-1-1-content'>
		<form action='#' id='boldgrid-feedback-form' method='POST'>
		<?php wp_nonce_field( 'feedback-notice-1-1', 'feedback_auth' ); ?>
			<div class='feedback-form-label'><?php esc_html_e( 'Feedback type', 'boldgrid-inspirations' ); ?></div>
			<div>
				<select id='feedback-type' class='feedback-form-field' name='feedback_type'>
					<option value=''><?php echo esc_html__( 'Select', 'boldgrid-inspirations' ); ?>...</option>
					<option value='Theme design'><?php echo esc_html__( 'Theme design', 'boldgrid-inspirations' ); ?></option>
					<option value='General usability'><?php echo esc_html__( 'General usability', 'boldgrid-inspirations' ); ?></option>
					<option value='Feature suggestion'><?php echo esc_html__( 'Feature suggestion', 'boldgrid-inspirations' ); ?></option>
					<option value='Your host'><?php
					echo esc_html__( 'Your web hosting provider', 'boldgrid-inspirations' );

					if ( null !== $reseller_title ) {
						echo ' (' . esc_html( $reseller_title ) . ')';
					}
					?></option>
					<option value='Bug report'><?php echo esc_html__( 'Bug report', 'boldgrid-inspirations' ); ?></option>
					<option value='Other'><?php echo esc_html__( 'Other', 'boldgrid-inspirations' ); ?></option>
				</select>
			</div>
			<div id='feedback-comment-area'>
				<div class='feedback-form-label'><?php echo esc_html__( 'Comment', 'boldgrid-inspirations' ); ?></div>
				<div>
					<textarea id='feedback-comment' class='feedback-form-field' name='comment' rows='4' cols='53' placeholder='<?php echo esc_html__( 'Please type your feedback comment here.', 'boldgrid-inspirations' ); ?>'></textarea>
				</div>
				<div class='feedback-form-label'></div>
				<div class='feedback-form-field'>
					<input type='checkbox' id='feedback-contact-checkbox' name='contact_me' value='Y' />
					<label for='feedback-contact-checkbox'>
						<?php echo esc_html__( 'Please contact me about my feedback', 'boldgrid-inspirations' ); ?>
					</label>
				</div>
				<div id='feedback-email-address'>
					<div class='feedback-form-label'><?php echo esc_html__( 'Email address', 'boldgrid-inspirations' ); ?></div>
					<div class='feedback-form-field'>
						<input type='text' id='feedback-email' name='email_address' size='30' value='<?php echo esc_attr( $user_email ); ?>' placeholder='<?php echo esc_attr__( 'Please type your email address here.', 'boldgrid-inspirations' ); ?>'>
					</div>
				</div>
				<div id='feedback-diagnostic-report'>
					<div class='feedback-form-label'><?php echo esc_html__( 'Diagnostic report', 'boldgrid-inspirations' ); ?></div>
					<div class='feedback-form-field'>
						<textarea id='feedback-diagnostic-text' name='diagnostic_report' rows='4' cols='80' disabled='disabled' placeholder='<?php echo esc_attr__( 'This area will be populated with diagnostic data to better assist you.', 'boldgrid-inspirations' ); ?>'></textarea>
					</div>
				</div>
				<div class='feedback-form-label'><?php echo esc_html__( 'Website Experience', 'boldgrid-inspirations' ); ?></div>
				<div>
					<select id='feedback-experience' class='feedback-form-field' name='experience'>
						<option value=''><?php echo esc_html__( 'Select', 'boldgrid-inspirations' ); ?>...</option>
						<option value='Just Started'><?php echo esc_html__( 'Just Started', 'boldgrid-inspirations' ); ?></option>
						<option value='1-2 Years'><?php echo esc_html__( '1-2 Years', 'boldgrid-inspirations' ); ?></option>
						<option value='2-5 Years'><?php echo esc_html__( '2-5 Years', 'boldgrid-inspirations' ); ?></option>
						<option value='6+ Years'><?php echo esc_html__( '6+ Years', 'boldgrid-inspirations' ); ?></option>
					</select>
				</div>
				<div id='feedback-error-message'>
					<div class='feedback-form-label'></div>
					<div class='feedback-form-field'></div>
				</div>
				<div class='feedback-form-label'></div>
				<div class='feedback-form-field'>
					<button id='feedback-submit' class='button button-primary' disabled='disabled'><?php esc_html_e( 'Submit', 'boldgrid-inspirations' ); ?></button>
					<span class="spinner"></span>
				</div>
			</div>
		</form>
	</div>
</div>
