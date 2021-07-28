<?php
$rows = $db->get_results("SELECT * FROM tb_detail d INNER JOIN tb_item i ON i.id_item=d.id_item WHERE id_transaksi='$_GET[ID]'");
$items = array();
foreach ($rows as $row) {
    $items[$row->id_item] = $row->nama_item;
}

$row = $db->get_row("SELECT * FROM tb_transaksi WHERE id_transaksi='$_GET[ID]'");
?>
<section class="content-header">
    <h1><font color=”#000000″>Ubah Data</font></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-sm-6">
            <?php if ($_POST) include 'aksi.php' ?>
            <form method="post">
                <div class="form-group">
                    <label>Id Transaksi <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="id_transaksi" value="<?= set_value('id_transaksi', $row->id_transaksi) ?>" readonly />
                </div>
                <div class="form-group">
                    <label>Tanggal <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" name="tanggal" value="<?= set_value('tanggal', $row->tanggal) ?>" />
                </div>
                <div class="form-group">
                    <label>Item <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="item" value="<?= set_value('item', implode(', ', $items)) ?>" />
                    <p class="help-block">Pisahkan setiap item dengan koma ( , )</p>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
                    <a class="btn btn-danger" href="?m=data"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>