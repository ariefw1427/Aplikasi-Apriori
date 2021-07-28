<?php
require_once 'functions.php';
if ($mod == 'get_kode') {
	$tanggal = str_replace('-', '', $_POST['tanggal']);
	echo kode_oto('id_transaksi', 'tb_transaksi', "T$tanggal", 3);
	//var_dump($_POST);
}
