<?php
$roles = get_data('roles', '*')->result();
?>
<form action="<?php echo $action_form ?>" method="post" class="form-ajax" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg">
            <input type="hidden" name="id_bom" value="<?= @$row->id_bom; ?>">

            <div class="form-group">
                <label class="font-weight-semibold">Mitra</label>
                <!-- <input list="mitras" class="form-control" id="mitra" name="mitra" placeholder="Masukkan nama mitra" value="<?php echo @$row->kode_mitra; ?>" required> -->
                <select name="kode_mitra" id="kode_mitra" class="form-control" required>
                    <option value="" disabled selected>Pilih mitra</option>
                    <?php foreach ($datamitra as $mi) : ?>
                        <option value="<?= $mi->nama_mitra; ?>" <?= (@$row->mitra == $mi->nama_mitra ? "selected" : ""); ?>><?= $mi->nama_mitra; ?></option>
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
                <label class="font-weight-semibold">Material</label>
                <!-- <input list="mitras" class="form-control" id="mitra" name="mitra" placeholder="Masukkan nama mitra" value="<?php echo @$row->kode_material; ?>" required> -->
                <select name="kode_material" id="kode_material" class="form-control" required>
                    <option value="" disabled selected>Pilih material</option>
                    <?php foreach ($dmaterial as $ma) : ?>
                        <option value="<?= $ma->deskripsi; ?>" <?= (@$row->material == $ma->deskripsi ? "selected" : ""); ?>><?= $ma->deskripsi; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="font-weight-semibold">Quantity</label>
                <input type="text" class="form-control" id="qty" name="qty" placeholder="Masukkan Kuantitas" value="<?php echo @$row->qty; ?>">
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Satuan</label>
                <select class="form-control" id="satuan" name="satuan" placeholder="Masukkan satuan" value="<?php echo @$row->satuan; ?>" required>

                    <option>kg</option>
                    <option>ton</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <input type="hidden" name="<?= $csrf['name']; ?>" id="csrf" value="<?= $csrf['hash']; ?>" />
                <input type="hidden" name="submit" id="submit-type" value="submit" />
                <button type="submit" id="submit" value="submit" class="btn bg-transparent text-blue border-blue ml-2 btn-submit" onclick="(function(){$('#submit-type').val('submit');return true;})();return true;">Submit<i class="icon-paperplane ml-2"></i></button>
                <button type="submit" id="submit-back" value="submit-back" class="btn bg-transparent text-blue border-blue ml-2 btn-submit-back" onclick="(function(){$('#submit-type').val('submit-back');return true;})();return true;">Submit & Back<i class="icon-paperplane ml-2"></i></button>
            </div>
