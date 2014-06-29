function getheadlines(){
  var max = 4;
  if(threads.length < max) max = threads.length;
  for(i = 0; i < max; i++){
    if (threads[i].title.length > 25){
      threads[i].title = threads[i].title.substring(0, 25) + "...";
    }
    document.getElementById('forenticker').innerHTML+='<span style="font-size: 10px;">'+threads[i].threaddate+' '+threads[i].threadtime+' <a target="_blank" href="http://forum.worldofplayers.de/forum/showthread.php?t=' + threads[i].threadid + '">' + threads[i].title + '</a></span><br>';
  }
}