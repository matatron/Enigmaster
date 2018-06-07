<h1><?=$message;?></h1>
<form id="upload-form" action="<?php echo URL::site('upload/index') ?>" method="post" enctype="multipart/form-data">
    <p>Choose file:</p>
    <p><input type="file" name="video" id="video" /></p>
    <p><input type="submit" name="submit" id="submit" value="Subir" /></p>
</form>
