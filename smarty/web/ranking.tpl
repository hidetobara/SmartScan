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

<div>
	ページ移動 &gt;
	{foreach from=$pager->enablePages item=index}
		<a href="./ranking.php?date={$date}&amp;os={$os}&amp;page={$index}"> {$index} </a>
	{/foreach}
</div>
<br />

{foreach from=$pager->currentItems key=index item=item}
	{include file='web/_package_info.tpl' _info=$item}
{foreachelse}
	データがありません
{/foreach}
<br />

<div>
	ページ移動 &gt;
	{foreach from=$pager->enablePages item=index}
		<a href="./ranking.php?date={$date}&amp;os={$os}&amp;page={$index}"> {$index} </a>
	{/foreach}
</div>
<p>※200位までの集計となります</p>

</body>
</html>
