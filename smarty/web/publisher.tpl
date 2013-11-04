<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>アプリ情報局</title>
	{include file='web/_common_style.tpl'}
</head>
<body>

{include file='web/_top_bar.tpl'}

<div class="chapter_bar">Android.</div>
{foreach from=$android_publishers key=index item=publisher}
	<div class="floating_box">
		{$index+1}. ({$publisher.count} titles)<br />
		{$publisher.publisher}<br />
	</div>

	{foreach from=$publisher.packages key=index item=info}
		<div class="floating_box">
			<a href="https://play.google.com/store/apps/details?id={$info->package}">
				<img src="{$info->image_url}" class="fixed_cell" />
			</a>
		</div>
	{/foreach}
	<div class="clear"></div>
{/foreach}

<div class="chapter_bar">iOS.</div>
{foreach from=$ios_publishers key=index item=publisher}
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
