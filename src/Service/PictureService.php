<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // Nouveau nom image 
        $file = md5(uniqid(rand(), true)) . '.webp';

        // Récupération infos
        $pictureInfos = getimagesize($picture);

        if($pictureInfos === false){
            throw new Exception('Format d\'image incorrect');
        }

        // Vérification format
        switch($pictureInfos['mime']){
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture);
                break;
            case 'image/jpg':
                $pictureSource = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $pictureSource = imagecreatefromwebp($picture);
                break;
            case 'image/gif' :
                $pictureSource = imagecreatefromgif($picture);
                break;
            default:
                throw new Exception('Format d\'image incorrect');
        }

        // Recadrage
        // Récupération dimensions
        $imageWidth = $pictureInfos[0];
        $imageHeight = $pictureInfos[1];

        // Orientation
        switch ($imageWidth <=> $imageHeight){
            case -1: // portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;
            case 0: // carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: // paysage
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;
        }

        // Nouvelle image vierge
        $resizedPicture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resizedPicture, $pictureSource, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory') . $folder;

        // Dossier de destination s'il n'existe pas
        if(!file_exists($path . '/miniatures/')){
            mkdir($path . '/miniatures/', 0755, true);
        }

        // Stokage image recadrée
        imagewebp($resizedPicture, $path . '/miniatures/' . $width . 'x' . $height . '-' . $file);

        $picture->move($path . '/', $file);

        return $file;
    }

    public function delete(string $file, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if($file !== 'default.webp'){
            $success = false;

            $path = $this->params->get('images_directory') . $folder;

            $miniature = $path . '/miniatures/' . $width . 'x' . $height . '-' . $file;

            if(file_exists($miniature)){
                unlink($miniature);
                $success = true;
            }

            $original = $path . '/' . $file;

            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}