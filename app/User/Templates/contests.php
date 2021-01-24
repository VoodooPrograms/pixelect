<?php include 'layout.php' ?>


<?php $vs->template->startblock('title') ?>
Pixelect - main page
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('content2') ?>

        <div class="contests">
            <div class="contests-add">
                <h2 class="contests-add-info">
                    Welcome to our contests section. Here you can add or participate in various challenges!
                </h2>
                <div class="contests-add-form">
                    <form method="POST" action="/contests" class="form">
                        <input type="text" placeholder="Contest title" name="title"/>
                        <input type="text" placeholder="Contest details" name="details"/>
                        <input placeholder="Starting Date" type="text" name="starting_date" onfocus="(this.type='datetime-local')" onblur="(this.type='text')" />
                        <input placeholder="Ending Date" type="text" name="ending_date" onfocus="(this.type='datetime-local')" onblur="(this.type='text')" />
                        <?php echo $vs->session('_error_bad_contest_data') ?>
                        <button type="submit">Add Contest</button>
                    </form>
                </div>
            </div>
        <?php foreach ($contests as $contest) : ?>
            <a class="contest-box" href="/contests/<?php echo $contest->getId(); ?>">
                <h3><?php echo $contest->getTitle(); ?></h3>
                <p><?php echo $contest->getDetails(); ?></p>
                <div class="contest-box-icons">
                    <p><img src="<?php echo $vs->assets('assets/icons/start-flag.svg'); ?>" /> <?php echo $contest->getStartingDate(); ?></p>
                    <p><img src="<?php echo $vs->assets('assets/icons/heart.svg'); ?>" /> <?php echo $contest->getLikes(); ?></p>
                    <p><img src="<?php echo $vs->assets('assets/icons/finish-flag.svg'); ?>" /> <?php echo $contest->getEndingDate(); ?></p>
                    <p><img src="<?php echo $vs->assets('assets/icons/gallery-layout.svg'); ?>" /> <?php echo $contest->getPictures(); ?></p>
                </div>
            </a>
        <?php endforeach; ?>
        </div>
<?php $vs->template->endblock() ?>

<?php $vs->template->startblock('scripts');?>


<script src=<?php $vs->assets('js/hamburger.js'); ?>></script>
<script src=<?php $vs->assets('js/userLikes.js'); ?>></script>

<?php $vs->template->endblock();?>

