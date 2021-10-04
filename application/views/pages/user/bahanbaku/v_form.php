<?php
    $roles = get_data('roles', '*')->result();
?>
<form action="<?php echo $action_form?>" method="post" class="form-ajax" enctype="multipart/form-data" >
    <div class="row">
        <div class="col-lg">
            <div class="form-group">
                <label class="font-weight-semibold">Mitra</label>
                <input type="text" class="form-control" id="kode_mitra" name="kode_mitra" placeholder="Masukkan nama" value="<?php echo @$row->kode_mitra;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Material</label>
                <input type="text" class="form-control" id="kode_material" name="kode_material" placeholder="Masukkan nama bahan" value="<?php echo @$row->kode_material;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Quantity</label>
                <input type="number" class="form-control" id="qty" name="qty" placeholder="Masukkan kuantitas" value="<?php echo @$row->qty;?>" required> 
              </div>
            
            <div class="form-group">
                <label class="font-weight-semibold">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier" placeholder="Masukkan nama supplier" value="<?php echo @$row->supplier;?>"> 
                </div>
            
           
            <br/>
            <div class="form-group">
                <input type="hidden" name="<?=$csrf['name'];?>" id="csrf" value="<?=$csrf['hash'];?>" />
                <input type="hidden" name="submit" id="submit-type" value="submit" />
                <button type="submit"  id="submit" value="submit" class="btn bg-transparent text-blue border-blue ml-2 btn-submit" onclick="(function(){$('#submit-type').val('submit');return true;})();return true;">Submit<i class="icon-paperplane ml-2"></i></button>
                <button type="submit"  id="submit-back" value="submit-back" class="btn bg-transparent text-blue border-blue ml-2 btn-submit-back" onclick="(function(){$('#submit-type').val('submit-back');return true;})();return true;">Submit & Back<i class="icon-paperplane ml-2"></i></button>
            </div>
        </div>
    </div>
</form>

