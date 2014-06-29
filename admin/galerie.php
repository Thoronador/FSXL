<?php
$b=4;
mysql_connect("localhost", "assassinscreed", "4bI1QzMMEXw0");
mysql_select_db("db_assassinscreed");
$a=mysql_query("SELECT * FROM `alix_screen` WHERE `cat_id` =3");
while($i=mysql_fetch_assoc($a)){
$_POST[action]="newpic";
$_POST[id]="12";
$_POST[fileurl]="http://www.assassins-creed.de/images/screenshots/".$i[screen_id].".jpg";
$_POST[fileurl2]="http://www.assassins-creed.de/images/screenshots/".$i[screen_id].".png";
$_POST[fileurl3]="http://www.assassins-creed.de/images/screenshots/".$i[screen_id].".gif";
$_POST[day]="22";
$_POST[month]="10";
$_POST[year]="2009";
$_POST[hour]="00";
$_POST[min]="00";
$_POST[text]=mysql_real_escape_string("");
$_POST[position]=$b;
$_POST[title]="";
$FSXL[time]=time();
$FSXL[tableset]="fsxl";
		$endung = substr($_POST[fileurl], strlen($_POST[fileurl])-3, 3);
		if ($endung == 'jpg' || $endung == 'gif' || $endung == 'png')
		{
			if(!copy($_POST[fileurl], 'mod_gallery/tmp/tmp.'.$endung)){
        $endung="png";
        if(!copy($_POST[fileurl2], 'mod_gallery/tmp/tmp.'.$endung)){
          $endung="gif";
          copy($_POST[fileurl3], 'mod_gallery/tmp/tmp.'.$endung);
        }
      }
			$file_arr = array();
			$file_arr[tmp_name] = 'mod_gallery/tmp/tmp.'.$endung;
			$file_arr[type] = 'image/'.$endung;
			$file_arr[path] = 'mod_gallery/tmp/';
			$chk = createGalleryPic($file_arr, $_POST[id], $_POST[position], $_POST[title], $_POST[text], $date);
			unlink('mod_gallery/tmp/tmp.'.$endung);
		}
    if ($chk)
	{
		settype($_POST[id], 'integer');
		mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `pics` = `pics` + 1 WHERE `id` = $_POST[id]");
    echo "<b>".$i[screen_id]."</b><br>";
	} else {
  echo $i[screen_id]."<br>";
  }
  $b++;
}
function createGalleryPic($imgfile, $id, $position, $title, $text, $release)
{
	global $FSXL;

	settype($id, 'integer');
	settype($position, 'integer');
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_gallerypics` (`id`, `galleryid`, `titel`, `text`, `position`, `date`, `hits`, `release`)
							VALUES (NULL, '$id', '$title', '$text', '$position', $FSXL[time], 0, '$release')");
	if ($index)
	{
		$picid = mysql_insert_id();
		$hash = md5($FSXL[time].$picid);

		// Gallerydaten auslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` WHERE `id` = $id");
		$gallery = mysql_fetch_assoc($index);

		$img = new imgConvert();
		if ($img->readIMG($imgfile))
		{
			$img->saveIMG('../images/gallery/'.$id.'/', $hash, 'jpg');

			$img->scaleIMG($gallery[thumbx], $gallery[thumby], 'LETTERBOX', $gallery[color]);
			$img->saveIMG('../images/gallery/'.$id.'/', $hash.'s', 'jpg');

			return true;
		}
		else
		{
			mysql_query("DELETE FROM `$FSXL[tableset]_gallerypics` WHERE `id` = $picid");
			return false;
		}
	}
	else
	{
		return false;
	}
}
class imgConvert
{
	var $sourceimg = false;
	var $sourcewidth = 0;
	var $sourceheight = 0;
	var $sourceaspect = 0;
	var $sourcetype = false;
	var $output = false;
	var $filename = '';
	
	// Bild einlesen
	function readIMG($img)
	{
		// Bildtyp auswerten
		$imginfo = getimagesize($img['tmp_name']);
		$this->filename = $img['tmp_name'];
		switch ($imginfo[2])
		{
			case 2: // JPG
				$this->sourceimg = imagecreatefromjpeg($img['tmp_name']);
				$this->sourcetype = 'JPG';
				break;
			case 1: // GIF
				$this->sourceimg = imagecreatefromgif($img['tmp_name']);
				$this->sourcetype = 'GIF';
				break;
			case 3: // PNG
				$this->sourceimg = imagecreatefrompng($img['tmp_name']);
				imageAlphaBlending($this->sourceimg, false);
				imageSaveAlpha($this->sourceimg, true);
				$this->sourcetype = 'PNG';
				break;
			case 6: // BMP
			case 15: // WBMP
				$this->sourceimg = imagecreatefromwbmp($img['tmp_name']);
				$this->sourcetype = 'BMP';
				break;
			default:
				return false;
		}
		
		$this->sourcewidth = $imginfo[0];
		$this->sourceheight = $imginfo[1];
		$this->sourceaspect = $imginfo[0] / $imginfo[1];
		return true;
	}
	
