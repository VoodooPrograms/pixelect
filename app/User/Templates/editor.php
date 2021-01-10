<?php include 'base.php' ?>

<?php $vs->template->startblock('title') ?>
Pixelect - editor
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('content') ?>
    <div></div>
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('scripts');?>
<script src=<?php $vs->assets('js/editor.js'); ?>></script>

<script>
    document.querySelector("div")
        .appendChild(startPixelEditor({}));
</script>
<?php $vs->template->endblock();?>

