<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリ会社別集計</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

{include file='web/_top_bar.tpl'}

{include file='web/_date_select.tpl' _action="./publisher.php" _date_str=$date}

{foreach from=$publishers key=index item=publisher}
	<div>
		<span class="rank">{$index+1}. {$publisher.publisher}</span> ({$publisher.count} titles) <br />
	</div>

	{foreach from=$publisher.packages key=index item=info}
		<div class="floating_minibox">
			<a href="{$info->detail_url}">
				<img src="{$HOME_URL}{$info->image_cache}" class="minibox" />
			</a>
		</div>
	{/foreach}
	<div class="clear"></div>
	<br />
{/foreach}

</body>
</html>
