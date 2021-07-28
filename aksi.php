<?php
require_once 'functions.php';

/** login */
if ($mod == 'login') {
    $user = esc_field($_POST['user']);
    $pass = esc_field($_POST['pass']);

    $row = $db->get_row("SELECT * FROM tb_admin WHERE user='$user' AND pass='$pass'");
    if ($row) {
        $_SESSION['login'] = $row->user;
        redirect_js("index.php");
    } else {
        print_msg("Salah kombinasi username dan password.");
    }
} else if ($mod == 'password') {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $pass3 = $_POST['pass3'];

    $row = $db->get_row("SELECT * FROM tb_admin WHERE user='$_SESSION[login]' AND pass='$pass1'");

    if ($pass1 == '' || $pass2 == '' || $pass3 == '')
        print_msg('Field bertanda * harus diisi.');
    elseif (!$row)
        print_msg('Password lama salah.');
    elseif ($pass2 != $pass3)
        print_msg('Password baru dan konfirmasi password baru tidak sama.');
    else {
        $db->query("UPDATE tb_admin SET pass='$pass2' WHERE user='$_SESSION[login]'");
        print_msg('Password berhasil diubah.', 'success');
    }
} elseif ($act == 'logout') {
    unset($_SESSION['login']);
    header("location:index.php?m=login");
}

/** data */
elseif ($mod == 'data_tambah') {
    $id_transaksi = $_POST['id_transaksi'];
    $item = $_POST['item'];
    $tanggal = $_POST['tanggal'];

    if ($tanggal == '' || $item == '' || $id_transaksi == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("INSERT INTO tb_transaksi (id_transaksi, tanggal) VALUES ('$id_transaksi', '$tanggal')");
        $items = explode(',', $item);
        foreach ($items as $item) {
            $item = trim($item);
            if ($item) {
                $row = $db->get_row("SELECT * FROM tb_item WHERE nama_item='$item'");
                if ($row) {
                    $id_item = $row->id_item;
                } else {
                    $db->query("INSERT INTO tb_item (nama_item) VALUES ('$item')");
                    $id_item = $db->insert_id;
                }

                $db->query("INSERT INTO tb_detail (id_transaksi, id_item) 
                    VALUES ('$id_transaksi', '$id_item')");
            }
        }
        redirect_js("index.php?m=data");
    }
} else if ($mod == 'data_ubah') {
    $id_transaksi = $_POST['id_transaksi'];
    $item = $_POST['item'];
    $tanggal = $_POST['tanggal'];

    if ($tanggal == '' || $item == '' || $id_transaksi == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_transaksi SET tanggal='$tanggal' WHERE id_transaksi='$_GET[ID]'");

        $ids = array();

        $items = explode(',', $item);
        foreach ($items as $item) {
            $item = trim($item);
            if ($item) {
                $row = $db->get_row("SELECT * FROM tb_detail d INNER JOIN tb_item i ON i.id_item=d.id_item WHERE id_transaksi='$id_transaksi' AND nama_item='$item'");
                $id_item = $row->id_item;
                if (!$row) {
                    //print_msg("$item tidak ada!");
                    $row = $db->get_row("SELECT * FROM tb_item WHERE nama_item='$item'");
                    if ($row) {
                        $id_item = $row->id_item;
                    } else {
                        $db->query("INSERT INTO tb_item (nama_item) VALUES ('$item')");
                        $id_item = $db->insert_id;
                    }
                    $db->query("INSERT INTO tb_detail (id_transaksi, id_item) VALUES ('$id_transaksi', '$id_item')");
                }
                $ids[$id_item] = $id_item;
            }
        }
        $db->query("DELETE FROM tb_detail WHERE id_transaksi='$id_transaksi' AND id_item NOT IN ('" . implode("','", $ids) . "')");
        redirect_js("index.php?m=data");
    }
} else if ($act == 'data_hapus') {
    $db->query("DELETE FROM tb_transaksi WHERE id_transaksi='$_GET[ID]'");
    header("location:index.php?m=data");
} else if ($act == 'data_truncate') {
    $db->query("TRUNCATE tb_detail");
    $db->query("TRUNCATE tb_transaksi");
    $db->query("TRUNCATE tb_item");
    header("location:index.php?m=data");
}
