<?php include 'layout.php' ?>


<?php $vs->template->startblock('title') ?>
Pixelect - main page
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('content2') ?>
<div class="loader"></div>
<div class="gallery">
    <div class="gallery-column"></div>
    <div class="gallery-column"></div>
    <div class="gallery-column"></div>
</div>
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('scripts');?>

<script src=<?php $vs->assets('js/hamburger.js'); ?>></script>
<script src=<?php $vs->assets('js/userLikes.js'); ?>></script>
<script src=<?php $vs->assets('js/editor/editor.js'); ?>></script>
<script src=<?php $vs->assets('js/pictures.js'); ?>></script>

<script>
    fetchCall('http://localhost:8080/user/pictures');
</script>

<?php $vs->template->endblock();?>

