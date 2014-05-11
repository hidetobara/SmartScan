<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>キーワード含有数</title>
	{include file='web/_common_style.tpl'}
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>
<body>

<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>

<br />
<br />
{include file='web/_date_select.tpl' _action="./key1.php" _date_str=$date}

<script type="text/javascript">
	$(function () {
		$('#container').highcharts({
			chart: {
				type: 'bar'
			},
			title: {
				text: '上位200タイトル説明文中のキーワード含有数'
			},
			xAxis: {
				categories: {$key_words},
				title: {
					text: null
				}
			},
			yAxis: {
				min: 0,
				max: 200,
				title: {
					text: 'Count',
					align: 'high'
				},
				labels: {
					overflow: 'justify'
				}
			},
			plotOptions: {
				bar: {
					dataLabels: {
						enabled: true
					}
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -40,
				y: 100,
				floating: true,
				borderWidth: 1,
				backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor || '#FFFFFF'),
				shadow: true
			},
			series: [
				{
					name: '{$date}',
					data: {$key_values}
				}
			]
		});
	});
</script>

<div id="container" style="min-width: 300px; height: 3000px; margin: 0 auto"></div>

{include file='web/_top_bar.tpl'}

<span>※mecabにて形態素解析し、各名詞の使用数を算出</span>

</body>
</html>
