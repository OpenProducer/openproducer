<?php
/**
 * BoldGrid Library Rating Prompt Class
 *
 * @package Boldgrid\Library
 * @subpackage \Library\License
 *
 * @version 2.7.7
 * @author BoldGrid <wpb@boldgrid.com>
 */

namespace Boldgrid\Library\Library;

use Boldgrid\Library\Library;

/**
 * BoldGrid Library Rating Prompt Class.
 *
 * This class is responsible for displaying admin notices asking for feedback / a rating on wp.org.
 *
 * @since 2.7.7
 */
class RatingPrompt {
	/**
	 * Whether or not we've already added the filters in the constructor.
	 *
	 * Filters only need to be added once.
	 *
	 * @since 2.7.7
	 * @var bool
	 */
	private static $filtersAdded = false;

	/**
	 * The minimum amount of time between showing different prompts.
	 *
	 * @since 2.7.7
	 * @var int
	 * @see self::getLastDismissal()
	 */
	private $minInterval = 0;

	/**
	 * The option name where rating prompts are stored.
	 *
	 * @since 2.7.7
	 * @var string
	 */
	private $optionName = 'bglib_rating_prompt';

	/**
	 * The role required to see a rating prompt.
	 *
	 * @since 2.7.7
	 * @var string
	 */
	private $userRole = 'update_plugins';

	/**
	 * Initialize class and set class properties.
	 *
	 * @since 2.7.7
	 */
	public function __construct() {
		// Only add the filters once.
		if ( ! self::$filtersAdded ) {
			Filter::add( $this );
			self::$filtersAdded = true;
		}

		$this->minInterval = 2 * DAY_IN_SECONDS;
	}

	/**
	 * Add a prompt.
	 *
	 * This only adds a prompt if it doesn't already exist by name.
	 *
	 * @since 2.7.7
	 *
	 * @param  array $prompt An array of configs for a prompt.
	 * @return bool          Whether or not the prompt was added.
	 */
	public function addPrompt( $prompt ) {
		$added = false;

		/*
		 * Determine whether to add the new prompt or not.
		 *
		 * If we've already a prompt for user_did_this_x_times, don't add another prompt for the
		 * same thing.
		 *
		 * If a plugin has already been dismissed, no need to add any additional rating prompts,
		 * they will never be showing.
		 */
		if ( ! $this->isPrompt( $prompt['name'] ) && ! $this->isPluginDismissed( $prompt['plugin'] ) ) {
			$prompt['time_added'] = time();

			$prompts = $this->getPrompts();

			$prompts[] = $prompt;

			$added = $this->savePrompts( $prompts );
		}

		return $added;
	}

	/**
	 * Add admin notices.
	 *
	 * @since 2.7.7
	 */
	public function admin_notices() {
		if ( ! current_user_can( $this->userRole ) ) {
			return;
		}

		$prompt = $this->getNext();
		if ( empty( $prompt ) ) {
			return;
		}

		$slides = $this->getPromptSlides( $prompt );
		if ( ! empty( $slides ) ) {
			echo '<div class="notice notice-success bglib-rating-prompt is-dismissible" data-slide-name="' . esc_attr( $prompt['name'] ) . '">';
			foreach ( $slides as $slide ) {
				echo $slide;
			}

			// This Nonce was causing conflicts with core nonces. Added a prefix to the nonce name.
			wp_nonce_field( 'bglib-rating-prompt', 'bglib_rating_prompt_nonce' );
			echo '</div>';
		}
	}

	/**
	 * Dismiss a rating prompt via ajax.
	 *
	 * @since 2.7.7
	 *
	 * @hook wp_ajax_blib_rating_prompt_dismiss
	 */
	public function ajaxDismiss() {
		if ( ! current_user_can( $this->userRole ) ) {
			wp_send_json_error( __( 'Permission denied.', 'boldgrid-library' ) );
		}

		if( ! check_ajax_referer( 'bglib-rating-prompt', 'security', false ) ) {
			wp_send_json_error( __( 'Invalid nonce.', 'boldgrid-library' ) );
		}

		$name          = sanitize_text_field( $_POST['name'] );
		$type          = sanitize_text_field( $_POST['type'] );
		$snooze_length = (int) $_POST['length'];

		switch( $type ) {
			case 'dismiss':
				$dismissed = $this->updatePromptKey( $name, 'time_dismissed', time() );
				$dismissed ? wp_send_json_success() : wp_send_json_error( __( 'Error dismissing prompt', 'boldgrid-library' ) );
				break;
			case 'snooze':
				$time_snoozed_set = $this->updatePromptKey( $name, 'time_snoozed', time() );
				$snoozed          = $this->updatePromptKey( $name, 'time_snoozed_until', time() + $snooze_length );
				$time_snoozed_set && $snoozed ? wp_send_json_success() : wp_send_json_error( __( 'Error snoozing prompt', 'boldgrid-library' ) );
				break;
			default:
				wp_send_json_error( __( 'Unknown action.', 'boldgrid-library' ) );
		}
	}

