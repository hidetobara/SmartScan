<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>ランキングの遷移</title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				var options = {
					chart: {
						renderTo: 'container',
						type: 'line'
					},
					title: {
						text: 'Rank of {$package}'
					},
					xAxis: {
					},
					yAxis: {
						title: {
							text: 'Rank'
						},
						reversed: true,
						tickInterval: 5,
						min: 0
					},
					series: [
						{},{}
					]
				};

				$.getJSON('trans-package.php?package={$package}', function(data) {
					options.xAxis.categories = data.categories;
					options.series = data.series;
					var chart = new Highcharts.Chart(options);
				});

			});
		</script>
	</head>
<body>

{include file='web/_top_bar.tpl'}

<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

</body>
</html>
