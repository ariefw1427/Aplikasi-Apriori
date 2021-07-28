<div class="page-header">
    <h1><font color=”#000000″>Import Dataset</font></h1>
</div>
<div class="row">
    <div class="col-md-6">
        <form method="post" enctype="multipart/form-data">
            <?php
            if ($_POST) {

                $row = 0;
                move_uploaded_file($_FILES['data']['tmp_name'], 'import/' . $_FILES['data']['name']) or die('Upload gagal');

                $arr = array();

                if (($handle = fopen('import/' . $_FILES['data']['name'], "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);

                        if ($row > 0) {
                            for ($c = 1; $c < $num; $c++) {
                                $arr[$row][$c] = $data[$c];
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                }

                $tb_transaksi = array();
                $item = array();
                $db_detail = array();
                foreach ($arr as $key => $val) {
                    $date = date_create($val[3], timezone_open("Europe/Oslo"));
                    $tb_transaksi[$val[1]] = array(
                        'id_transaksi' => $val[1],
                        'tanggal' => date_format($date, "Y-m-d"),
                    );
                    $item[strtolower(trim($val[2]))] = trim($val[2]);
                    $tb_detail[] = array(
                        'id_transaksi' => $val[1],
                        'id_item' => $val[2],
                    );
                }
                $item_id = array();
                $no = 1;
                foreach ($item as $key => $val) {
                    $item_id[trim(strtolower($key))] = $no++;
                }
                //echo '<pre>' . print_r($item, 1) . '</pre>';

                $tb_item = array();
                foreach ($item as $key => $val) {
                    $tb_item[] = array(
                        'id_item' => $item_id[trim(strtolower($key))],
                        'nama_item' => $val,
                    );
                }

                foreach ($tb_detail as $key => $val) {
                    $tb_detail[$key]['id_item'] = $item_id[trim(strtolower($val['id_item']))];
                }

                //echo '<pre>' . print_r($tb_item, 1) . '</pre>';

                $db->query("TRUNCATE tb_item");
                $db->multi_query('tb_item', $tb_item);

                $db->query("TRUNCATE tb_transaksi");
                $db->multi_query('tb_transaksi', $tb_transaksi);
                $db->query("TRUNCATE tb_detail");
                $db->multi_query('tb_detail', $tb_detail);

                print_msg('Dataset berhasil diimport!', 'success');
            }
            ?>
            <div class="form-group">
                <label><font color=”#000000″>Pilih file</font></label>
                <input class="form-control" type="file" name="data" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary" name="import"><span class="glyphicon glyphicon-import"></span> Import</button>
                <a class="btn btn-danger" href="?m=data"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>