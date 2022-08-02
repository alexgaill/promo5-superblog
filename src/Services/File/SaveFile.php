<?php
namespace App\Services\File;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Class dédiée à l'enregistrement des fichiers
 */
class SaveFile {

    /**
     * Déplace un fichier dans le dossier dédié et génère un nom unique
     *
     * @param File $picture
     * @param string $folder
     * @return string
     */
    public function saveUploadedFile (File $picture, string $folder): string
    {
        try {
            
            // On créé un nom unique pour chaque image en concaténant l'extension du fichier
            $pictureName = md5(uniqid()) . '.' . $picture->guessExtension();

            // On enregsitre le fichier d'image dans un dossier img
            $picture->move(
                $folder,// Le dossier dans lequel est déplacé le fichier
                $pictureName// Le nom du fichier
            );

            return $pictureName;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}