<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Power control</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="js/jquery-3.1.1.min.js"></script>

	<script>
<?php
	require ('define.php');

	print "var POWER_API = '" . POWER_API . "';";
	print "var RESET_API = '" . RESET_API . "';";
?>
	</script>
  </head>

  <body>

	<nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#patern04">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
  			<a href="/" class="navbar-brand">PowerWeb</a>
      </div>

      <div id="patern04" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li><a href="">Link1</a></li>
          <li><a href="">Link2</a></li>
          <li><a href="">Link3</a></li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <h2>Hypervisor</h2>
          <p>
						Hypervisor is <span id="hv_status">down</span>&nbsp;(<span class="js_lastUpdate">----/--/--</span>)


						<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#hv_detail"
										aria-expanded="false" aria-controls="hv_detail">
							Detail
						</button>
					</p>

					<div class="collapse" id="hv_detail">
						<pre id="hv_output" class="well">
						</pre>
					</div>
        </div>
        <div class="col-md-6 col-sm-12">
          <h2>Server</h2>
          <p>
						Server is <span id="sv_status">down</span>&nbsp;(<span class="js_lastUpdate">----/--/--</span>)

						<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#sv_detail"
										aria-expanded="false" aria-controls="sv_detail">
							Detail
						</button>
					</p>
					<div class="collapse" id="sv_detail">
						<pre id="sv_output" class="well">
						</pre>
					</div>
       </div>
      </div>

	<div class="row">
		<div class="col-sm-12">
			<h3> commands </h3>
		</div>
		<div class="col-sm-12">

			<div class="alert alert-warning alert-dismissible fade in" role="alert"
					id="js_alert_power" style="display: none;">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<p id="js_alert_power_msg">
				Auto refresh enabled.<br />
				Controlling power. Please wait...
				</p>
			</div>

			<div class="alert alert-success alert-dismissible fade in" role="alert"
					id="js_alert_power_done" style="display: none;">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<p id="js_alert_power_done_msg">
				Power control done.
				</p>
			</div>
			<h4>
					Auto refresh&nbsp;
					<input id="js_autoRefresh" type="checkbox" data-toggle="toggle" data-on="Enabled" data-off="Disabled">
			</h4>
			<br />
			<p style="margin-bottom: 15px;">
				<button type="button" class="js_powercommand btn btn-lg btn-warning" onclick="power('short');">
					Power off/on server (short press)
				</button>
			</p>
			<p>
				<button type="button" class="js_powercommand btn btn-lg btn-danger" onclick="power('long');">
					Force power off server (long press)
				</button>
			</p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<h3> Aircon controls </h3>
		</div>
		<div class="alert alert-success alert-dismissible fade in" role="alert"
				id="js_alert_aircon_power" style="display: none;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			<p>
			Sending IR... (aircon)
			</p>
		</div>

		<div class="alert alert-success alert-dismissible fade in" role="alert"
				id="js_alert_aircon_power_done" style="display: none;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			<p>
			Power control done. (aircon)
			</p>
		</div>
		<div class="col-sm-12">
			<p style="margin-bottom: 15px;">
				<button type="button" class="js_airconcommand btn btn-lg btn-primary" onclick="aircon('on');">
					Power on aircon
				</button>
				<button type="button" class="js_airconcommand btn btn-lg btn-default" onclick="aircon('off');">
					Power off aircon
				</button>
			</p>
		</div>
	</div>
    <hr>
    </div> <!-- /container -->

    <script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-toggle.min.js"></script>
		<script src="js/moment.min.js"></script>

		<script>
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

					setTimeout(function() {
						$("#js_alert_aircon_power_done").fadeOut("slow");
					}, 5000);
				});
			}


			function setPowerCommandEnabled(isEnable) {
				$(".js_powercommand").prop("disabled", !isEnable);
			}
		</script>
  </body>
</html>
