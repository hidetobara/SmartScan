<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリランキング</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

{include file='web/_top_bar.tpl'}

<div align='center'>
	{base64image url='img/apple.png' style=''}
</div>

{include file='web/_date_select.tpl' _action="./ranking.php" _date_str=$date}

<p>※200位までの集計となります</p>

アプリの情報
{foreach from=$packages key=index item=item}
	{include file='web/_package_info.tpl' _info=$item _number=$index+1}
{foreachelse}
	データがありません
{/foreach}

</body>
</html>