	/**
	 * Get an array of attributes for a decision's <a> tag.
	 *
	 * @since 2.7.7
	 *
	 * @param  array $decision A decision from a slide.
	 * @return array
	 */
	public function getDecisionAttributes( $decision ) {
		$attributes = array();

		$action = isset( $decision['snooze'] ) ? 'snooze' : 'dismiss';

		$attributes['data-action'] = esc_attr( $action );

		if ( isset( $decision['link'] ) ) {
			$attributes['href'] = esc_url( $decision['link'] );
			$attributes['target'] = '_blank';
		} else {
			$attributes['href'] = '';
		}

		if ( isset( $decision['snooze'] ) ) {
			$attributes['data-snooze'] = esc_attr( $decision['snooze'] );
		}

		if ( isset( $decision['slide'] ) ) {
			$attributes['data-next-slide'] = esc_attr( $decision['slide'] );
		}

		return $attributes;
	}

	/**
	 * Get the time of the last dismissal or snooze, whichever is latest.
	 *
	 * One reason this is used is to ensure prompts don't show one after another for the user. For
	 * example, if the user just dismissed a rating prompt, we may not want to show them another
	 * prompt for at least 2 days.
	 *
	 * @since 2.7.7
	 *
	 * @return int
	 */
	public function getLastDismissal() {
		$lastDismissal = 0;

		$prompts = $this->getPrompts();
		foreach ( $prompts as $prompt ) {
			$promptDismissal = 0;

			if ( ! empty( $prompt['time_dismissed'] ) ) {
				$promptDismissal = $prompt['time_dismissed'];
			} elseif ( ! empty( $prompt['time_snoozed'] ) ) {
				$promptDismissal = $prompt['time_snoozed'];
			}

			$lastDismissal = $promptDismissal > $lastDismissal ? $promptDismissal : $lastDismissal;
		}

		return $lastDismissal;
	}

	/**
	 * Get the minInterval value.
	 *
	 * @since 2.7.7
	 *
	 * @return int
	 */
	public function getMinInterval() {
		return $this->minInterval;
	}

	/**
	 * Admin enqueue scripts.
	 *
	 * @since 2.7.7
	 */
	public function admin_enqueue_scripts() {
		if ( ! current_user_can( $this->userRole ) ) {
			return;
		}

		$prompt = $this->getNext();

		if ( ! empty( $prompt ) ) {
			wp_enqueue_script(
				'bglib-rating-prompt-js',
				Library\Configs::get( 'libraryUrl' ) . 'src/assets/js/rating-prompt.js',
				'jquery',
				date( 'Ymd' )
			);

			wp_enqueue_style(
				'bglib-rating-prompt-css',
				Library\Configs::get( 'libraryUrl' ) . 'src/assets/css/rating-prompt.css',
				array(),
				date( 'Ymd' )
			);
		}
	}

	/**
	 * Return the markup of a prompt's slides.
	 *
	 * @since 2.7.7
	 *
	 * @param  array  $prompt A prompt.
	 * @return string
	 */
	public function getPromptSlides( $prompt ) {
		$slides = array();

		if ( ! empty( $prompt['slides'] ) ) {
			foreach ( $prompt['slides'] as $slide_id => $slide ) {
				$slideMarkup = $this->getSlideMarkup( $slide_id, $slide );

				$slides[$slide_id] = $slideMarkup;
			}
		}

		return $slides;
	}

	/**
	 * Get the markup for an individual slide.
	 *
	 * @since 2.7.7
	 *
	 * @param  string $slide_id The id of a slide.
	 * @param  array  $slide    A array of slide configs.
	 * @return string
	 */
	public function getSlideMarkup( $slide_id, $slide ) {
		$slideMarkup = '<div data-slide-id="' . esc_attr( $slide_id ) . '">';

		$slideMarkup .= '<p>' . $slide['text'] . '</p>';

		if ( ! empty( $slide['decisions'] ) ) {
			$slide_decisions = array();

			foreach( $slide['decisions'] as $decision ) {
				$attributes = $this->getDecisionAttributes( $decision );

				$markup = '<a ';
				foreach ( $attributes as $key => $value ) {
					$markup .= $key . '="' . $value . '" ';
				}
				$markup .= '>' . esc_html( $decision['text'] ) . '</a>';

				$slide_decisions[] = $markup;
			}
			$slideMarkup .= '<ul><li>' . implode( '</li><li>', $slide_decisions ) . '</li></ul>';
		}

		$slideMarkup .= '</div>';

		return $slideMarkup;
	}

