<div class="page-header">
    <h1><font color=”#000000″>Perhitungan Algoritma Apriori</font></h1>
</div>
<?php
$tanggal_awal = $db->get_var("SELECT min(tanggal) FROM tb_transaksi");
$tanggal_akhir = $db->get_var("SELECT max(tanggal) FROM tb_transaksi");

$tanggal_awal = set_value('tanggal_awal', $tanggal_awal);
$tanggal_akhir = set_value('tanggal_akhir', $tanggal_akhir);
?>
<div class="row">
    <div class="col-md-6">
        <form method="post">
            <input type="hidden" name="m" value="apriori" />
            <div class="form-group hidden">
                <label>Tanggal awal <span class="text-danger">*</span></label>
                <input class="form-control" name="tanggal_awal" type="date" value="<?= $tanggal_awal ?>">
            </div>
            <div class="form-group hidden">
                <label>Tanggal akhir <span class="text-danger">*</span></label>
                <input class="form-control" name="tanggal_akhir" type="date" value="<?= $tanggal_akhir ?>">
            </div>
            <div class="form-group">
                <label class="text-danger"><font color=”#000000″>Minimal Support (%)</font> <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="min_support" value="<?= set_value('min_support', 25) ?>" />
            </div>
            <div class="form-group">
                <label class="text-danger"><font color=”#000000″>Minimal Confidence (%)</font> <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="min_confidence" value="<?= set_value('min_confidence', 75) ?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><font color=”#000000″>Hitung</font></button>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><a href="#data" data-toggle="collapse"><font color=”#000000″>Data</font></a></h3>
    </div>
    <div class="table-responsive collapse" id="data">
        <table class="table table-bordered table-hover table-striped datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Transaksi</th>
                    <th>Data</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <?php
            $q = esc_field($_GET['q']);
            $pg = new Paging();
            $limit = 25;
            $offset = $pg->get_offset($limit, $_GET['page']);

            $from = "FROM tb_detail d INNER JOIN tb_transaksi t ON t.id_transaksi=d.id_transaksi INNER JOIN tb_item i ON i.id_item=d.id_item";
            $where = " WHERE i.nama_item LIKE '%$q%'";

            $rows = $db->get_results("SELECT * $from $where ORDER BY d.id_transaksi, d.id_item");

            $jumrec = $db->get_var("SELECT COUNT(*) $from $where");

            $no = $offset;
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= ++$no ?></td>
                    <td><?= $row->id_transaksi ?></td>
                    <td><?= $row->nama_item ?></td>
                    <td><?= $row->tanggal ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php
if ($_POST) {
    $_SESSION['apriori'] = $_POST;
    include 'apriori_hasil.php';
}
?>