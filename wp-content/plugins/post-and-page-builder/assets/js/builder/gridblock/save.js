var $ = window.jQuery,
	BG = BOLDGRID.EDITOR;

export class Save {

	/**
	 * DOM load events.
	 *
	 * @since 1.6
	 */
	init() {
		this._setupSaveGridblock();
	}

	/**
	 * Handle the event of clicking save on a Gridblock.
	 *
	 * @since 1.6
	 */
	_setupSaveGridblock() {
		BG.GRIDBLOCK.View.$gridblockSection
			.find( '.gridblocks' )
			.on( 'mousedown', '.gridblock .save', e => {
				e.stopPropagation();

				let $this = $( e.target ),
					gridblockId = $this.closest( '.gridblock' ).data( 'id' ),
					gridblockData = BG.GRIDBLOCK.configs.gridblocks[gridblockId];

				BG.Controls.get( 'Library' ).openPanel( {
					title: gridblockData.getTitle(),
					type: gridblockData.type,
					html: BG.GRIDBLOCK.Create.getHtml( gridblockId )
				} );
			} );
	}
}

export { Save as default };
