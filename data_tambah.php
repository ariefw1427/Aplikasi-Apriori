<section class="content-header">
    <h1><font color=”#000000″>Input Data</font></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-sm-6">
            <?php if ($_POST) include 'aksi.php' ?>
            <form method="post">
                <div class="form-group">
                    <label><font color=”#000000″>Tanggal</font> <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" name="tanggal" value="<?= set_value('tanggal', date('Y-m-d')) ?>" onchange="kode_oto()" />
                </div>
                <div class="form-group">
                    <label><font color=”#000000″>Id Transaksi</font> <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="id_transaksi" value="<?= set_value('id_transaksi', kode_oto('id_transaksi', 'tb_transaksi', 'T', 6)) ?>" />
                </div><font color=”#000000″>
                <div class="form-group">
                    <label><font color=”#000000″>Item</font> <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="item" value="<?= set_value('item') ?>" />
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
<script type="text/javascript">
    $(function() {
        kode_oto();
    })

    function kode_oto() {
        $.ajax({
            url: 'ajax.php?m=get_kode',
            data: {
                tanggal: $("input[name='tanggal']").val()
            },
            type: 'POST',
            success: function(res) {
                $("input[name='id_transaksi']").val(res);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
</script>