<?php
/**
 * BoldGrid Source Code
 *
 * @package Boldgrid_Inspirations_Attribution_Asset
 * @copyright BoldGrid.com
 * @version $Id$
 * @author BoldGrid.com <wpb@boldgrid.com>
 */

namespace Boldgrid\Inspirations\Premium;

use Boldgrid\Library\Library;

/**
 * The BoldGrid Attribution Asset class.
 *
 * This class includes methods that help process assets during the creating of the Attribution
 * page.
 *
 * @since 1.3.1
 */
class Attribution {

	/**
	 * @var bool $licensed Licensed plugin?
	 * @var array $controls Controls array.
	 */
	protected $licensed;

	/**
	 * Controls.
	 *
	 * @since 1.4.3
	 * @var array
	 */
	protected $controls;

	/**
	 * Initialize class and set properties.
	 *
	 * @since 1.4.3
	 */
	public function __construct() {
		$this->setLicensed();

		add_action( 'customize_register', array( $this, 'addSettings' ) );

		$this->controls = $this->setControls(
			array(
				'reseller_control' => array(
					'type'      => 'checkbox',
					'settings'  => 'hide_partner_attribution',
					'transport' => 'refresh',
					'label'     => __( 'Hide Partner Attribution', 'boldgrid-inspirations' ),
					'section'   => 'boldgrid_footer_panel',
					'default'   => false,
					'priority'  => 50,
				),
				'special_thanks_control' => array(
					'type'      => 'checkbox',
					'settings'  => 'hide_special_thanks_attribution',
					'transport' => 'refresh',
					'label'     => __( 'Hide Special Thanks Link', 'boldgrid-inspirations' ),
					'section'   => 'boldgrid_footer_panel',
					'default'   => false,
					'priority'  => 60,
				),
			)
		);

		Library\Filter::add( $this );

		// If we have a premium license, add appropriate hooks to handle "Built with BoldGrid".
		if ( $this->licensed ) {
			include_once dirname( __FILE__ ) . '/BoldgridAttribution.php';
			$boldgridAttribution = new Boldgrid_Attribution();
			$boldgridAttribution->addHooks();
		}
	}

	/**
	 * Sets the licensed property.
	 *
	 * This property specifies whether or not the appropriate license is had to show controls for toggling
	 * Attribution.
	 *
	 * @since 1.4.3
	 */
	private function setLicensed() {
		$license = new Library\License;

		/*
		 * Originally only boldgrid-inspirations, several different license types may now toggle
		 * Attribution controls.
		 */
		$this->licensed = $license->getValid() && (
			$license->isPremium( 'boldgrid-inspirations' ) ||
			$license->isPremium( 'crio' ) ||
			$license->isPremium( 'post-and-page-builder' )
		);
	}

	/**
	 * Sets the controls array.
	 *
	 * @since 1.4.3
	 *
	 * @param array $controls Controls array.
	 */
	private function setControls( $controls ) {
		return $this->controls = $controls;
	}

	/**
	 * Adds required customizer footer configurations.
	 *
	 * @hook boldgrid_theme_framework_config
	 *
	 * @priority 5
	 *
	 * @param  array $configs BGTFW Configurations.
	 *
	 * @return array $configs BGTFW Configurations.
	 */
	public function partnerControl( $configs ) {

		$configs['customizer-options']['required']['boldgrid_enable_footer'] = array_values( array_diff( $configs['customizer-options']['required']['boldgrid_enable_footer'], array( 'hide_partner_attribution' ) ) );
		$reseller = get_option( 'boldgrid_reseller', false );

		if ( $reseller && ! empty( $reseller['reseller_title'] ) ) {
			$configs['customizer-options']['required']['boldgrid_enable_footer'][] = 'hide_partner_attribution';
		}

		return $configs;
	}

	/**
	 * Adds required customizer footer configurations.
	 *
	 * @hook boldgrid_theme_framework_config
	 *
	 * @priority 5
	 *
	 * @param  array $configs BGTFW Configurations.
	 *
	 * @return array $configs BGTFW Configurations.
	 */
	public function specialThanksControl( $configs ) {
		if ( $this->getLicensed() ) {
			$configs['customizer-options']['required']['boldgrid_enable_footer'][] = 'hide_special_thanks_attribution';
		}
		return $configs;
	}

