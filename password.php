<section class="content-header">
    <h1><font color=”#000000″>Edit Password</font></h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-sm-5">
            <?php if ($_POST) include 'aksi.php' ?>
            <form method="post">
                <div class="form-group">
                    <label><font color=”#000000″>Password Lama </font><span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="pass1" />
                </div>
                <div class="form-group">
                    <label><font color=”#000000″>Password Baru</font><span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="pass2" />
                </div>
                <div class="form-group">
                    <label><font color=”#000000″>Konfirmasi Password Baru</font><span class="text-danger">*</span></label>
                    <input class="form-control" type="password" name="pass3" />
                </div>
                <button class="btn btn-primary"><span class="glyphicon glyphicon-save"></span> Simpan</button>
            </form>
        </div>
    </div>
</section>