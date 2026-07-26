<div class="box box-warning direct-chat direct-chat-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-envelope"></i> UBGChat V.1</h3>

        <div class="box-tools pull-right">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span></button>
        </div>
    </div>

    <div class="box-body">
        <div class="direct-chat-messages">
        <?php
            foreach ($pesan as $row)
            {
            if ($row->pesan_dekan) {
                ?>
                <div class="direct-chat-msg right">
                    <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right"><?= $dekan ?></span>
                       <span class="direct-chat-timestamp pull-left">Tanggal:<?= tgl_indo($row->tgl_dekan); ?>&nbsp;Jam:<?= date('h:i:s A', strtotime($row->tgl_dekan)) ?></span>
                    </div>
                    <div class="direct-chat-text">
                        <?= $row->pesan_dekan; ?>
                    </div>
                </div>
                <?php 
            }else{
                ?>
                    <div class="direct-chat-msg">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left">
                                  <?php 
                                if($row->kode_prodi){
                                    echo $prodi;
                                }else{
                                    echo $dosen;
                                }
                                ?>
                            </span>
                            <span class="direct-chat-timestamp pull-right">
                            <?php 
                                if($row->tgl_prodi){
                                    echo $row->tgl_prodi;
                                }else{
                                    echo $row->tgl_dosen;
                                }
                            ?>    
                            </span>
                        </div>
                        <div class="direct-chat-text">
                            <?php 
                                if($row->pesan_prodi){
                                    echo $row->pesan_prodi;
                                }else{
                                    echo $row->pesan_dosen;
                                }
                            ?>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
            <div class="direct-chat-msg right mychat"></div>
        </div>
    </div>
     <!-- /.box-body -->
    <div class="box-footer">
        <form action="#" method="post">
            <div class="input-group">
                <input type="text" name="message" placeholder="Tulisan Pesan ..." class="form-control tulis-pesan">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-warning btn-flat btn-chat">Kirim</button>
                </span>
            </div>
        </form>
    </div>
    <!-- /.box-footer-->
</div>

<script>
    const directMessageRight = document.querySelector('.right')
    const buttonSend = document.querySelector('.btn-chat')
    const myChat = document.querySelector('.mychat')
    const myDate = new Date();
    let daysList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    let monthsList = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Aug', 'Oct', 'Nov', 'Dec'];
    let date = myDate.getDate();
    let month = monthsList[myDate.getMonth()];
    let year = myDate.getFullYear();
    let day = daysList[myDate.getDay()];
    let today = `${date} ${month} ${year}, ${day}`;
    let amOrPm;
    let twelveHours = function (){
    if(myDate.getHours() > 12)
    {
        amOrPm = 'PM';
        let twentyFourHourTime = myDate.getHours();
        let conversion = twentyFourHourTime - 12;
        return `${conversion}`
    }else {
        amOrPm = 'AM';
        return `${myDate.getHours()}`}
    };
    let hours = twelveHours();
    let minutes = myDate.getMinutes();
    let currentTime = `${hours}:${minutes} ${amOrPm}`;

    buttonSend.addEventListener("click", () => {
        const formControl = document.querySelector('.tulis-pesan').value;
        const newMessage = document.createElement('div')
        const sendingName = `<div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-right"><?= $dekan ?></span>
                    <span class="direct-chat-timestamp pull-left">${today + ' ' + currentTime}</span>
                    </div>`
        newMessage.className = 'direct-chat-text'
        newMessage.innerText = `${formControl}`
        var url = "<?= site_url('dosen/penilaian/pesan_all') ?>/" + super_kelas_id + "/dekan/<?= $param ?>";
        $.ajax({
            url: url,
            type: "POST",
            data :{
                pesan : formControl,
                tgl : `${year}-${myDate.getMonth()+1}-${myDate.getDate()} ${myDate.getHours()}:${myDate.getMinutes()}:${myDate.getSeconds()}`
            },
            success: function (res) {
                if (res === 'success') {
                    myChat.insertAdjacentHTML('beforeend', sendingName)
                    myChat.append(newMessage)
                }
                console.log(res);
            }
        }) 
    })
</script>