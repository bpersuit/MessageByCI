<?php
	header("content-type:image/png");
	$validateLength=4;
	$strToDraw="";
	$chars=[
  		"0","1","2","3","4",
  		"5","6","7","8","9",
 	 	"a","b","c","d","e","f","g",
  		"h","i","j","k","l","m","n",
  		"o","p","q","r","s","t",
  		"u","v","w","x","y","z",
  		"A","B","C","D","E","F","G",
  		"H","I","J","K","L","M","N",
  		"O","P","Q","R","S","T",
  		"U","V","W","X","Y","Z"
	];
	 $imgW=80;
	 $imgH=25;
	 $imgRes=imagecreate($imgW,$imgH);
	 $imgColor=imagecolorallocate($imgRes,255,255,100);
	 $color=imagecolorallocate($imgRes,0,0,0);
	$sessioncode = "";
	for($i=0;$i<$validateLength;$i++){
  		$rand=rand(1,58);
  		$strToDraw=$strToDraw." ".$chars[$rand];
  		$sessioncode = $sessioncode."".$chars[$rand];
	}
 	$_SESSION['code'] = $sessioncode;
 	setcookie('code',$sessioncode,-1,'/MessageByCI/');
	 imagestring($imgRes,5,0,5,$strToDraw,$color);
	 for($i=0;$i<100;$i++){
   		imagesetpixel($imgRes,rand(0,$imgW),rand(0,$imgH),$color);
	 }
	
	 imagepng($imgRes);
	 imagedestroy($imgRes);
?>