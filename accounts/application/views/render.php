
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo "Barcode Generator " ?></title>
  <link rel="stylesheet" href="">
  <style>
    body{
      text-align: center;
    }
  </style>
</head>
<body>
  <h1 align="center"><?php echo "Barcode Generator " ?></h1>
  
  <!-- <h3>Ini render QRcode</h3>
  <img src="<?php   //echo site_url('Render/QRcode/'.$kode); ?>" alt=""> -->
  <p><h3>Ini render Barcode</h3></p>
  <img src="<?php echo site_url('assets/barcode/'.$image); ?>" height="200" width="300"/>
  <br>

  
</body>
</html>