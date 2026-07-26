<br>
<div class="box box-solid flat">
	<div class="box-body" style="padding-top:10px;">
		<div class="col-sm-4">
			<div class="input-group input-group">
                <input type="text" class="form-control" name="nim" id="search-box" placeholder="Masukan NIM">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="kirim()"><i class="fa fa-search"></i></button>
                    </span>
              </div>
              <div id="suggesstion-box"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function kirim () {
        var nim = $('#search-box').val();

        $.ajax({
            url : "<?= site_url('admin/akademik/perubahan/semester_ini/perubahan')  ?>/"+nim,
            type : "GET",
            data : "nim="+nim,
            success : function () {
                console.log = "terkirim";
                window.location.href = "<?= site_url('admin/akademik/perubahan/semester_ini/perubahan')  ?>/"+nim;
            },
            error : function () {
                console.log = "gagal";
            }


        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#search-box").keyup(function(){
            $.ajax({
                type: "POST",
                url: "<?= site_url('admin/akademik/perubahan/semester_ini/autocomplate')  ?>",
                data:'keyword='+$(this).val(),
                beforeSend: function(){
                    $("#search-box").css("background","#FFF url(../../LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                    $("#search-box").css("background","#FFF");
                }
            });
        });
    });
    //To select country name
    function selectNim(val) {
        $("#search-box").val(val);
        $("#suggesstion-box").hide();
    }
</script>