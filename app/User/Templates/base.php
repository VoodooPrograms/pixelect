<html>
<head>
    <title><?php $vs->template->startblock('title');?> <?php $vs->template->endblock();?> </title>
<!--    <link rel="stylesheet" href="--><?php //$vs->assets('css/style.css') ?><!--">-->
    <link rel="stylesheet" href="app/User/Public/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
</head>
<body>

<?php $vs->template->startblock('content');?>
<?php $vs->template->endblock();?>

<?php $vs->template->startblock('scripts');?>
<?php $vs->template->endblock();?>

</body>
</html>