<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title><?= $this->e($title) ?></title>

	<link rel="stylesheet" href="<?= $this->assetUrl('css/style.css') ?>">
</head>
<body>
	<div class="container">
		<header>
			<h1><?= $this->e($title) ?></h1>
		</header>

		<main>
			<?= $this->section('main_content') ?>
		</main>

		<?php if($this->section('bottom_links')) : ?>
            <footer>
                <?= $this->section('bottom_links') ?>
            </footer>
        <?php endif ?>
	</div>
</body>
</html>