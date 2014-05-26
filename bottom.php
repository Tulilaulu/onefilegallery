    <title>Galleria</title>
  </head>
  <body>

  <div id='fade'></div>
  <div id='objectwrapper'>
    <img src=""/>
    <a href="">
      <img src="<?php echo GALLERYFOLDERURL."/".$GLOBALS['generalFileIcon'] ?>"/>
      <span>Right click to download</span>
    </a>
  </div>

<?php 
//ini_set("display_errors", 1);

$path = $_SERVER["PATH_INFO"];
$showItem = true;
if ($path == NULL){ //if no path is given, start from the root
  $path = '*';
  $showItem = false;
}
else{
  if (is_dir(substr($path, 1))){
    $path = __DIR__.$path.'/*'; //star for getting all items in folder
    $showItem = false;
  }
}

  showNavigation($path, GALLERYROOT);
  echo "<div id='main'>";

  if ($showItem){ //signal to javascript that an item should be shown
    $url = dirname(GALLERYROOT).$path;
    echo "<script>var url = '$url'; </script>";
    $path = __DIR__.dirname($path).'/*';
  }
  
  $items = glob($path, GLOB_MARK);
  $items = removeGallerysFiles($items); //make sure files of the gallery are ignored

  foreach ($items as $item){  
    makeTileElement($item);
  }


//  echo "<br><br><br><br>";
//  var_dump($_SERVER);

  ?>
  </div>
  </body>
</html>
