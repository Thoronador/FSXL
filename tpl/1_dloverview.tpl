<div id="contentheader">
	<div id="contentlogo">
		<b>Downloads</b>
	</div>
</div>
<div id="contentwindow">
	<div style="padding:10px;">
		<div style="height:38px;">
			<img border="0" src="images/styles/froggreen/download/root.gif" alt="" style="float:left; margin-right:8px;">
			<b>{pagetitle}</b><br>
			Stammverzeichnis
		</div>
	<-- folder -->
		<div style="height:38px; margin-left:{deep}px;">
			<a href="?section=download&folder={folderid}">
				<img border="0" src="images/styles/froggreen/download/folder<-- if selected_folder -->2<-- /if selected_folder -->.gif" alt="" style="float:left; margin-right:8px;">
			</a>
			<b>{foldername} ({numfiles})</b><br>
			{foldertext}
		</div>
	<-- /folder -->
	<-- file -->
		<div style="height:18px; margin-left:{deep}px;">
			<a href="?section=download&id={downloadid}">
				<img border="0" src="images/styles/froggreen/download/file.gif" alt="" width="16" height="16" style="margin-bottom:-5px;">
				{downloadname}
			</a>
		</div>
	<-- /file -->
	</div>
</div>
