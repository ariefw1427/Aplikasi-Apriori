<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Cetak Laporan</title>
    <link href="assets/css/default-bootstrap.min.css" rel="stylesheet" />
    <style>
        h1 {
            font-size: 20px;
            border-bottom: 4px double #000;
            padding: 3px 0;
        }

        .wrapper {
            max-width: 1024px;
            margin: 0 auto;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="wrapper">
        <?php

        if (is_file($mod . '_cetak.php'))
            include $mod . '_cetak.php';
        ?>
    </div>
</body>

</html>