<style>
    .left,
    .right {
        display: inline-block;
        width: 48%;
        border: solid 1px black;
        overflow: auto;
        vertical-align: top;
        min-height: 300px;
    }
</style>
<script>
    function codificar() {
        var object = {
            path: $("#path").val(),
            pixels: $("#pixels").val()
        }
        $.post("/copycoder/codify", object, function(res) {
            $("#coded").html("<img src=\""+res+"?r="+Math.random()+"\">");
        })
    }
</script>
<?php if ($path == "") { ?>

<h1><?=$message;?></h1>
<form id="upload-form" action="<?php echo URL::site('copycoder/index') ?>" method="post" enctype="multipart/form-data">
    <p>Subir Imagen:</p>
    <p><input type="file" name="image" id="image" /></p>
    <p><input type="submit" name="submit" id="submit" value="Subir" /></p>
</form>

<?php } ?>
<?php if ($path != "") { ?>
<div>
    <input type="hidden" name="path" id="path" value="<?=$path;?>" /> 
    Pixeles: <input type="number" id="pixels" value="15" />
    <button onclick="codificar()">CODIFICAR</button>
</div>
<div class="left">
    <img src="<?=$path;?>" />
</div>
<div class="right" id="coded">
    <img  src="<?=$path;?>" />
</div>
<?php } ?>