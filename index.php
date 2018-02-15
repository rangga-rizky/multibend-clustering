<?php
//include "function.php";
include "cluster.php";

for ($i=1; $i < 7; $i++) { 
	$im= imagecreatefromstring(file_get_contents("satelit/gb".$i.".GIF"));
	$height = imagesy($im);
    $width = imagesx($im);
    $hist = array();

    $pixel = 0;

    for ($w=0; $w < $width; $w++) { 
    	for ($h=0; $h < $height; $h++) { 
    		$rgb = imagecolorat($im, $w, $h);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;  
			$grey = intdiv(($r+$g+$b),3);
			$dataset[$pixel][0][$i-1] =$grey;
			$dataset[$pixel][0][7] = $pixel;
			$pixel++;
    	}	
    }
   	
}

$single=new Cluster($dataset);
if(isset($_GET["k"])){
	$k = $_GET["k"];
}
else{
	$k=3;
}
$single->singleLinkage($k);
$clusters=$single->getCluster();

$result = [];
foreach ($clusters as $number_of_cluster => $cluster) {
	foreach ($cluster as $data) {
		switch ($number_of_cluster) {
		case 0:
			$result[$data[7]]["r"] = 255;
			$result[$data[7]]["g"] = 0;
			$result[$data[7]]["b"] = 0;
			break;
		case 1:
			$result[$data[7]]["r"] = 0;
			$result[$data[7]]["g"] = 255;
			$result[$data[7]]["b"] = 0;
			break;
		case 2:
			$result[$data[7]]["r"] = 0;
			$result[$data[7]]["g"] = 0;
			$result[$data[7]]["b"] = 255;
			break;		
		case 3:
			$result[$data[7]]["r"] = 0;
			$result[$data[7]]["g"] = 255;
			$result[$data[7]]["b"] = 255;
			break;	
		case 4:
			$result[$data[7]]["r"] = 255;
			$result[$data[7]]["g"] = 255;
			$result[$data[7]]["b"] = 0;
			break;		
		default:
			$result[$data[7]]["r"] = 0;
			$result[$data[7]]["g"] = 0;
			$result[$data[7]]["b"] = 255;
			break;
	}
	}
}

$img = imagecreatetruecolor($width, $height);
$pixel = 0;
for ($w=0; $w < $width; $w++) { 
   	for ($h=0; $h < $height; $h++) { 
        $val = imagecolorallocate($img, $result[$pixel]["r"], $result[$pixel]["g"], $result[$pixel]["b"]);
        imagesetpixel ($img, $w, $h, $val);
        $pixel++;
    }
}

imagejpeg($img,"result.jpg");

?>

<!DOCTYPE html>
<html>
<head>
	<title>Landsat 7</title>
</head>
<body style="background-color: #E0F7FA">
	<div align="center" style="margin-top: 50px;">
		<h1>MultiBend Clustering pada Landsat 7</h1>
	<?php for ($i=1; $i < 7; $i++) { ?>
		<img src="satelit/gb<?php echo $i?>.GIF" widht="150px" height="150px">
	<?php } ?>
	</div>
	<br>
	<div align="center">
		<h3>Hasil cluster = 3,4 dan 5</h3>
		<img src="result3.jpg" widht="200px" height="200px">
		<img src="result4.jpg" widht="200px" height="200px">
		<img src="result5.jpg" widht="200px" height="200px">
	</div>

</body>
</html>
