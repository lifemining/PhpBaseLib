<?php


namespace Lifemining\PhpBaseLib\Image;


use Exception as ExceptionAlias;

class Gd
{
    /**
     * makeAvatarCircle allow to create a circle avatar from an existing image and save it to a file or stream it to screen
     * @param string $img_src_path path to the image file
     * @param string|null $img_dst_path if path is given will save the avatar in a file
     * @param array $options
     * @return ExceptionAlias if source file not exists
     */
    public static function makeAvatarCircle ($img_src_path, $img_dst_path = null, array $options = array()) {
        $options = array_merge([
            'width'     => 100,
            'height'    => 100,
            'stream'    => false, // to stream the file over http
        ], $options);
        if (!file_exists($img_src_path)) return new ExceptionAlias('source image not exists');
        $img_src        = imagecreatefromstring(file_get_contents($img_src_path));
        $img_src_width  = imagesx($img_src);
        $img_src_height = imagesy($img_src);

        $img_dst = imagecreatetruecolor($options['width'], $options['height']);
        imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0,
            $options['width'], $options['height'], $img_src_width, $img_src_height);

        $mask = imagecreatetruecolor($options['width'], $options['height']);
        $red = imagecolorallocate($mask, 255, 0, 255);
        imagefill($mask, 0, 0, $red);
        $green = imagecolorallocate($mask, 0, 255, 0);
        imagecolortransparent($mask, $green);
        imagefilledellipse($mask, $options['width']/2, $options['height']/2,
            $options['width'], $options['height'], $green);


        imagecopymerge($img_dst, $mask, 0, 0, 0, 0,
            $options['width'], $options['height'], 100);
        imagecolortransparent($img_dst,$red);

        if (!is_null($img_dst_path)) imagepng($img_dst, $img_dst_path);
        if ($options['stream']) {
            header('Content-type: image/png');
            imagepng($img_dst);
        }

        imagedestroy($img_src);
        imagedestroy($img_dst);
        imagedestroy($mask);
    }
}