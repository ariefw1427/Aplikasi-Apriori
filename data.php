<div class="page-header">
    <h1><font color=”#000000″>Data</font></h1>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <form class="form-inline">
            <input type="hidden" name="m" value="data" />
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?= $_GET['q'] ?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span> Refresh</button>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" href="?m=data_tambah"><span class="glyphicon glyphicon-plus"></span> Input Data</a>
            </div>
            <div class="form-group">
                <a class="btn btn-info" href="?m=data_import"><span class="glyphicon glyphicon-import"></span> Import</a>
            </div>
            <div class="form-group">
                <a class="btn btn-default" href="cetak.php?<?= $_SERVER['QUERY_STRING'] ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Cetak</a>
            </div>
            <div class="form-group">
                <a class="btn btn-danger" href="aksi.php?act=data_truncate" onclick="return confirm('Hapus semua data?')"><span class="glyphicon glyphicon-trash"></span> Delete</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Transaksi</th>
                    <th>Data</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php
            $q = esc_field($_GET['q']);
            $pg = new Paging();
            $limit = 25;
            $offset = $pg->get_offset($limit, $_GET['page']);

            $from = "FROM tb_detail d INNER JOIN tb_transaksi t ON t.id_transaksi=d.id_transaksi INNER JOIN tb_item i ON i.id_item=d.id_item";
            $where = " WHERE i.nama_item LIKE '%$q%'";

            $rows = $db->get_results("SELECT * $from $where ORDER BY d.id_transaksi, d.id_item LIMIT $offset, $limit");

            $jumrec = $db->get_var("SELECT COUNT(*) $from $where");

            $no = $offset;
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= ++$no ?></td>
                    <td><?= $row->id_transaksi ?></td>
                    <td><?= $row->nama_item ?></td>
                    <td><?= $row->tanggal ?></td>
                    <td class="nw">
                        <a class="btn btn-xs btn-warning" href="?m=data_ubah&ID=<?= $row->id_transaksi ?>"><span class="glyphicon glyphicon-edit"></span></a>
                        <a class="btn btn-xs btn-danger" href="aksi.php?act=data_hapus&ID=<?= $row->id_transaksi ?>" onclick="return confirm('Hapus data?')"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="panel-footer">
        <ul class="pagination"><?= $pg->show("m=data&q=$_GET[q]&page=", $jumrec, $limit, $_GET['page']) ?></ul>
    </div>
</div>