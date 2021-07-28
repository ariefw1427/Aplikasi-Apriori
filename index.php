<?php
include 'functions.php';
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="favicon.ico" />

  <title>Apriori</title>
  <link href="assets/css/cyborg-bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/general.css" rel="stylesheet" />
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
</head>

<body background="gambar/obatan.jpg">
  <nav class="navbar navbar-default navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="?">Home</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <?php if ($_SESSION['login']) : ?>
            <li><a href="?m=data"><span class="glyphicon glyphicon-pushpin"></span> Data Transaksi</a></li>
            <li><a href="?m=apriori"><span class="glyphicon glyphicon-flash"></span> Proses Apriori</a></li>
            <li><a href="?m=password"><span class="glyphicon glyphicon-lock"></span> Password</a></li>
            <li><a href="aksi.php?act=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          <?php else : ?>
            <li><a href="?m=login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <?php endif ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <?php
    if (file_exists($mod . '.php'))
      include $mod . '.php';
    else
      include 'home.php';
    ?>
  </div>
  <footer class="footer">
    <div class="container ">
      <div class="row">
        <div class="col-lg-6">
          <h6>
            <font size=”3″ color=”#000000″>Copyright &copy; <?= date('Y') ?> | Moh.Arief Wicaksono</font>
          </h6>
        </div>
        <div class="col-lg-6">
          <h6>
            <em class="pull-right">
              <font size=”3″ color=”#000000″>Created 10 Januari 2021</font>
            </em>
          </h6>
        </div>
      </div>
    </div>
  </footer>
  <script type="text/javascript">
    $(document).ready(function() {
      $(document).on('focus', ':input', function() {
        $(this).attr('autocomplete', 'off');
      });
    });
  </script>
</body>

</html>