	// Bild skalieren
	function scaleIMG($width, $height, $mode, $bgcolor, $transparent=false)
	{
		$width = $width==0 ? 1 : $width;
		$height = $height==0 ? 1 : $height;
	
		// Methode auswählen
		switch (strtoupper($mode))
		{
			case 'SCALE_TO_WIDTH':
				$height = round($width/$this->sourceaspect);
				$offset = array(0, 0, $width, $height);
				break;
			case 'SCALE_TO_HEIGHT':
				$width = round($height*$this->sourceaspect);
				$offset = array(0, 0, $width, $height);
				break;
			case 'LETTERBOX':
				if ($this->sourceaspect >= $width/$height) {
					$offset[2] = $width;
					$offset[3] = round($this->sourceheight / ($this->sourcewidth / $width));
					$offset[0] = 0;
					$offset[1] = round(($height - $offset[3]) / 2);
				}
				else {
					$offset[2] = round($this->sourcewidth / ($this->sourceheight / $height));
					$offset[3] = $height;
					$offset[0] = round(($width - $offset[2]) / 2);
					$offset[1] = 0;
				}
				break;
			case 'CROP':
				if ($width/$height >= $this->sourceaspect) {
					$offset[2] = $width;
					$offset[3] = round($this->sourceheight / ($this->sourcewidth / $width));
					$offset[0] = 0;
					$offset[1] = round(($height - $offset[3]) / 2);
				}
				else {
					$offset[2] = round($this->sourcewidth / ($this->sourceheight / $height));
					$offset[3] = $height;
					$offset[0] = round(($width - $offset[2]) / 2);
					$offset[1] = 0;
				}
				break;
			case 'RESIZE':
				if ($this->sourceaspect >= $width/$height) {
					$offset[0] = 0;
					$offset[1] = 0;
					$offset[2] = $width;
					$offset[3] = round($this->sourceheight / ($this->sourcewidth / $width));
					$height = $offset[3];
				}
				else {
					$offset[0] = 0;
					$offset[1] = 0;
					$offset[2] = round($this->sourcewidth / ($this->sourceheight / $height));
					$offset[3] = $height;
					$width = $offset[2];
				}
				break;
			default:
				return false;
		}
		
		// Bild erzeugen
		$this->outputimg = imagecreatetruecolor($width, $height);
		
		// Hintergrundfarbe
		$bg = $this->ImageColorAllocateFromHex($this->outputimg, $bgcolor);
		imagefill($this->outputimg, 0, 0, $bg);

		// Transparenter Hintergrund
		if ($transparent) {
			if ($this->sourcetype == 'PNG') {
				imageAlphaBlending($this->outputimg, false);
				imageSaveAlpha($this->outputimg, true);
				$alpha = imagecolorallocatealpha($this->outputimg, 0, 0, 0, 127);
				imagefill($this->outputimg, 0, 0, $alpha);
			}
			imagecolortransparent($this->outputimg, $bg);
		}
		
		// Quelle kopieren
		imagecopyresampled($this->outputimg, $this->sourceimg, $offset[0], $offset[1], 0, 0, $offset[2], $offset[3], $this->sourcewidth, $this->sourceheight);
	}
	
	// Bild speichern
	function saveIMG($destination, $filename, $type=false, $quality=85)
	{
		// Falls noch kein Bild vorhanden ist
		if ($this->outputimg == false) {
			$this->outputimg = $this->sourceimg;
		}
		
		// Ausgabeformat bestimmen
		$type = $type ? strtoupper($type) : $this->sourcetype;
		
		// Alte Bilder löschen
		unlink($destination.$filename.'.png');
		unlink($destination.$filename.'.gif');
		unlink($destination.$filename.'.jpg');
		unlink($destination.$filename.'.bmp');
		
		switch ($type)
		{
			case 'JPG':
				$chk = imagejpeg($this->outputimg, $destination.$filename.'.jpg', $quality);
				break;
			case 'GIF':
				$chk = imagegif($this->outputimg, $destination.$filename.'.gif');
				break;
			case 'PNG':
				$chk = imagepng($this->outputimg, $destination.$filename.'.png');
				break;
			case 'BMP':
				$type = 'PNG';
				$chk = imagepng($this->outputimg, $destination.$filename.'.png');
				break;
			case 'COPY':
				$type = $this->sourcetype;
				$chk = move_uploaded_file($this->filename, $destination.$filename.'.'.strtolower($type));
				chmod ($destination.$filename.'.'.$type, 0644);
				break;
			default:
				return false;
		}
		
		if ($chk) {
			$size = filesize($destination.$filename.'.'.strtolower($type));
			$solution = getimagesize($destination.$filename.'.'.strtolower($type));
			return array($solution[0], $solution[1], $size, $type);
		}
		// Bild konnte nicht erstellt werden
		else {
			return false;
		}
	}
	
	// Hexadezimalfarben umwandeln
	function ImageColorAllocateFromHex($img, $hexstr)
	{
		$int = hexdec($hexstr);
		return ImageColorAllocate ($img, 0xFF & ($int >> 0x10), 0xFF & ($int >> 0x8), 0xFF & $int);
	} 
}
echo "...";
?>