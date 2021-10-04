<?php
    $roles = get_data('roles', '*')->result();
?>
<form action="<?php echo $action_form?>" method="post" class="form-ajax" enctype="multipart/form-data" >
    <div class="row">
        <div class="col-lg">
            <div class="form-group">
                <label class="font-weight-semibold">Kode Mitra</label>
                <input type="text" class="form-control" id="kode_mitra" name="kode_mitra" placeholder="Masukkan kode mitra" value="<?php echo @$row->kode_mitra;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Nama Mitra</label>
                <input type="text" class="form-control" id="nama_mitra" name="nama_mitra" placeholder="Masukkan nama mitra" value="<?php echo @$row->nama_mitra;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" value="<?php echo @$row->alamat;?>" required> 
                 </div>
                 <div class="form-group">
                <label class="font-weight-semibold">Nomor HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan nomor Hp" value="<?php echo @$row->no_hp;?>" required> 
                 </div>
            
            
            <div class="form-group">
                <input type="hidden" name="<?=$csrf['name'];?>" id="csrf" value="<?=$csrf['hash'];?>" />
                <input type="hidden" name="submit" id="submit-type" value="submit" />
                <button type="submit"  id="submit" value="submit" class="btn bg-transparent text-blue border-blue ml-2 btn-submit" onclick="(function(){$('#submit-type').val('submit');return true;})();return true;">Submit<i class="icon-paperplane ml-2"></i></button>
                <button type="submit"  id="submit-back" value="submit-back" class="btn bg-transparent text-blue border-blue ml-2 btn-submit-back" onclick="(function(){$('#submit-type').val('submit-back');return true;})();return true;">Submit & Back<i class="icon-paperplane ml-2"></i></button>
            </div>
        </div>
    </div>
</form>

