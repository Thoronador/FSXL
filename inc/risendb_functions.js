var rdb_items = new Array();

function showItem(id) {
	itt = document.getElementById("item_tooltip");
	itt.innerHTML = '';
	
	if (rdb_items[id]) {
		itt.innerHTML = rdb_items[id];
		showITT();
	}
	else {
		requestItemCode(id);
		showITT();
	}
}
function hideItem() {
	hideITT();
}

// Http Request
function requestItemCode(id) {
	http_request = false;

	if (window.XMLHttpRequest) // Mozilla, Safari,...
	{
		http_request = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) // IE
	{
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}
		}
	}

	if (!http_request) {
		alert("Ende :( Kann keine XMLHTTP-Instanz erzeugen");
		return false;
	}
	params="id="+id;
	http_request.onreadystatechange = new Function('fx', 'updateItemTooltip("'+id+'")');
	http_request.open("POST", "/inc/risendb_getitem.php", true);
	http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_request.setRequestHeader("Content-length", params.length);
	http_request.setRequestHeader("Connection", "close");
	http_request.send(params);
}
function updateItemTooltip(id) {
	if (http_request.readyState == 4) {
		itt = document.getElementById("item_tooltip");
		itt.innerHTML = http_request.responseText;
		rdb_items[id] = http_request.responseText;
	}
}

// Tooltip Position updaten
function updateITT(e) {
	itt = document.getElementById("item_tooltip");
	if (itt != null) {
		x = (document.all) ? window.event.x + wmtt.offsetParent.scrollLeft : e.pageX;
		y = (document.all) ? window.event.y + wmtt.offsetParent.scrollTop  : e.pageY;
		//if (y - document.body.scrollTop + itt.offsetHeight > document.body.clientHeight) {
		//	y -= itt.offsetHeight + 10;
		//}
		itt.style.left = (x + 10) + "px";
		itt.style.top   = (y + 10) + "px";
	}
}

// Tooltip anzeigen
function showITT() {
	itt = document.getElementById("item_tooltip");
	itt.style.display = "block";
}

// Tooltip verbergen
function hideITT() {
	itt = document.getElementById("item_tooltip");
	itt.style.display = "none";
}

document.onmousemove = updateITT;


// Karte wechseln
function changeMap(map, x, y) {
	document.getElementById("rdb_map").src = "/images/risendb/map_"+map+".png";
	document.getElementById("rdb_pointer").style.left = x+"px";
	document.getElementById("rdb_pointer").style.top = y+"px";
}

function changeMultiMap(map, n, o, s, w, x, y) {
	document.getElementById("rdb_map").src = "/images/risendb/map_"+map+".png";

	var g_width = Math.abs(w - o);
	var m_width = x;
	var f_width = g_width / m_width;
	var g_height = Math.abs(n - s);
	var m_height = y;
	var f_height = g_height / m_height;
	
	var divs = document.getElementsByTagName('div');
	for (var i=0; i<divs.length; i++) {
		var coords = divs[i].id.split(",");
		if (coords[0] == "rdb_pointer") {
			coords[1] = parseInt(coords[1]);
			coords[2] = parseInt(coords[2]);
			if (coords[1]>=w && coords[1]<=o && coords[2]>=s && coords[2]<=n) {
				divs[i].style.visibility = "visible";
				var xpos = Math.round(Math.abs(coords[1] - w) / f_width) - 5;
				var ypos = y - Math.round(Math.abs(coords[2] - s) / f_height) - 5;
				divs[i].style.left = xpos+"px";
				divs[i].style.top = ypos+"px";
			} else {
				divs[i].style.visibility = "hidden";
			}
		}
	}
}
