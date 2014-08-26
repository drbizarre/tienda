<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digital Media</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="navbar navbar-inverse" role="navigation">
     <?php echo $navbar; ?>
    </div>

   <div class="container">
    <div class="row">
      <div class="col-md-2">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Departamentos</h3>
          </div>
          <div class="panel-body">
            <?php echo $menu; ?>
          </div>
        </div>        
      </div>
      <div class="col-md-10">
         <?php echo $slider; ?>
         <?php echo $ofertas; ?>
         <?php echo $destacados; ?>
      </div>
    </div>
   </div>
   <div class="container">
      <!-- FOOTER -->
      <footer>
        <?php echo $footer; ?>
      </footer>    
   </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>