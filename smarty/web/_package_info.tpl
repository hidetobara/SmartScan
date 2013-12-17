{*****
	1package表示
	引数:
		_info
		_number
*****}
	<div>
		<div class="floating_cell">
			<a href="{$_info->detail_url}">
				<img src="{$_info->image_url}" class="fixed_cell" />
			</a>
		</div>
		<div class="floating_cell">
			{if $_number}
				<span> {$_number}. </span><br />
			{elseif $_info->rank}
				<span> {$_info->rank}. </span><br />
			{/if}

			<span> {$_info->title} </span><br />

			{if $_info->point}
				<span> {(int)$_info->point} point. </span><br />
			{/if}

			<span><a href="transition.php?package={$_info->package}&amp;os={$_info->os}"> trans &gt;&gt;&gt; </a></span>
		</div>
		<div class="clear">
		</div>
	</div>
