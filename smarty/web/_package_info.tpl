{*****
	1package表示
	引数:
		_info
		_number
*****}
	<div>
		<div class="floating_cell">
			<img src="{$_info->image_url}" class="fixed_cell" />
		</div>
		<div class="floating_cell">
			<span> {$_number}. </span><br />
			<span> {$_info->title} </span><br />
			{if $_info->point}
				<span> {(int)$_info->point} point. </span>
			{/if}
		</div>
		<div class="clear">
		</div>
	</div>
