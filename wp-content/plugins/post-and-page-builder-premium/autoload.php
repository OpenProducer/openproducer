<?php
function bg_ppbp_autoload ($pClassName) {
	if ( false === strpos( $pClassName, 'Boldgrid\\PPBP' ) ) {
		return;
	}

	$updatedClass = str_replace( 'Boldgrid\PPBP\\', '', $pClassName );
	$path = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $updatedClass . '.php';
	$path = str_replace( '\\', '/', $path );

	if ( file_exists( $path ) && $pClassName !== $updatedClass ) {
		include( $path );
		return;
	}
}

spl_autoload_register( 'bg_ppbp_autoload' );
