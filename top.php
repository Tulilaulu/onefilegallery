<?php
//CONFIG
$backgroundcolor = "#EEFFEE";
$directoryBoxColor = "#88AA88";
$fileBoxColor = "#449944";
$navigationBackgroundColor = "#88AA88";
$navigationElementColor = "#AAEEAA";
$navigationLinkColor = "#FFFFFF";
$navigationElementBorderColor = "#22DD22";
$navigationSeparatorColor = "#22DD22";
$imageBorderWidth = "5px";
$imageBorderColor = "#FFFFFF";
$imageBorderRadius = "2px";
$elementNameColor = "#FFFFFF";
$thumbnailBGColor = "#77DD77";

$GLOBALS['generalFileIcon'] = "file.svg";//type filename here for file icon (for non-picture files)
$GLOBALS['navigationSeparator'] = "&gt;";

//Code, do not change

//gallery url with index.php
define("GALLERYROOT", "http://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]); 
//gallery url withou index.php
define("GALLERYFOLDERURL", "http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"]));
$GLOBALS['pictureextensions'] =["jpg", "gif", "jpeg", "bmp", "png"];

function removeGallerysFiles($items){
  //TODO: remove files that are deleted when moving
  $removables = array("index.php", "_thumbs/", $GLOBALS['generalFileIcon'], "top.php", "bottom.php", "styles.css", "script.js"); //add ignored files here
  $items = array_diff($items, $removables);
  return $items;
}

//TODO: make separate cases for different dimensions
function makeThumbnail($src, $dest, $desired_width) {
//edited from
//davidwalsh.name/create-image-thumbnail-php
  $source_image = imagecreatefromstring(file_get_contents($src));
  $width = imagesx($source_image);
  $height = imagesy($source_image);
  $desired_height = floor($height * ($desired_width / $width));
  $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
  /* copy source image at a resized size */
  imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height); 
  imagejpeg($virtual_image, $dest);
}

function getThumbnailForPicture ($item){
  $thumburl = GALLERYFOLDERURL."/_thumbs/"; 
  $thumbpath = __DIR__."/_thumbs/"; 
  if (!file_exists($thumbpath)){ //check for folder
    mkdir($thumbpath);
  }
  $thumbpath = $thumbpath.basename($item);
  $thumburl = $thumburl.basename($item);
  if (!file_exists($thumbpath)){
    makeThumbnail($item, $thumbpath, 140);
  }
  return $thumburl;
}

function getThumbnailForDirectory ($item){
  if (isset($_SERVER["PATH_INFO"])){
    $dirpath = __DIR__.$_SERVER["PATH_INFO"]."/".basename($item);
  }
  else{
    $dirpath = __DIR__."/".basename($item);
  }
  $dirIterator = new RecursiveDirectoryIterator($dirpath);
  $image = ""; //TODO: More recursion
  foreach ($dirIterator as $file){
    if (is_file($file)){
      $ext = strtolower(pathinfo($file)['extension']);
      if (in_array($ext, $GLOBALS['pictureextensions'] )){
        $image = $file;
        break;
      }
    }
  }
  $thumburl = GALLERYFOLDERURL."/_thumbs/";
  return $thumburl.basename($image);
}

function showNavigation ($path){
  echo "<div id='navigation'><div id='navigation-inner'>";
  echo "<a href='".GALLERYROOT."'>Home</a>";
  if (isset($_SERVER["PATH_INFO"])){
    $folder = $_SERVER["PATH_INFO"];
    $folders = explode('/', $folder); 
    $path = GALLERYROOT;
    foreach ($folders as $folder){
      if ($folder != ""){
        $path = $path."/".$folder;
        echo " $GLOBALS[navigationSeparator] <a href='".htmlspecialchars($path)."'>".htmlspecialchars($folder)."</a>";
      }
    }
  }
  echo "</div></div>";
}

function makeTileElement ($elem){
  $elementname = htmlspecialchars(basename($elem));
  $path = GALLERYROOT;
  if (isset($_SERVER["PATH_INFO"])){
    $path = $path.$_SERVER["PATH_INFO"];
  }
  $path = $path."/".$elementname;
  if (is_dir($elem)){
    makeDirectoryElement($path);
  }  
  else{ //is a file
    $extension = strtolower(pathinfo($elem)['extension']);
    if (in_array($extension, $GLOBALS['pictureextensions'])){ //is image 
      $image = getThumbnailForPicture($elem);
    }
    else{
      $image = GALLERYFOLDERURL."/".$GLOBALS['generalFileIcon'];
    }
    $elementname = htmlspecialchars($elementname);  
echo <<<LINE
<div class='file' id='$elementname'>
<a href='$path'>
<div class='imgwrap'>
<img src='$image'>
</div>
<span>$elementname</span>
</a></div>
LINE;
  }
}

function makeDirectoryElement ($url){
    $elementname = basename($url);
    $path = GALLERYROOT."/".$elementname;
    $image = GetThumbnailForDirectory($path);
    $elementname = htmlspecialchars($elementname);
    echo "<div class='directory' id='$elementname'><a href='$url'>";
    echo "<img src='$image'><span>".$elementname."/</span></a></div>";
}


?>
<!DOCTYPE html>
<html>
  <head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Dosis:300,400,600' rel='stylesheet' type='text/css'>
