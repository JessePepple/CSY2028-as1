<?php

$categories = get_categories($db);
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $page_title ?? 'ibuy Auctions' ?></title>
		<link rel="stylesheet" href="ibuy.css" />
	</head>

	<body>
		<header>
			<h1><span class="i">i</span><span class="b">b</span><span class="u">u</span><span class="y">y</span></h1>

			<form action="search.php">
				<input type="text" name="search" placeholder="Search for anything" value="<?= form_value('search') ?>" />
				<input type="submit" name="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>

				<?php

				// display categories
				if($categories):
					foreach($categories as $row):
						?>
						<li><a class="categoryLink" href="/categories.php?id=<?= $row['id'] ?>"> <?= $row['name'] ?> </a></li>
						<?php
					endforeach;
				endif;
				?>
					<li><a class="categoryLink sub" href="javascript:void(0)">More</a></li>
			</ul>
		</nav>
		<img src="banners/1.jpg" alt="Banner" />
        <main>