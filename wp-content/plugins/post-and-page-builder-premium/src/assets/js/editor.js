import { SliderComponent } from './component/slider';

export class Editor {
	init() {
		this.slider = new SliderComponent().init();
	}
}

new Editor().init();
