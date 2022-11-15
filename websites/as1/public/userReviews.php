<?php
require_once('head.php');
require_once('helpers.php');

if(!isset($_GET['id']))
{
    header("Location: index.php");
    exit;
}

$user_id = $_GET['id'];
$author = get_user($user_id, $db);
if(!$author)
{
    header("Location: index.php");
    exit;
}
$reviews = get_user_reviews($user_id, $db);


include_once('header.php'); ?>

<section class="reviews">
    <h2>Reviews from <?= $author['name'] ?> </h2>
    <?php
    if($reviews): ?>
    <ul>
        <?php
        foreach($reviews as $row):
            ?>
            <li><strong><a href="userReviews.php?id=<?= $row['reviewer_id'] ?>"><?= $row['name'] ?></a> said </strong> <?= $row['review_text'] ?> <em><?= $row['date_posted'] ?></em></li>

            <?php
        endforeach;
        ?>

    </ul>
    <?php endif ?>
</section>


<?php include_once('footer.php'); 