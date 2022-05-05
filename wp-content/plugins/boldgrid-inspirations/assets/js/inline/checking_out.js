$installation_log = jQuery(".installation-log");
$deploy_log_line_count = jQuery(".deploy_log_line_count");
$link_toggle_log = jQuery(".toggle-log");
$spinner = jQuery(".spinner");

// As new lines are added to the deploy_log, update the line count.
function update_deploy_log_line_count() {
	var line_count = $installation_log.find(".plugin-card-top").find("li").length;
	$deploy_log_line_count.html(line_count);
}

// Toggle the log as the user clicks "show / hide"
$link_toggle_log.on("click", function() {
	$installation_log.slideToggle();
});