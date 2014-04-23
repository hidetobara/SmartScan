{*****
	pie chart 表示用JavaScript
	引数: _date, _key, _os, _container
*****}
<script type="text/javascript">
	$(document).ready(function()
	{
		var options = {
			chart: {
				renderTo: '{$_container}'
			},
			title: {
				text: 'アプリ説明文内 "{$_key}" 含有率'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: true
				}
			},
			series: [{
				type: 'pie',
				name: 'Keyword count',
				data: [
					['Key',   0],
					['Other',       200]
				]
			}]
		};

		$.getJSON('key-date.php?date={$_date}&key={$_key}&os={$_os}', function(data) {
			options.series[0].data = data.data;
			var chart = new Highcharts.Chart(options);
		});
	});
</script>
