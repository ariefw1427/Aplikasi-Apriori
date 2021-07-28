<h1>Hasil Perhitungan Asosiasi Apriori</h1>
<?php

$_POST = $_SESSION['apriori'];

$tanggal_awal = $db->get_var("SELECT min(tanggal) FROM tb_transaksi");
$tanggal_akhir = $db->get_var("SELECT max(tanggal) FROM tb_transaksi");

$tanggal_awal = set_value('tanggal_awal', $tanggal_awal);
$tanggal_akhir = set_value('tanggal_akhir', $tanggal_akhir);


$time_start = microtime(true);

$data =  $db->get_results("SELECT * FROM tb_detail d INNER JOIN tb_transaksi t ON t.id_transaksi=d.id_transaksi INNER JOIN tb_item i ON i.id_item=d.id_item WHERE tanggal>='$tanggal_awal' AND tanggal<='$tanggal_akhir' ORDER BY d.id_transaksi, d.id_item");

$min_support = set_value('min_support', 25);
$min_confidence = set_value('min_confidence', 25);

$data = convert($data);

reset($data);
$arr_kategory = array();
foreach ($data as $key => $val) {
    foreach ($val as $k => $v) {
        $arr_kategory[$v] = $v;
    }
}
$item_total = count($arr_kategory);

$itemset = 1;
$stop = false;
$large_candicate = array();
$com_category = array();
$support = array();

while (!$stop) :
    $temp_large_candidate = $large_candicate;
    $temp_com_category = $com_category;
    $temp_support = $support;

    $com_category = get_com_category($arr_kategory, $itemset);
    $qty = get_candidate($data, $com_category);

    $support = get_support($qty, count($data));
    $large_candicate = filter_candicate($qty, $support, $min_support / 100);

    if ($large_candicate) : ?>
        <div class="panel panel-primary hidden">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a href="#C_<?= $itemset ?>" data-toggle="collapse">C<?= $itemset ?> (Kandidat <?= $itemset ?>-itemset)</a>
                </h3>
            </div>
            <div class="table-responsive collapse" id="C_<?= $itemset ?>">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <?php for ($a = 1; $a <= $itemset; $a++) : ?>
                                <th>Item<?= $a ?></th>
                            <?php endfor ?>
                            <th>Qty</th>
                            <th>Support</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    foreach ($com_category as $key => $val) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <?php foreach ($val as $k) : ?>
                                <td><?= $k ?></td>
                            <?php endforeach ?>
                            <td><?= $qty[$key] ?></td>
                            <td><?= round($support[$key] * 100, 2) ?>%</td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
        <div class="panel panel-primary hidden">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a href="#L_<?= $itemset ?>" data-toggle="collapse">L<?= $itemset ?> (Large <?= $itemset ?>-itemset)</a>
                </h3>
            </div>
            <div class="table-responsive collapse" id="L_<?= $itemset ?>">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <?php for ($a = 1; $a <= $itemset; $a++) : ?>
                                <th>Item<?= $a ?></th>
                            <?php endfor ?>
                            <th>Qty</th>
                            <th>Support</th>
                        </tr>
                    </thead>
                    <?php
                    $no = 1;
                    foreach ($large_candicate as $key => $val) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <?php foreach ($com_category[$key] as $k) : ?>
                                <td><?= $k ?></td>
                            <?php endforeach ?>
                            <td><?= $large_candicate[$key] ?></td>
                            <td><?= round($support[$key] * 100, 2) ?>%</td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </div>
        <?php if ($itemset > 1) :
            $ass_key = ass_key($large_candicate, $com_category);
            $ass_qty = get_ass_qty($ass_key, $data);
            $confidence = get_confidence($ass_key, $ass_qty, $large_candicate);
            //echo '<pre>' . print_r($la, 1) .'</pre>';
        ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Asosiasi (<?= $itemset ?>-itemset)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Rule</th>
                                <th>Support</th>
                                <th colspan="2">Confidence</th>
                                <th>Sup. * Conf.</th>
                                <th>Lift Ratio</th>
                            </tr>
                        </thead>
                        <?php
                        $no = 1;
                        arsort($confidence);

                        foreach ($confidence as $key => $val) : $val = $ass_key[$key];
                            if ($confidence[$key] >= $min_confidence / 100) : ?>
                                <tr class="<?= $confidence[$key] >= $min_confidence / 100 ? '' : 'danger' ?>">
                                    <td><?= $no++ ?></td>
                                    <td>Jika konsumen membeli <strong><?= implode(", ", $val['left']) ?></strong> maka membeli <strong><?= $val['right'] ?></strong></td>
                                    <td><?= round($support[$val['index']] * 100, 2) ?>%</td>
                                    <td><?= $large_candicate[$val['index']] ?>/<?= $ass_qty[$key] ?></td>
                                    <td><?= round($confidence[$key] * 100, 2) ?>%</td>
                                    <td><?= round($support[$val['index']] * $confidence[$key] * 100, 2) ?></td>
                                    <td><?= round($confidence[$key] / ($min_confidence / 100), 2) ?></td>
                                </tr>
                        <?php endif;
                        endforeach ?>
                    </table>
                </div>
            </div>
<?php endif;
        $arr_kategory = filter_category($large_candicate, $com_category);

        if (count($large_candicate) < 1) {
            $stop = true;
        }
        if ($itemset > $item_total) {
            $stop = true;
        }
        $itemset++;
    else : // not large_candidate        
        $stop = true;
    endif;
endwhile; ?>