<?php
include(INCLUDE_FILES."/functions/form.php");

$message = false;
if(!empty($_POST['submit'])){

    $result = handleSign($signtype);
    if($result){
        $message =  "Bedankt voor het <span style='font-weight:bold;'>{$signtype}</span> checken! Veel plezier vandaag!!";

        echo "<meta http-equiv=\"refresh\" content=\"2; URL=".BASEURL."\">";
    }
}

?>
<div class="row">
    <div class="header">
        <img src="<?php echo BASEURL;?>assets/img/bureau_logo.png" alt="Logo Het Bureau" width="120px" height="90px">
        <h1>Welkom bij de check <?= strtoupper($signtype);?> voor Het Bureau</h1>
    </div>
    <div class="row-form">
        <a href="<?= $_ENV['BASEURL'];?>" class="button sign-in">Terug naar Home page</a>
    </div>
    <?php if($message){?>
        <div class="alert alert-success">
            <?= $message;?>
        </div>
    <?php }?>
    <?php if(!$message){?>
        <form method="post" action="<?= $_SERVER['REQUEST_URI'];?>" class="sigPad">
            <input type="hidden" name="check_type" value="in">
            <div class="form-group">
                <label for="std_nr">Vul je student nummer in:</label>
                <input type="text" pattern="\d*" maxlength="6" name="std_nr" id="std_nr" class="form-control" />
            </div>
            <div class="form-group">
                <p class="drawItDesc">Handtekening</p>
                <ul class="sigNav">
                    <li class="drawIt"><a href="#draw-it" >Draw It</a></li>
                    <li class="clearButton"><a href="#clear">Clear</a></li>
                </ul>
                <div class="sig sigWrapper">
                    <div class="typed"></div>
                    <canvas class="pad" width="510" height="155"></canvas>
                    <input type="hidden" name="output" class="output">
                </div>
            </div>
            <button type="submit" name="submit" value="checkin" class="btn btn-success">Ik check <?= $signtype;?>!</button>
        </form>
    <?php }?>
</div>