<?php include 'base.php' ?>


<?php $vs->template->startblock('title') ?>
    Pixelect - main page
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('content') ?>
<div class="grid-container">
    <nav class="col-2">
        <div class="button-list">
            <a href="/pixel-editor"><img src="app/User/Public/assets/icons/big-paint-brush.svg" />Draw</a>
            <a href="/main"><img src="app/User/Public/assets/icons/gallery-layout.svg" />Gallery</a>
            <a href="/challenges"><img src="app/User/Public/assets/icons/success.svg" />Challenges</a>
        </div>
        <div class="profile">
            <img src="app/User/Public/assets/icons/trophy.svg" class="profile-image" />
            <div class="profile-name">Bartosz</div>
            <div class="profile-buttons">
                <a href="#" class="profile-likes"><img src="app/User/Public/assets/icons/heart.svg" />621</a>
                <a href="#" class="profile-wins"><img src="app/User/Public/assets/icons/trophy.svg" />3</a>
                <a href="/profile" class="profile-go-to"><img src="app/User/Public/assets/icons/user.svg" /></a>
                <a href="/logout" class="profile-logout"><img src="app/User/Public/assets/icons/logout.svg" /></a>
            </div>
        </div>
    </nav>
    <div id="menu-button">
        <img src="app/User/Public/assets/icons/hamburger.svg" />
    </div>
    <main class="col-10">
        <div class="gallery">
            <div class="gallery-item">qwe</div>
            <div class="gallery-item">asd</div>
            <div class="gallery-item">ddd</div>
        </div>
    </main>
</div>
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('scripts');?>
<script>
    const menuButton = document.getElementById('menu-button');
    const menu = document.querySelector('nav');
    menuButton.addEventListener('click', () => {
        menu.classList.toggle('show');
        menuButton.classList.toggle('show');
    });
</script>
<?php $vs->template->endblock();?>

