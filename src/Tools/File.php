<?php

namespace Lifemining\PhpBaseLib\Tools;

class File
{

    public static function isNewer($pathfile_new, $pathfile_old)
    {
        if (!file_exists($pathfile_old)) {
            return true;
        }
        return (filemtime($pathfile_new) > filemtime($pathfile_old));
    }

    /**
     *
     * @param array $array_files
     * @param string $order 'desc' | 'asc'
     * @param int $limit
     */
    public static function orderByDate(array $array_files, $order, $limit = null)
    {
        $files = array();
        foreach ($array_files as $f) {
            if (file_exists($f)) {
                $files[filemtime($f)] = $f;
            }
        }
        if (strtolower($order) === 'asc') {
            ksort($files);
        } else {
            krsort($files);
        }
        $files = array_values($files);
        if ($limit && $limit > 0 && $limit < count($files)) {
            $files = array_slice($files, 0, $limit);
        }
        return $files;
    }

    public static function getNbLines($file, $offset = null)
    {
        return String::getNbLines(file_get_contents($file), $offset);
    }

    public static function getUniqueName($dir, $baseName, $extension, $max = 9)
    {
        $dir = Dir::addSepToEnd($dir);
        if (Dir::isDir($dir)) {
            for ($cpt = 1; $cpt <= $max; $cpt++) {
                $newFile = $dir . $baseName . '_' . Number::fillZero($cpt, strlen(strval($max))) . '.' . $extension;
                if (!is_file($newFile)) {
                    return $newFile;
                }
            }
            trigger_error('la limite maximun d\'occurence de création de fichier a été atteinte "' . $max . '"');
        } else {
            trigger_error('le répertoire de base "' . $dir . '" n\'existe pas');
        }
        return null;
    }


    public static function md5($path)
    {
        return md5(file_get_contents($path));
    }

    public static function create($dst, $data, $bool_create_dir = true)
    {
        $dir = Dir::dirname($dst);
        if ($bool_create_dir && !Dir::isDir($dir)) {
            Dir::create($dir);
        }
        return (file_put_contents($dst, $data) !== false);
        //return (file_put_contents($dst, $data) && self::chmod($dst));
    }

    public static function move($src, $dst, $bool_create_dir = true)
    {
        $dir = Dir::dirname($dst);
        if ($bool_create_dir && !Dir::isDir($dir)) {
            Dir::create($dir);
        }
        if (copy($src, $dst)) {
            unlink($src);
            return true;
        }
        return false;
        //return (rename ($src, $dst));
        //return (rename ($src, $dst) && self::chmod($dst));
    }

    public static function copy($src, $dst, $bool_create_dir = true)
    {
        $dir = Dir::dirname($dst);
        if ($bool_create_dir && !Dir::isDir($dir)) {
            Dir::create($dir);
        }
        return (copy($src, $dst));
        //return (copy ($src, $dst) && self::chmod($dst));
    }

    public static function chmod($file, $mode = 0664)
    {
        return chmod($file, $mode);
    }

    public static function getExtension($file, $bool_to_lower = true)
    {
        $tmp = explode('.', $file);
        $ext = end($tmp);
        if ($bool_to_lower) {
            $ext = strtolower($ext);
        }
        return $ext;
    }

    public static function getMime($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mime_type = finfo_file($finfo, $file);
            finfo_close($finfo);
            if ($mime_type) {
                return $mime_type;
            }
        }
        $extension = self::getExtension($file);
        switch ($extension) {
            case "js" :
                //return "application/x-javascript";
                return "application/javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
                return "image/" . strtolower($fileSuffix[1]);

            case 'ico' :
                return 'image/vnd.microsoft.icon';

            case 'tif':
            case 'tiff':
                return 'image/tiff';

            case 'svg':
            case 'svgz':
                return 'image/svg+xml';

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case 'psd':
                return 'image/vnd.adobe.photoshop';

            case 'ai':
            case 'eps':
            case 'ps':
                return 'application/postscript';

            // ms office
            case 'doc':
                return 'application/msword';
            case 'rtf':
                return 'application/rtf';
            case 'xls':
                return 'application/vnd.ms-excel';

            // open office
            case 'odt':
                return 'application/vnd.oasis.opendocument.text';
            case 'ods':
                return 'application/vnd.oasis.opendocument.spreadsheet';

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "phtml" :
            case "tpl" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mov" :
            case "qt" :
                return "video/quicktime";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            case 'rar':
                return 'application/x-rar-compressed';
            case 'exe':
                return 'application/x-msdownload';
            case 'msi':
                return 'application/x-msdownload';
            case 'cab':
                return 'application/vnd.ms-cab-compressed';

            default:
                return 'application/octet-stream';
        }
    }

    public static function getBaseName($file, $bool_without_suffix = true)
    {
        if ($bool_without_suffix) {
            return basename($file, '.' . self::getExtension($file, false));
        } else {
            return basename($file);
        }
    }

    public static function sendFile($file)
    {
        header("Content-Type: " . self::getMime($file) . "");
        header("Content-disposition: attachment; filename=" . basename($file) . ";");
        header("Content-Length: " . filesize($file));
        //header('Content-Transfer-Encoding: Binary');
        //header('Expires: 0');
        header('Accept-Ranges: bytes');
        header('ETag: "' . md5($file) . '"');
        readfile($file);
        exit();
    }

    public static function formatHumanSize($bytes_size, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = intval(floor((strlen($bytes_size) - 1) / 3));
        return sprintf("%.{$decimals}f", $bytes_size / pow(1024, $factor)) . @$sz[$factor];
    }

    public static function getSize($path, $bool_human = true)
    {
        $size = 0;
        if (file_exists($path)) {
            $size = filesize($path);
        }
        if ($bool_human) {
            $size = self::formatHumanSize($size);
        }
        return $size;
    }
}