	/**
	 * Whether or not the minimum amount of time between showing prompts has been reached.
	 *
	 * @since 2.7.7
	 *
	 * @see self::getLastDismissal().
	 *
	 * @return bool
	 */
	public function isMinInterval() {
		return time() > $this->getLastDismissal() + $this->minInterval;
	}

	/**
	 * Get a prompt by name.
	 *
	 * @since 2.7.7
	 *
	 * @param  string $name The name of a prompt.
	 * @return array
	 */
	public function getPrompt( $name ) {
		$prompt_found = array();

		$prompts = $this->getPrompts();

		foreach( $prompts as $prompt ) {
			if ( $name === $prompt['name'] ) {
				$prompt_found = $prompt;
				break;
			}
		}

		return $prompt_found;
	}

	/**
	 * Get all rating prompts.
	 *
	 * @since 2.7.7
	 *
	 * @return array
	 */
	public function getPrompts() {
		return get_option( $this->optionName, array() );
	}

	/**
	 * Determine whether or not a given plugin has any dismissed notices.
	 *
	 * @since 2.7.7
	 *
	 * @param  string $plugin A plugin name.
	 * @return bool
	 */
	public function isPluginDismissed( $plugin ) {
		$dismissed = false;

		$plugin_prompts = $this->getPluginPrompts( $plugin );

		foreach ( $plugin_prompts as $prompt ) {
			if ( ! empty( $prompt['time_dismissed'] ) ) {
				$dismissed = true;
			}
		}

		return $dismissed;
	}

	/**
	 * Whether or not a prompt (by prompt name) exists.
	 *
	 * @since 2.7.7
	 *
	 * @param  string $name A prompt name.
	 * @return bool
	 */
	public function isPrompt( $name ) {
		$prompt = $this->getPrompt( $name );

		return ! empty( $prompt );
	}

	/**
	 * Get all prompts for a plugin.
	 *
	 * @since 2.7.7
	 *
	 * @param  string $plugin The name of a plugin.
	 * @return array
	 */
	public function getPluginPrompts( $plugin ) {
		$pluginPrompts = array();

		$prompts = $this->getPrompts();

		foreach ( $prompts as $prompt ) {
			if ( $prompt['plugin'] === $plugin ) {
				$pluginPrompts[] = $prompt;
			}
		}

		return $pluginPrompts;
	}

	/**
	 * Get the next prompt to display.
	 *
	 * We only display one prompt at a time. Get that prompt.
	 *
	 * @since 2.7.7
	 */
	public function getNext() {
		$nextPrompt = array();
		$prompts = $this->getPrompts();

		foreach ( $prompts as $prompt ) {
			$isDismissed      = isset( $prompt['time_dismissed'] );
			$isOlder          = empty( $nextPrompt ) || ( ! $isDismissed && $prompt['time_added'] < $nextPrompt['time_added'] );
			$isStillSnoozing  = ! empty( $prompt['time_snoozed_until'] ) && $prompt['time_snoozed_until'] > time();

			if ( ! $isDismissed && $isOlder && ! $isStillSnoozing ) {
				$nextPrompt = $prompt;
			}
		}

		if ( ! $this->isMinInterval() ) {
			$nextPrompt = array();
		}

		return $nextPrompt;
	}

	/**
	 * Update an existing prompt.
	 *
	 * If the prompt is not found, this method will not make any changes.
	 *
	 * @since 2.7.7
	 *
	 * @param  array $prompt_to_save A prompt.
	 * @return bool                  Whether or not the prompt was found and updated.
	 */
	public function updatePrompt( $prompt_to_save ) {
		$found = false;

		$prompts = $this->getPrompts();

		foreach ( $prompts as $key => $prompt ) {
			if ( $prompt_to_save['name'] === $prompt['name'] ) {
				$found = true;

				$prompts[$key] = $prompt_to_save;

				break;
			}
		}

		return $found ? $this->savePrompts( $prompts ) : false;
	}

	/**
	 * Save prompts.
	 *
	 * Be sure to pass in all prompts. This replaces the existing option.
	 *
	 * @since 2.7.7
	 *
	 * @param  array $prompts An array of prompts.
	 * @return bool           Whether or not the prompts were saved.
	 */
	public function savePrompts( $prompts ) {
		return update_option( $this->optionName, $prompts );
	}

	/**
	 * Update an attribute for a prompt.
	 *
	 * Commonly used to set the time a prompt was dismissed.
	 *
	 * @since 2.7.7
	 *
	 * @param  string $name  The name of a prompt.
	 * @param  string $key   The key to update.
	 * @param  mixed  $value The new value for the key.
	 * @return bool          Whether or not the prompt was saved successfully.
	 */
	public function updatePromptKey( $name, $key, $value ) {
		$prompt = $this->getPrompt( $name );

		$prompt[$key] = $value;

		return $this->updatePrompt( $prompt );
	}
}
