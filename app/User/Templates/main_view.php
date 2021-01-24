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
<script>
    async function checkForNewDiv() {
        let lastDiv = document.querySelector(".gallery > div > div.gallery-item:last-child");
        let lastDivOffset = lastDiv.offsetTop + lastDiv.clientHeight;
        let pageOffset = window.pageYOffset + window.innerHeight;
        if(pageOffset > lastDivOffset) {
            await fetchCall('http://localhost:8080/pictures/all');
            lastDiv = document.querySelector(".gallery > div > div.gallery-item:last-child");
            lastDivOffset = lastDiv.offsetTop + lastDiv.clientHeight;
            pageOffset = window.pageYOffset + window.innerHeight;
            console.debug(lastDivOffset);
            console.debug(pageOffset);
        }
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        setInterval(async function () {
            await checkForNewDiv();
        }, 3000)
    });



</script>

<script src=<?php $vs->assets('js/hamburger.js'); ?>></script>
<script src=<?php $vs->assets('js/userLikes.js'); ?>></script>
<script src=<?php $vs->assets('js/editor/editor.js'); ?>></script>
<script src=<?php $vs->assets('js/pictures.js'); ?>></script>

<script>
    fetchCall('http://localhost:8080/pictures/all');
</script>

<?php $vs->template->endblock();?>

