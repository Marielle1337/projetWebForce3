<?php

$this->layout('layout', ['title' => 'ajouter un article']) ?>

<?php $this->start('main_content') ?>
<form action="#" method="POST" accept-charset="utf-8">
	<label>
		<input type="text" name="title" value=" " placeholder="le titre de votre article">
	</label>
	<label>
		<img src="<? echo ?>" alt="Photo de " /> 
	</label>

	<label>
		<textarea name="content" >Saisir un texte ici.</textarea>

	</label>
	</br>
	<button type="submit" name="add">ajouter un article</button>
	<button type="submit" name="add-stay">ajouter un article et rester ici</button>

</form>
<?php $this->stop('main_content') ?