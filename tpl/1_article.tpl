<div id="contentheader">
	<div id="contentlogo">
		<span style="float:right">{date}</span>
	<-- if has_cat -->
		[<a href="{caturl}" style="color:#FFFFFF;">{catname}</a>]
	<-- /if has_cat -->
		{title}
	<-- if pages -->
		<i>(Seite: {currentpage})</i>
	<-- /if pages -->
	</div>
</div>
<div id="contentwindow">
	<div style="width:90%; margin:0px auto;">
		<br>{text}
	</div>
	<p>
	<-- if pages -->
		<div style="float:left">
			Seite:
			<-- pagelink -->
				<a href="{pagelink}">[{pagenum}]</a>
			<-- /pagelink -->
		</div>
	<-- /if pages -->
	<div style="text-align:right;">
		<-- if show_user -->
		geschrieben von {username}
		<-- /if show_user -->
	</div>
</div>
