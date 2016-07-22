<?php $this->layout('layout', ['title' => $article['title'], 'categories'=>$categories]) ?>

<?php $this->start('main_content') ?>

	<article>
	   
        <div>Posté le <?php echo date('d/m/Y', strtotime($article['dateCreated'])) ?>, 
        par <?php if (is_numeric($article['author'])) { echo $author['login']; } else { echo $article['author']; }?></div>
       <?php if($article['picture']) { echo '<img class="banniere" src="'.$this->assetUrl('/uploads/'.$article['picture']).'">'; } ?>
	    <p><?php echo $article['content'] ?></p>

	</article>

<!--  Ajouter des commentaires -->
    <form action="#" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
    <fieldset> <legend>Ajouter un commentaire</legend>
        
        <label>
            <input type="text" name="author"  placeholder="Votre nom" <?php if(isset($_POST['author'])) { echo 'value="'.$_POST['author'].'"';}?> >
            <?php if (isset($errors['author'])) { echo $errors['author'] ;} ?>
        </label>

        <label>
            <input type="mail" name="email"  placeholder="Votre email (facultatif)" <?php if(isset($_POST['email'])) { echo 'value="'.$_POST['email'].'"';}?>>
        </label>
    
        <label>
            <textarea name="content" placeholder="Saisir un texte ici."><?php if(isset($_POST['content'])){ echo $_POST['content']; } ?></textarea>
            <?php if (isset($errors['content'])) { echo $errors['content'] ; } ?>
        </label>

        <button type="submit" name="addComment">Ajouter un commentaire</button>

    </fieldset>
    </form>

    <!--  Affichage des commentaires -->
    <section>
            <?php foreach($comments as $comment) : ?>
                <article>

                    <p>
                        <div>
                            <?= 'Par '.$comment['author'].', le '. date('d/m/Y', strtotime($comment['dateCreated'])) ?>
                        </div>  
                        <?php echo $comment['content'] ?>
                    </p>

                </article>
            <?php endforeach; ?>
    </section>

<?php $this->stop('main_content') ?>