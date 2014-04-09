<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリ　オブ　アプリ</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

{base64image url='img/androids.jpg' style='width: 100%;'}

{include file='web/_top_bar.tpl'}

<div align='left'>
	注目の高評価アプリ<br />
	{foreach from=$best_packages key=index item=item}
		{include file='web/_package_info.tpl' _info=$item _number=$index+1 _is_embed=true }
	{/foreach}
</div>

</body>
</html>
