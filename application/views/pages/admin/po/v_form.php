<?php
    $roles = get_data('roles', '*')->result();
?>
<form action="<?php echo $action_form?>" method="post" class="form-ajax" enctype="multipart/form-data" >
    <div class="row">
        <div class="col-lg">
            <div class="form-group">
                <label class="font-weight-semibold">No PO</label>
                <input type="text" class="form-control" id="no_po" name="no_po" placeholder="Masukkan nomer order" value="<?php echo @$row->no_po;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Mitra</label>
                <input type="text" class="form-control" id="mitra" name="mitra" placeholder="Masukkan nama" value="<?php echo @$row->mitra;?>" required>
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">jumlah order</label>
                <input type="text" class="form-control" id="jumlah_order" name="jumlah_order" placeholder="Masukkan jumlah order" value="<?php echo @$row->jumlah_order;?>" required> 
               
            </div>
            <div class="form-group">
                <label class="font-weight-semibold">Deadline Pengiriman</label>
                <input type="date" class="form-control" id="deal_line" name="dead_line" placeholder="deadline" value="<?php echo @$row->dead_line;?>"> 
                  </div>
            <div class="form-group">
                <label class="font-weight-semibold">Tanggal Order</label>
                <input type="date" class="form-control" id="tanggal_po" name="tanggal_po" placeholder="tanggal_po" value="<?php echo @$row->tanggal_po;?>"> 
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

