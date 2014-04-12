{*****
	1package表示
	引数:
		_info: パッケージ情報
		_number: 順位、マイナスにすると表示しない
		_is_embed: 埋め込み画像か
*****}
	<div>
		<div class="floating_cell">
			<a href="{$_info->detail_url}">
				{if !$_is_embed}
					<img src="{$HOME_URL}{$_info->image_cache}" class="fixed_cell" />
				{else}
					{base64image url=$_info->image_cache class="fixed_cell"}
				{/if}
			</a>
		</div>
		<div class="floating_cell">
			{if $_number && $_number > 0}
				<span class='rank'> {$_number}. </span>
			{elseif $_info->rank}
				<span class='rank'> {$_info->rank}. </span>
			{/if}

			<span> {$_info->title} </span><br />

			{if $_info->point}
				<span> {(int)$_info->point} point. </span><br />
			{/if}

			<span><a href="transition.php?package={$_info->package}&amp;os={$_info->os}"> ランキング変動 &gt;&gt;&gt; </a></span>
		</div>
			<div class="clear" />
		</div>
	</div>
