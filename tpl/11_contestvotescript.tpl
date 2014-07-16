<script type="text/javascript">
	function genVoteButtons(entryid)
	{
		document.writeln('<div id="entry'+entryid+'">');
		document.writeln('<img id="star_'+entryid+'_1" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar('+entryid+', 1)" onclick="voteEntry('+entryid+', 1)">');
		document.writeln('<img id="star_'+entryid+'_2" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar('+entryid+', 2)" onclick="voteEntry('+entryid+', 2)">');
		document.writeln('<img id="star_'+entryid+'_3" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar('+entryid+', 3)" onclick="voteEntry('+entryid+', 3)">');
		document.writeln('<img id="star_'+entryid+'_4" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar('+entryid+', 4)" onclick="voteEntry('+entryid+', 4)">');
		document.writeln('<img id="star_'+entryid+'_5" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar('+entryid+', 5)" onclick="voteEntry('+entryid+', 5)">');
		document.writeln('</div>');
	}
	function highStar(entryid, starnum)
	{
		document.getElementById('star_'+entryid+'_1').src = "images/styles/froggreen/star_empty.gif";
		document.getElementById('star_'+entryid+'_2').src = "images/styles/froggreen/star_empty.gif";
		document.getElementById('star_'+entryid+'_3').src = "images/styles/froggreen/star_empty.gif";
		document.getElementById('star_'+entryid+'_4').src = "images/styles/froggreen/star_empty.gif";
		document.getElementById('star_'+entryid+'_5').src = "images/styles/froggreen/star_empty.gif";

		for (i=1; i<=starnum; i++)
		{
			document.getElementById('star_'+entryid+'_'+i).src = "images/styles/froggreen/star_full.gif";
		}
	}
	function voteEntry(entryid, points)
	{
		http_request = false;
		if (window.XMLHttpRequest) // Mozilla, Safari,...
		{
			http_request = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // IE
		{
			try
			{
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					http_request = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e) {}
			}
		}
		if (!http_request)
		{
			alert("Ende :( Kann keine XMLHTTP-Instanz erzeugen");
			return false;
		}
		params="entryid="+entryid+"&points="+points;
		http_request.open("POST", "inc/votecontest.php", true);
		http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http_request.setRequestHeader("Content-length", params.length);
		http_request.setRequestHeader("Connection", "close");
		http_request.onreadystatechange = setVoteButtons;
		http_request.send(params);
	}
	function setVoteButtons()
	{
		if (http_request.readyState == 4)
		{
			if (http_request.responseText)
			{
				var result = http_request.responseText.split('_');
				var newHTML = "";
				for (i=1; i<=result[1]; i++)
				{
					newHTML += '<img border="0" src="images/styles/froggreen/star_full2.gif" alt=""> ';
				}
				for (i; i<=5; i++)
				{
					newHTML += '<img border="0" src="images/styles/froggreen/star_empty.gif" alt=""> ';
				}
				document.getElementById('entry'+result[0]).innerHTML = newHTML;
			}
		}
	}
	function genVoteButtons2(points)
	{
		document.writeln('<div>');
		for (i=1; i<=points; i++)
		{
			document.writeln('<img border="0" src="images/styles/froggreen/star_full2.gif" alt="">');
		}
		for (i; i<=5; i++)
		{
			document.writeln('<img border="0" src="images/styles/froggreen/star_empty.gif" alt="">');
		}
		document.writeln('</div>');
	}
</script>