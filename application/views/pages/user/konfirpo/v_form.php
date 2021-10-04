<?php
    $roles = get_data('roles', '*')->result();
?>
<form action="<?php echo $action_form?>" method="post" class="form-ajax" enctype="multipart/form-data" >
    <div class="row">
        <div class="col-lg">
            <div class="form-group">
                <label class="font-weight-semibold">Nomor PO</label>
                <input type="text" class="form-control" id="no_po" name="no_po" placeholder="Masukkan nama" value="<?php echo @$row->no_po;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Tanggal Pengiriman</label>
                <input type="date" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" placeholder="Masukkan nama" value="<?php echo @$row->tanggal_pengiriman;?>" required>
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

