// 電源操作を行います
function aircon(onoff) {
	$("#js_alert_aircon_power").show();
	$("#js_alert_aircon_power_done").hide();
	$(".js_airconcommand").prop("disabled", true);

	return $.ajax({
		type: "GET",
		url: "aircon.php?power=" + onoff,
		dataType: "json"
	}).then(function (data) {
		$(".js_airconcommand").prop("disabled", false);
		$("#js_alert_aircon_power").hide();
		$("#js_alert_aircon_power_done").show();

		setTimeout(function() {
			$("#js_alert_aircon_power_done").fadeOut("slow");
		}, 5000);
	});
}
