<h1>Data</h1>
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

    $rows = $db->get_results("SELECT * $from $where ORDER BY d.id_transaksi, d.id_item LIMIT $offset, $limit");

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