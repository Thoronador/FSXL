var idTextfeld = 'ctext';

var rangeIE = null;

function insertSmilie(code)
{
	insertText('', code);
}
function insertFSCode(tag, add)
{
	if (add) insertText('[' + tag + '=' + add + ']', '[\/' + tag + ']');
	else insertText('[' + tag + ']', '[\/' + tag + ']');
}

function insertText(vor, nach)
{
	var textfeld = document.getElementById(idTextfeld);
	textfeld.focus();

	// IE, Opera
	if(typeof document.selection != 'undefined')
	{
		insertIE(textfeld, vor, nach);
	}
	// Gecko
	else if (typeof textfeld.selectionStart != 'undefined')
	{
		insertGecko(textfeld, vor, nach);
	}
}

// IE, OPERA
function insertIE(textfeld, vor, nach)
{
	if(!rangeIE) rangeIE = document.selection.createRange();

	if(rangeIE.parentElement().id != idTextfeld)
	{
		rangeIE = null;
		return;
	}

	var alterText = rangeIE.text;
	rangeIE.text = vor + alterText + nach;

	if (alterText.length == 0) rangeIE.move('character', -nach.length);
	else rangeIE.moveStart('character', rangeIE.text.length);
     
	rangeIE.select();
	rangeIE = null;
}

// Gecko
function insertGecko(textfeld, vor, nach)
{
	von = textfeld.selectionStart;
	bis = textfeld.selectionEnd;

	anfang = textfeld.value.slice(0, von);
	mitte  = textfeld.value.slice(von, bis);
	ende   = textfeld.value.slice(bis);

	textfeld.value = anfang + vor + mitte + nach + ende;

	if(bis - von == 0)
	{
		textfeld.selectionStart = von + vor.length;
		textfeld.selectionEnd = textfeld.selectionStart;
	}
	else
	{
		textfeld.selectionEnd = bis + vor.length + nach.length;
		textfeld.selectionStart = textfeld.selectionEnd;
	}
}