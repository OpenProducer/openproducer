<div class="plugin <?php echo $pluginClasses; ?>">
	<div class="<?php echo $messageClasses; ?>"><p><?php echo $message; ?></p></div>
	<div class="plugin-wrap">
		<div class="plugin-wrap-left">
			<img src="<?php echo $api->icons['1x']; ?>" alt="<?php printf( /* translators: 1 the name of the plguin. */ esc_attr__( '%1$s Plugin Logo', 'boldgrid-plugin-installer' ), $api->name ); ?>">
			<a href="<?php echo $modal; ?>" class="thickbox open-plugin-details-modal" aria-label="<?php printf( esc_attr__( 'More information about %1$s', 'boldgrid-plugin-installer' ), $name ); ?>" data-title="<?php echo $name; ?>">
				<?php esc_html_e( 'More Details', 'boldgrid-plugin-installer' ); ?>
			</a>
		</div>
		<h2 class="plugin-title">
			<?php echo $api->name; ?>
		</h2>
		<p>
			<?php echo $api->short_description; ?>
		</p>
		<p class="plugin-author">
			<?php printf( /* translators: The name of the plugin's author. */ esc_html__( 'By %1$s', 'boldgrid-plugin-installer' ), $api->author ); ?>
		</p>
	</div>
	<ul class="activation-row">
		<?php echo $premiumLink; ?>
		<li>
			<a href="<?php echo $button['link']; ?>" class="<?php echo $button['classes']; ?>" aria-label="<?php printf( /* translators: The name of the plugin to install. */ esc_attr__( 'Install %1$s now', 'boldgrid-plugin-installer' ), $name ); ?>" data-slug="<?php echo esc_attr( $api->slug ); ?>" data-name="<?php echo esc_attr( $name ); ?>">
				<?php echo $button['text']; ?>
			</a>
		</li>
	</ul>
</div>
