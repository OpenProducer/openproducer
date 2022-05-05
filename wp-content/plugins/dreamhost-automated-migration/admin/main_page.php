<header class="header-container">
	<img class="dreamhost-logo" src="<?php echo plugins_url("/../assets/img/dreamhost-logo.png", __FILE__); ?>">
	<img class="blogvault-logo" src="<?php echo plugins_url("/../assets/img/blogvault-logo.png", __FILE__); ?>">
</header>
<main class="text-center">
	<div class="card">
		<form action="<?php echo $this->bvinfo->appUrl(); ?>/migration/migrate" method="post" name="signup">
			<h1 class="card-title">Migrate your site to DreamHost</h1>
			<p>The DreamHost automated migration plugin makes moving sites to the DreamHost platform effortless.
				 It doesn't matter if you're a qualified developer or someone teaching yourself for the first time,
				 this plugin does all the hard work for you. Move as many sites as you need quickly, and with minimal interaction with support.
			</p>
			<hr class="my-4">
			<?php $this->showErrors(); ?>
			<div class="form-content">
				<label class="email-label" required>Email</label>
				<br>
				<input type="email" name="email" placeholder="Email address" class="email-input">
				<div class="tnc-check text-center mt-2">
					<label class="normal-text horizontal">
						<input type="hidden" name="bvsrc" value="wpplugin" />
						<input type="hidden" name="migrate" value="dreamhost" />
						<input type="checkbox" name="consent" onchange="document.getElementById('migratesubmit').disabled = !this.checked;" value="1">
						<span class="checkmark"></span>&nbsp;
						I agree to BlogVault's <a href="https://blogvault.net/tos/">Terms &amp; Conditions</a> and <a href="https://blogvault.net/privacy/">Privacy&nbsp;Policy</a>
					</label>
				</div>
			</div>
			<?php echo $this->siteInfoTags(); ?>
			<input type="submit" name="submit" id="migratesubmit" class="button button-primary" value="Migrate" disabled>
		</form>
	</div>
</main>