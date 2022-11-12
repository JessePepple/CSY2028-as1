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

			<form action="#">
				<input type="text" name="search" placeholder="Search for anything" />
				<input type="submit" name="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>
				<li><a class="categoryLink" href="/index.php">Home</a></li>

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
				<?php 
				// we display different authentication links for logged and non logged users
				if(isset($_SESSION['id'])):
					?>
					<li><a class="categoryLink" href="/logout.php">Logout</a></li>
					<?php
					else: ?>
					<li><a class="categoryLink" href="/login.php">Login</a></li>
					<li><a class="categoryLink" href="/register.php">Register</a></li>
					<?php endif; ?>
			</ul>
		</nav>
		<img src="banners/1.jpg" alt="Banner" />
        <main>