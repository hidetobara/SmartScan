{*****
	日付、OSの選択
	引数
		_action
		_date_str
****}
	<form method="GET" action="{$_action}">
		OS:<select name="os">
			<option {if $os == "android"}selected{/if}>Android</option>
			<option {if $os == "ios"}selected{/if}>iOS</option>
		</select>
		日時:<input type="date" name="date" value="{$_date_str}" />
		<input type="submit" value="Show" />
	</form>
