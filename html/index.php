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
		<script src="js/jquery-3.1.1.min.js"></script>
  </head>

  <body>

    <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
      <a class="navbar-brand" href="#">Powerweb</a>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12">
					<h3>サーバーステータス</h3>
				</div>
        <div class="col-md-6 col-sm-12">
          <h2>Hypervisor</h2>
          <p>
						Hypervisor is <span id="hv_status">down</span>

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
						Server is <span id="sv_status">down</span>

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
					<h4><a href="#">power off/on server (normal)</a></h3>
					<br />
					<h4><a href="#">power off server (force)</a></h3>
				</div>
			</div>
      <hr>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="js/bootstrap.min.js"></script>

		<script>
			var json;
			$(document).ready(function() {

				$.ajax({
					type: "GET",
					url: "ping.php",
					dataType: "json"
				}).then(function (data) {
					console.log(data);
					json = data;
					$("#hv_status").text( (data.hv_ret == 0) ? "up" : "DOWN" );
					$("#sv_status").text( (data.sv_ret == 0) ? "up" : "DOWN" );

					$("#hv_output").text( arrayToString(data.hv_output) );
					$("#sv_output").text( arrayToString(data.sv_output) );
				});
			});

			function arrayToString(stringArray) {
				var str = "";
				stringArray.forEach(function (s) {
					str += s + "\n";
				});
				return str;
			}
		</script>
  </body>
</html>