	/**
	 * Adds attribution link controls to theme customizer.
	 *
	 * @hook kirki/fields
	 *
	 * @param array $controls [description]
	 */
	public function addControls( $controls ) {
		global $boldgrid_theme_framework;

		// Don't assume we're running a BoldGrid theme.
		if ( empty( $boldgrid_theme_framework ) ) {
			return $controls;
		}

		$bgtfw_version = $boldgrid_theme_framework->get_configs()['framework-version'];
		$is_crio       = version_compare( $bgtfw_version, '2.0.0', 'ge' );

		if ( $is_crio ) {
			return $controls;
		}

		$controls = array_merge( $controls, $this->getControls() );

		if ( ! get_option( 'boldgrid_reseller', false ) ) {
			unset( $controls['reseller_control'] );
		}
		if ( ! $this->getLicensed() ) {
			unset( $controls['special_thanks_control'] );
		}

		return $controls;
	}


	/**
	 * Adds attribution settings
	 *
	 * @since SINCEVERSION
	 */
	public function addSettings( $wp_customize ) {

		if ( get_option( 'boldgrid_reseller', false ) ) {
			$wp_customize->add_setting(
				'hide_partner_attribution',
				array(
					'default'           => false,
					'type'              => 'theme_mod',
					'sanitize_callback' => function( $checked ) {
						return ( ( isset( $checked ) && true == $checked ) ? true : false );
					},
				)
			);
		}
		if ( $this->getLicensed() ) {
			$wp_customize->add_setting(
				'hide_special_thanks_attribution',
				array(
					'default'           => false,
					'type'              => 'theme_mod',
					'sanitize_callback' => function( $checked ) {
						return ( ( isset( $checked ) && true == $checked ) ? true : false );
					},
				)
			);
		}
	}

	/**
	 * Create the attribution link for reseller affiliations.
	 *
	 * @since 1.4.3
	 *
	 * @hook bgtfw_attribution_links
	 *
	 * @param string $link Attribution markup to add to footer links.
	 *
	 * @return string $link Markup to add.
	 */
	public function addReseller( $link ) {
		// If the user hasn't disabled the footer, add the links.
		if ( get_theme_mod( 'boldgrid_enable_footer', true ) ) {
			$reseller_data = get_option( 'boldgrid_reseller', false );
			// Authorized Reseller/Partner Link.
			if ( ! get_theme_mod( 'hide_partner_attribution' ) ) {
				if ( ! empty( $reseller_data['reseller_title'] ) ) {
					$link = sprintf(
						'<span class="link partner-attribution-link">%s <a href="%s" rel="nofollow" target="_blank">%s</a></span>',
						__( 'Support from', 'bgtfw' ),
						$reseller_data['reseller_website_url'],
						$reseller_data['reseller_title']
					);
				}
			}
		}

		return $link;
	}

	/**
	 * Create the attribution link and keep link filterable for BoldGrid Staging
	 *
	 * @since 1.4.3
	 *
	 * @hook bgtfw_attribution_links
	 *
	 * @param string $link Attribution markup to add to footer links.
	 *
	 * @return string $link Markup to add.
	 */
	public function addAttribution( $link ) {
		$attribution_data = get_option( 'boldgrid_attribution' );
		$attribution_page = get_page_by_title( 'Attribution' );
		$special_thanks = __( 'Special Thanks', 'bgtfw' );

		// If option is available use that or try to find the page by slug name.
		if ( ! empty( $attribution_data['page']['id'] ) ) {
			$attribution = '<a href="' . get_permalink( $attribution_data['page']['id'] ) . '">' . $special_thanks . '</a>';
		} elseif ( $attribution_page ) {
			$attribution .= '<a href="' . get_site_url( null, 'attribution' ) . '">' . $special_thanks . '</a>';
		} else {
			$attribution .= '';
		}

		$this->getLicensed() ? : set_theme_mod( 'hide_special_thanks_attribution', false );
		$value = get_theme_mod( 'hide_special_thanks_attribution', false );

		$shown = '<span class="link special-thanks-attribution-link">' . $attribution . '</span>';
		if ( is_customize_preview() ) {
			$value = $value ? '<span class="link special-thanks-attribution-link hidden">' . $attribution . '</span>' : $shown;
		} else {
			$value = $value ? '' : $shown;
		}

		$attribution = $value;
		if ( ! get_theme_mod( 'boldgrid_enable_footer', true ) && $this->getLicensed() ) {
			$attribution = '';
		} else {
			$attribution = $value;
		}

		return $link . $attribution;
	}

	/**
	 * Gets $licensed class property.
	 *
	 * @return bool $licensed Licensed Plugin?
	 */
	protected function getLicensed() {
		return $this->licensed;
	}

	/**
	 * Gets $controls class property.
	 *
	 * @return array $controls Controls array.
	 */
	protected function getControls() {
		return $this->controls;
	}
}
