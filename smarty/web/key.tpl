<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>キーワード含有率</title>
	{include file='web/_common_style.tpl'}
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	{include file='web/_key_piechart.tpl' _container='container1' _date=$date _key='RPG' _os=$os}
	{include file='web/_key_piechart.tpl' _container='container2' _date=$date _key='イケメン' _os=$os}
	{include file='web/_key_piechart.tpl' _container='container3' _date=$date _key='恋愛' _os=$os}
	{include file='web/_key_piechart.tpl' _container='container4' _date=$date _key='CRIWARE' _os=$os}
</head>
<body>

<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>

<br />
<br />
{include file='web/_date_select.tpl' _action="./key.php" _date_str=$date}

<div id="container1" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
<div id="container2" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
<div id="container3" style="min-width: 310px; height: 300px; margin: 0 auto"></div>
<div id="container4" style="min-width: 310px; height: 300px; margin: 0 auto"></div>

{include file='web/_top_bar.tpl'}

</body>
</html>
