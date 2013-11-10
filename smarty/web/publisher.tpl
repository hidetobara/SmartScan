<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリ情報局</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

{include file='web/_top_bar.tpl'}

{include file='web/_date_select.tpl' _action="./publisher.php" _date_str=$date}

{foreach from=$publishers key=index item=publisher}
	<div class="floating_box">
		{$index+1}. ({$publisher.count} titles)<br />
		{$publisher.publisher}<br />
	</div>

	{foreach from=$publisher.packages key=index item=info}
		<div class="floating_box">
			<a href="{$info->detail_url}">
				<img src="{$info->image_url}" class="fixed_cell" />
			</a>
		</div>
	{/foreach}
	<div class="clear"></div>
{/foreach}

</body>
</html>
