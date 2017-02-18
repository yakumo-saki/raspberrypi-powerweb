var autoRefresh = false;
var refreshTimer;
var json;

$(document).ready(function() {
	fetchStatus();

	$('#js_autoRefresh').bootstrapToggle();
	$('#js_autoRefresh').change(function() {
		var checked = $(this).prop('checked');
		if (autoRefresh == checked) {
			return; // スイッチの状態と内部状態が一致しているので処理不要
		} else {
			if (checked) {
				enableAutoRefresh();
			} else {
				disableAutoRefresh();
			}
		}
	})
});

function enableAutoRefresh() {
	if (refreshTimer != null) {
		clearTimeout(refreshTimer);
	}

	refreshTimer = setTimeout(function() {
		fetchStatus();
		enableAutoRefresh();
	}, 5000);

	setAutoRefresh(true);
}

function disableAutoRefresh() {
	clearTimeout(refreshTimer);
	setAutoRefresh(false);
}

// 自動更新の状態を更新します。
// 外部から直接呼ぶべきではありません。
function setAutoRefresh(enabled) {
	autoRefresh = enabled;
	var onoff = enabled ? "on" : "off";
	$('#js_autoRefresh').bootstrapToggle(onoff);
}

// サーバーステータスを更新します
function fetchStatus(){
	return $.ajax({
		type: "GET",
		url: "ping.php",
		dataType: "json"
	}).then(function (data) {
		json = data;
		refreshStatus(data);
		$(".js_lastUpdate").text(moment().format("YYYY/MM/DD HH:mm:ss"));
	});
}

function refreshStatus(data) {
	$("#hv_status").text( (data.hv_ret == 0) ? "up" : "DOWN" );
	$("#sv_status").text( (data.sv_ret == 0) ? "up" : "DOWN" );

	$("#hv_output").text( arrayToString(data.hv_output) );
	$("#sv_output").text( arrayToString(data.sv_output) );

}

function arrayToString(stringArray) {
	var str = "";
	stringArray.forEach(function (s) {
		str += s + "\n";
	});
	return str;
}

// 電源操作を行います
function power(type) {

	setPowerCommandEnabled(false);
	$("#js_alert_power").show();
	$("#js_alert_power_done").hide();
	$(".js_powercommand").prop("disabled", true);
	enableAutoRefresh();

	return $.ajax({
		type: "GET",
		url: "power.php?type=" + type,
		dataType: "json"
	}).then(function (data) {
		$("#js_alert_power").hide();
		$("#js_alert_power_done").show();
		$(".js_powercommand").prop("disabled", false);
		setPowerCommandEnabled(true);

		setTimeout(function() {
			$("#js_alert_power_done").fadeOut("slow");
		}, 5000);

		setTimeout(function() {
			setPowerCommandEnabled(true);
		}, 3000);
	});
}

function setPowerCommandEnabled(isEnable) {
	$(".js_powercommand").prop("disabled", !isEnable);
}
