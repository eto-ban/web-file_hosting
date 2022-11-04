<form class="uplauder_form" method="post" action="/upload.php" enctype="multipart/form-data">

    <div class="uplauser_btn">
        <b id="filename">Выберете файл</b> <br> <br>
        <input id="file-upload" class="uplauder_file" type="file" name="file1" id="file" onchange="uploadFile()">
        <label for="file-upload" class="uplauser_file-btn">
            Загрузить файл
        </label>
    </div>

    <div class="uplouser_descrip">
        <input class="uplouser_descrip-input" type="text" name="descr" maxlength="256"  placeholder="Описание файла">
    </div>
    <div class="uplouder_button">
        <button class="uplouder_button-btn" type="submit">Загрузить</button>
    </div>
</form>

<script>
    $('input:file').change(
        function(e){
            console.log(e.target.files[0].name);
            document.getElementById('filename').innerHTML = e.target.files[0].name;

        });
</script>

<style>
    .uplauder_form{
        border: 1px solid;
        width:400px;
        margin: 20px auto;
        margin-top: 20vh;
        margin-bottom: 30vh;
    }
    .uplauder_file[type="file"] {
        display: none;
    }
    .uplauser_btn, .uplouser_descrip, .uplouder_button{
        text-align: center;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .uplauser_file-btn {
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
    .uplouser_descrip-input{
        background-color:#616A6B;
        color: #fff;
        text-align: center;
        width: 90%;
        border: 0;
    }
    .uplouser_descrip-input::-webkit-input-placeholder {
        color: #fff;
    }
    .uplouser_descrip-input:-moz-placeholder {
        color: #fff;
    }
    .uplouser_descrip-input::-moz-placeholder {
        color: #fff;
    }
    .uplouser_descrip-input:-ms-input-placeholder {
        color: #fff;
    }
    .uplouder_button-btn{
        background-color: #CCD1D1;
    }
</style>
