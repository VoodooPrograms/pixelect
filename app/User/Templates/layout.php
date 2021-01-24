<?php include 'base.php' ?>


<?php $vs->template->startblock('title') ?>
Pixelect - main page
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('content') ?>
<div class="grid-container">
    <nav class="col-2">
        <div class="button-list">
            <a href="/pixel-editor"><img src=<?php echo $vs->assets('assets/icons/big-paint-brush.svg'); ?> />Draw</a>
            <a href="/main"><img src=<?php echo $vs->assets('assets/icons/gallery-layout.svg'); ?> />Gallery</a>
            <a href="/contests"><img src=<?php echo $vs->assets('assets/icons/success.svg'); ?> />Challenges</a>
            <a href="/my-work"><img src=<?php echo $vs->assets('assets/icons/success.svg'); ?> />My Pictures</a>
        </div>
        <div class="profile">
            <img src=<?php echo $vs->assets('assets/icons/trophy.svg'); ?> class="profile-image" />
            <div class="profile-name"></div>
            <div class="profile-buttons">
                <a class="profile-likes"><img src=<?php echo $vs->assets('assets/icons/heart.svg'); ?> /></a>
                <a class="profile-wins"><img src=<?php echo $vs->assets('assets/icons/trophy.svg'); ?> />3</a>
                <a href="/profile" class="profile-go-to"><img src=<?php echo $vs->assets('assets/icons/user.svg'); ?> /></a>
                <a href="/logout" class="profile-logout"><img src=<?php echo $vs->assets('assets/icons/logout.svg'); ?> /></a>
            </div>
        </div>
    </nav>
    <div id="menu-button">
        <img src=<?php echo $vs->assets('assets/icons/hamburger.svg'); ?> />
    </div>
    <main class="col-10">

        <?php $vs->template->startblock('content2') ?>

        <?php $vs->template->endblock() ?>

    </main>
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

