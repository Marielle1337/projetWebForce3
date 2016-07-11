<?php

namespace Controller;

use \W\Controller\Controller;

class BlogController extends Controller
{

    /**
     * Page d'accueil par défaut
     */
    public function home()
    {
        $managerArticles = new \Manager\BlogManager();
        $managerArticles->setTable('articles');
        $articles = $managerArticles->findAll($orderBy = 'dateCreated', $orderDir = 'DESC', $limit = 5);

        $this->show('blog/home', ['articles' => $articles]);
    }

    //ajouter un article


    public function add()
    {
        //si tu es admin 
        //$this->allowTo('admin');

        //var_dump($_FILES); etat des lieux mettre en com a la fin ou supprimer la ligne
        // Autorisation
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = strip_tags(trim($value));
            }
        }


        // Je verifie si j'ai une soumission de formulaire
        if (isset($_POST['addArticle']) || isset($_POST['addArticleAndStay'])) {
            $errors = [];

            // Verifications
            if (empty($_POST['title'])) {
                $errors['title']['empty'] = true;
            }
            if (empty($_POST['dateCreated'])) {
                $errors['dateCreated']['empty'] = true;
            }
            if (empty($_FILES['picture'])) {
                $errors['picture']['empty'] = true;
            }

            // Ajout en DB
            if (count($errors) === 0) {
                // Si j'ai pas d'erreur
                $title = $_POST['title'];
                $dateCreated = $_POST['dateCreated'];
                $content = $_POST['content'];
                $picture = $_FILES['picture'];

                $managerArticles = new \Manager\BlogManager();
                

                

                // traitement du medias
                // Vérifier si le téléchargement du fichier n'a pas été interrompu
                if ($_FILES['picture']['error'] != UPLOAD_ERR_OK) {
                    echo 'Erreur lors du téléchargement.';
                } else {
                    // Objet FileInfo
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);

                    // Récupération du Mime
                    $mimeType = $finfo->file($_FILES['picture']['tmp_name']);

                    $extFoundInArray = array_search(
                        $mimeType, array(
                            'jpg' => 'image/jpeg',
                            'png' => 'image/png',
                            'gif' => 'image/gif',
                            //'mpeg' => 'video/mpeg',
                            //'mp4' => 'video/mp4',
                        )
                    );
                    if ($extFoundInArray === false) {
                        echo 'Le fichier n\'est pas une image';
                        
                        
                    } else {

                        // Renommer nom du fichier
                        $fileName = sha1_file($_FILES['picture']['tmp_name']) . time() . '.' . $extFoundInArray;
                        $path = __DIR__ . '/../../uploads/' . $fileName;
                        $moved = move_uploaded_file($_FILES['picture']['tmp_name'], $path);
                        if (!$moved) {
                            echo 'Erreur lors de l\'enregistrement';
                            
                        } else {
                            //on redimensionne le medias

                            //si je suis jpg ou png ou gif
                            if($extFoundInArray == 'gif' || $extFoundInArray == 'png' || $extFoundInArray == 'jpg' )
                            {
                             //resize
                             $resize = $this ->resize($path, null, 80, 80, $path);
                            }
                            // On a bien uploadé le fichier

                            $data = [
                                'title' => $title,
                                'dateCreated' => $dateCreated,
                                'content' => $content,
                                'picture' => $picture,
                            ];
                            $managerArticles->insert($data);
                            
                            if (isset($_POST['addArticle'])) {
                                // Ajout et redirection
                                $this->redirectToRoute('home');
                            } else { // Facultatif
                                // Si j'ai clique sur 'rester'
                                $this->redirectToRoute('add');
                            }
                        }
                    } // End File Is Image
                } // End Upload Success
            }
            // Si j'ai une erreur
            // J'affiche le template, avec un tableau d'erreurs
            $this->show('blog/add', ['errors' => $errors]);
        }

        $this->show('blog/add');
    }

    public function delete($id)
    {
        //si tu es admin 
        //$this->allowTo('admin');
        
        $managerArticles = new \Manager\BlogManager();
        $managerArticles->setTable('articles');
        $managerArticles->delete($id);
        $this->redirectToRoute('liste');
    }
    
    public function liste()
    {
        //si tu es admin 
        //$this->allowTo('admin');
        
        $managerArticles = new \Manager\BlogManager();
        $managerArticles->setTable('articles');
        $articles = $managerArticles -> findAll();
        $this->show('blog/liste', ['articles'=>$articles]);
    }

    public function grid()
    {
        //si tu es admin 
        //$this->allowTo('admin');
        
        $managerArticles = new \Manager\BlogManager();
        $managerArticles->setTable('articles');
        $articles = $managerArticles -> findAll($orderBy = "dateCreated", $orderDir = "DESC", $limit = 5);
        $this->show('blog/grid', ['articles'=>$articles]);
    }
    
    private function resize($file, $string = null, $width = 0, $height = 0, $output = 'file')
    {
        if ($height <= 0 && $width <= 0)
            return false;

        if ($file === null && $string === null)
            return false;

        # Setting defaults and meta

        $info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);

        $image = '';

        $final_width = 0;

        $final_height = 0;

        list($width_old, $height_old) = $info;

        $cropHeight = $cropWidth = 0;

        # Calculating proportionality

        if ($proportional) {

            if ($width == 0)

                $factor = $height / $height_old;

            elseif ($height == 0)

                $factor = $width / $width_old;

            else

                $factor = min($width / $width_old, $height / $height_old);

            $final_width = round($width_old * $factor);

            $final_height = round($height_old * $factor);

        }

        else {

            $final_width = ( $width <= 0 ) ? $width_old : $width;

            $final_height = ( $height <= 0 ) ? $height_old : $height;

            $widthX = $width_old / $width;

            $heightX = $height_old / $height;

            $x = min($widthX, $heightX);

            $cropWidth = ($width_old - $width * $x) / 2;

            $cropHeight = ($height_old - $height * $x) / 2;

        }

        # Loading image to memory according to type

        switch ($info[2])

        {

            case IMAGETYPE_JPEG: $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);

                break;

            case IMAGETYPE_GIF: $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);

                break;

            case IMAGETYPE_PNG: $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);

                break;

            default: return false;

        }

        # This is the resizing/resampling/transparency-preserving magic

        $image_resized = imagecreatetruecolor($final_width, $final_height);

        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {

            $transparency = imagecolortransparent($image);

            $palletsize = imagecolorstotal($image);

            if ($transparency >= 0 && $transparency < $palletsize) {

                $transparent_color = imagecolorsforindex($image, $transparency);

                $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);

                imagefill($image_resized, 0, 0, $transparency);

                imagecolortransparent($image_resized, $transparency);

            } elseif ($info[2] == IMAGETYPE_PNG) {

                imagealphablending($image_resized, false);

                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);

                imagefill($image_resized, 0, 0, $color);

                imagesavealpha($image_resized, true);

            }

        }


        imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);

        # Taking care of original, if needed

        if ($delete_original) {

            if ($use_linux_commands)

                exec('rm ' . $file);

            else

                @unlink($file);

        }

        # Preparing a method of providing result

        switch (strtolower($output))

        {

            case 'browser':

                $mime = image_type_to_mime_type($info[2]);

                header("Content-type: $mime");

                $output = NULL;

                break;

            case 'file':

                $output = $file;

                break;

            case 'return':

                return $image_resized;

                break;

            default:

                break;

        }
        
        # Writing image according to type to the output destination and image quality

        switch ($info[2])

        {

            case IMAGETYPE_GIF: imagegif($image_resized, $output);

                break;

            case IMAGETYPE_JPEG: imagejpeg($image_resized, $output, $quality);
                
                

                break;

            case IMAGETYPE_PNG:

                $quality = 9 - (int) ((0.9 * $quality) / 10.0);

                imagepng($image_resized, $output, $quality);

                break;

            default: return false;

        }

        return true;
    }

}
