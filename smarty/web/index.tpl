<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリ情報局</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

アプリの情報
{foreach from=$best_packages key=index item=item}
	<div>
		<img src="{$item->image_url}" class="fixed_cell"> <span> {$index+1}. </span> <span> {$item->title} </span> <span>{$item->point} point.</span>
	</div>
{/foreach}

</body>
</html>
