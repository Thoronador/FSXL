<div id="contentheader">
	<div id="contentlogo">
		NEWS ARCHIV
	</div>
</div>
<div id="contentwindow" style="padding-left:20px;">
	<form action="./">
		<input type="hidden" name="section" value="newsarchiv">
		News anzeigen vom
		<select name="month" class="textinput">
			<option value="1">Januar</option>
			<option value="2">Februar</option>
			<option value="3">März</option>
			<option value="4">April</option>
			<option value="5">Mai</option>
			<option value="6">Juni</option>
			<option value="7">Juli</option>
			<option value="8">August</option>
			<option value="9">September</option>
			<option value="10">Oktober</option>
			<option value="11">November</option>
			<option value="12">Dezember</option>
		</select>
		<select name="year" class="textinput">
			{yearoptions}
		</select>
		<input type="submit" value="los" class="button">
	</form>
</div>
<p>
{news}
<div align="right">
	<-- page -->
		<-- if currentpage -->
			<b>[{pagenum}]</b>
		<-- else currentpage -->
			<a href="{pagelink}">[{pagenum}]</a>
		<-- /if currentpage -->
	<-- /page -->
</div>
<p/>