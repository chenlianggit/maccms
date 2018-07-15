var uid = MacPlayer.PlayUrl;
if(uid.indexOf('http') > -1){
	//url
	MacPlayer.Html = '<iframe allowfullscreen="true" width="100%" height="'+MacPlayer.Height+'" src="http://api.tv6.com/api/?url='+uid+'" frameborder="0" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>';
	MacPlayer.Show();
}else{
	//id
	MacPlayer.Html = '<iframe allowfullscreen="true" width="100%" height="'+MacPlayer.Height+'" src="http://api.tv6.com/api/?url='+uid+'" frameborder="0" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>';
	MacPlayer.Show();
}