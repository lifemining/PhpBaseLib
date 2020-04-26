<?php

namespace Lifemining\PhpBaseLib;

class Image {

    /**
     * return string to be included in img tag as inline data
     * @param $path to the image file
     * @return string formated image to base 64
     */
    public static function toDataInlineBase64 ($path) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = base64_encode(file_get_contents($path));
        return 'data:image/'.$type.';base64,'.$data;
    }
}