<div class="wrapper">
    <div class="header-top">
        <div class="header-logo"><?php echo CHtml::link(zmf::config('sitename'),zmf::config('baseurl'));?></div>          
    </div>
    <div class="alert alert-danger" role="alert">
        <h1><?php echo $code; ?></h1>
        <div class="message"><?php echo nl2br(CHtml::encode($message)); ?></div>
    </div>
</div>
