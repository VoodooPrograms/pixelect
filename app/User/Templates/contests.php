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
            <a href="/contests"><img src="app/User/Public/assets/icons/success.svg" />Challenges</a>
        </div>
        <div class="profile">
            <img src="app/User/Public/assets/icons/trophy.svg" class="profile-image" />
            <div class="profile-name"></div>
            <div class="profile-buttons">
                <a class="profile-likes"><img src="app/User/Public/assets/icons/heart.svg" /></a>
                <a class="profile-wins"><img src="app/User/Public/assets/icons/trophy.svg" />3</a>
                <a href="/profile" class="profile-go-to"><img src="app/User/Public/assets/icons/user.svg" /></a>
                <a href="/logout" class="profile-logout"><img src="app/User/Public/assets/icons/logout.svg" /></a>
            </div>
        </div>
    </nav>
    <div id="menu-button">
        <img src="app/User/Public/assets/icons/hamburger.svg" />
    </div>
    <main class="col-10">
        <div class="contests">
            <form method="POST" action="/contests">
                <input type="text" placeholder="Contest title" name="title"/>
                <input type="text" placeholder="Contest details" name="details"/>
                <input type="datetime-local" name="starting_date">
                <input type="datetime-local" name="ending_date">

                <?php echo $vs->session('_error_bad_contest_data') ?>
                <input type="submit" value="Add Contest">
            </form>
        <?php foreach ($contests as $contest) : ?>
            <a class="contest-box" href="/contests/<?php echo $contest->getId(); ?>">
                <h3><?php echo $contest->getTitle(); ?></h3>
                <p><?php echo $contest->getDetails(); ?></p>
                <p>Starting date: <?php echo $contest->getStartingDate(); ?></p>
                <p>Ending date: <?php echo $contest->getEndingDate(); ?></p>
                <p>All likes: <?php echo $contest->getLikes(); ?></p>
                <p>All pictures: <?php echo $contest->getPictures(); ?></p>
            </a>
        <?php endforeach; ?>
        </div>
    </main>
</div>
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('scripts');?>


<script src=<?php $vs->assets('js/hamburger.js'); ?>></script>
<script src=<?php $vs->assets('js/userLikes.js'); ?>></script>

<?php $vs->template->endblock();?>

