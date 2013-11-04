<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリ情報局</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

{include file='web/_top_bar.tpl'}

注目の高評価アプリ
{foreach from=$best_packages key=index item=item}
	{include file='web/_package_info.tpl' _info=$item _number=$index+1}
{/foreach}


</body>
</html>
