<?php
    $roles = get_data('roles', '*')->result();
?>
<form action="<?php echo $action_form?>" method="post" class="form-ajax" enctype="multipart/form-data" >
    <div class="row">
        <div class="col-lg">
            <div class="form-group">
                <label class="font-weight-semibold">Kode Material</label>
                <input type="text" class="form-control" id="kode_material" name="kode_material" placeholder="Masukkan kode material" value="<?php echo @$row->kode_material;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Deskripsi</label>
                <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi" value="<?php echo @$row->deskripsi;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Unit</label>
                <select class="form-control" id="unit"name="unit" placeholder="Masukkan deskripsi" value="<?php echo @$row->unit;?>" required>
      <option>gr</option>
      <option>kg</option>  
      <option>ton</option> 
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

