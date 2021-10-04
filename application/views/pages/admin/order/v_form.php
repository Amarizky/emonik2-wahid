<?php
$roles = get_data('roles', '*')->result();
?>
<form action="<?php echo $action_form ?>" method="post" class="form-ajax" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg">
            <div class="form-group">
                <label class="font-weight-semibold">Nomor PO</label>
                <input type="text" class="form-control" id="no_po" name="no_po" placeholder="Masukkan nomor" value="<?php echo @$row->no_po; ?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Mitra</label>
                <!-- <input list="mitras" class="form-control" id="mitra" name="mitra" placeholder="Masukkan nama mitra" value="<?php echo @$row->kode_mitra; ?>" required> -->
                <select name="kode_mitra" id="kode_mitra" class="form-control" required>
                    <option value="" disabled selected>Pilih mitra</option>
                    <?php foreach ($datamitra as $m) : ?>
                        <option value="<?= $m->nama_mitra; ?>" <?= (@$row->mitra == $m->nama_mitra ? "selected" : ""); ?>><?= $m->nama_mitra; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="font-weight-semibold">Produk</label>
                <!-- <input list="mitras" class="form-control" id="mitra" name="mitra" placeholder="Masukkan nama mitra" value="<?php echo @$row->kode_produk; ?>" required> -->
                <select name="kode_produk" id="kode_produk" class="form-control" required>
                    <option value="" disabled selected>Pilih produk</option>
                    <?php foreach ($dproduk as $m) : ?>
                        <option value="<?= $m->nama_produk; ?>" <?= (@$row->produk == $m->nama_produk ? "selected" : ""); ?>><?= $m->nama_produk; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Quantity</label>
                <input type="text" class="form-control" id="qty" name="qty" placeholder="Masukkan quantity" value="<?php echo @$row->qty;?>" required> 
                 </div>
                 <div class="form-group">
                <label class="font-weight-semibold">Document deadtime</label>
                <input type="date" class="form-control" id="document_deadtime" name="document_deadtime" placeholder="Masukkan document deadtime" value="<?php echo @$row->document_deadtime;?>" required>
            </div>
            
            <div class="form-group">
                <label class="font-weight-semibold">Deadline</label>
                <input type="date" class="form-control" id="deadline" name="deadline" placeholder="Masukkan Deadline" value="<?php echo @$row->deadline;?>" required> 
                 </div>



            <br />
            <div class="form-group">
                <input type="hidden" name="<?= $csrf['name']; ?>" id="csrf" value="<?= $csrf['hash']; ?>" />
                <input type="hidden" name="submit" id="submit-type" value="submit" />
                <button type="submit" id="submit" value="submit" class="btn bg-transparent text-blue border-blue ml-2 btn-submit" onclick="(function(){$('#submit-type').val('submit');return true;})();return true;">Submit<i class="icon-paperplane ml-2"></i></button>
                <button type="submit" id="submit-back" value="submit-back" class="btn bg-transparent text-blue border-blue ml-2 btn-submit-back" onclick="(function(){$('#submit-type').val('submit-back');return true;})();return true;">Submit & Back<i class="icon-paperplane ml-2"></i></button>
            </div>
        </div>
    </div>
</form>