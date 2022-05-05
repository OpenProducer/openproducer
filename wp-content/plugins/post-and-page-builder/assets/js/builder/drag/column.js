var $ = jQuery;

export default function( event, $left, $entered ) {
	var self = BOLDGRID.EDITOR.Controls.$container;

	if (
		self.recent_event &&
		self.recent_event.entered == $entered[0] &&
		self.recent_event.left == $left[0]
	) {
		return true;
	}

	self['recent_event'] = {
		entered: $entered[0],
		left: $left[0]
	};

	// @todo Figure out of this is good?
	if ( self.insertion_time + 20 > new Date().getTime() ) {
		return true;
	}

	// OVERWRITE(Column): When you trigger an event into child, rewrite to parent.
	if ( false == $entered.is( self.unformatted_column_selectors_string ) ) {
		if ( false == $entered.is( self.row_selectors_string ) ) {
			let $closestColumn = $entered.closest_context( self.column_selectors_string, self );
			if ( $closestColumn.length ) {
				$entered = $closestColumn;
			}
		}
	}

	// If you are dragging outside of the master container, skip this event.
	if ( false == self.has( $entered ).length ) {
		return true;
	}

	if ( $entered[0] == self.$temp_insertion[0] ) {
		return;
	}

	// If you drag entered a child of a column, from the same
	// column,
	// or child of the column, ignore the drag. This happens if the
	// current drag width is small and after your most recent drop your cursor was
	// still inside of a foreign column.

	//If this is happening in the same row.
	if ( $entered.siblings().filter( self.$temp_insertion ).length ) {

		// If entering a column from a column.
		if ( $entered.is( self.unformatted_column_selectors_string ) ) {

			// If entered a column that is not my own.
			if ( $entered[0] != self.$current_drag[0] ) {
				let $originalDragLeave = $( event.target );

				// I've left from a child of this column or the column itself.
				if ( $entered.find( $originalDragLeave ).length || $entered[0] == $originalDragLeave[0] ) {
					return true;
				}
			}
		}
	}

	// Moves element.
	self.move_column_to( $entered );
}
