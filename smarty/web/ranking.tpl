<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリランキング</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

{include file='web/_top_bar.tpl'}

<form method="GET" action="./ranking.php">
	OS:<select name="os">
		<option>Android</option>
		<option>iOS</option>
	</select>
	日時:<input type="date" name="date" value="{$date_str}" />
	<input type="submit" value="Show" />
</form>

アプリの情報
{foreach from=$packages key=index item=item}
	{include file='web/_package_info.tpl' _info=$item _number=$index+1}
{foreachelse}
	データがありません
{/foreach}

</body>
</html>
