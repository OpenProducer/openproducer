import './component/slider/library';
import '../scss/slider.scss';
import '../scss/single.scss';
import '../scss/post-list.scss';

import { Plugin as SliderPlugin } from './component/slider/plugin';

let $ = jQuery;

class Application {

	/**
	 * Instantiate the Application.
	 *
	 * @since 1.0.0
	 */
	init() {
		$( () => this.onLoad() );

		return this;
	}

	/**
	 * Run this on page load.
	 *
	 * @since 1.0.0
	 */
	onLoad() {
		this.slider = new SliderPlugin();
		this.slider.initPageSliders();
	}
}

window.BOLDGRID = window.BOLDGRID || {};
window.BOLDGRID.PPBP = new Application().init();
