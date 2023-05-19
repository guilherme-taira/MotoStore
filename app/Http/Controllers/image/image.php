<?php

namespace App\Http\Controllers\image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class image
{
    private $image;
    private $type;

    public function __construct($image)
    {
        $this->image = imagecreatefromstring(file_get_contents($image));
        // PEGA O VALOR DA IMAGEM
        $info = pathinfo($image);
        $this->type = $info['extension'] == 'jpg' ? 'jpeg' : $info['extension'];
    }

    public function print($quality = 100)
    {
        header('Content-type: image/' . $this->type);
        $this->output(null,$quality);
    }

    public function resize($newwidth,$newheight){
        $this->image = imagescale($this->image,$newwidth,$newheight);
    }

    public function output($localfile, $quality = 100)
    {
        switch ($this->type) {
            case 'jpeg':
                imagejpeg($this->image, $localfile, $quality);
                break;
            case 'png':
                imagejpeg($this->image, $localfile, $quality);
                break;
            case 'bmp':
                imagebmp($this->image, $localfile, $quality);
                break;
            case 'webp':
                imagewebp($this->image, $localfile, $quality);
                break;
        }
    }
}
