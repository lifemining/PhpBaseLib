<?php

namespace Lifemining\PhpBaseLib\Tools;

class Dir
{

    public static function createUnique($dir, $baseName, $max = 9)
    {
        $dir = self::addSepToEnd($dir);
        if (self::isDir($dir)) {
            for ($cpt = 1; $cpt <= $max; $cpt++) {
                $newDir = self::addSepToEnd($dir . $baseName . '_' . Number::fillZero($cpt, strlen(strval($max))));
                if (!self::isDir($newDir)) {
                    if (self::create($newDir)) {
                        return $newDir;
                    } else {
                        trigger_error('Erreur lors de la création du répertoire "' . $newDir . '"');
                        return null;
                    }
                }
            }
            trigger_error('la limite maximun d\'occurence de création de répertoire a été atteinte "' . $max . '"');
        } else {
            trigger_error('le répertoire de base "' . $dir . '" pour la création d\'un sous répertoire n\'existe pas');
        }
        return null;
    }

    public static function addSepToEnd($dir, $sep = '/')
    {
        return Str::addCharToEnd($dir, $sep);
    }


    /**
     * efface tout le contenu d'un dossier sans effacer celui ci
     * @param string $dir le répertoire à vider
     */
    public static function rmContent($dir)
    {
        $dirs = self::getDirs($dir);
        foreach ($dirs as $d) {
            self::rRmdir($dir . '/' . $d);
        }
        $files = self::getFiles($dir);
        foreach ($files as $f) {
            unlink($dir . '/' . $f);
        }
    }

    /**
     * récupère les chemins (récursifs) des fichiers contenu dans un répertoire
     * @param string $dir le répertoire à scanner
     * @return array
     */
    public static function getContent($dir)
    {
        $tmp = array();
        if (is_dir($dir)) {
            $dir = self::addSepToEnd($dir);
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . $object) == "dir") {
                        $tmp = array_merge($tmp, self::getContent($dir . $object . '/'));
                    } else {
                        $tmp[] = $dir . $object;
                    }
                }
            }
            reset($objects);
        }
        return $tmp;
    }

    /**
     * efface un répertoire et tout son contenu
     * @param string $dir le répertoire à effacer
     */
    public static function rRmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        self::rRmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * efface recursivement les répertoires vides ou n'ayant pas les extensions voulues
     * @param string $dir le rep à scanner
     * @param array $ext extension recherchées, si trouvée => rep non vide
     */
    public static function rRmEmptyDir($dir, $ext = array())
    {
        $dir = self::addSepToEnd($dir);
        if (is_dir($dir)) {
            if (self::isEmpty($dir, $ext, true)) {
                self::rRmdir($dir);
            } else {
                foreach (self::getDirs($dir) as $childDir) {
                    self::rRmEmptyDir($dir . $childDir, $ext);
                }
            }
        }
    }

    /**
     * Renvoie le chemin du dossier parent
     * @param string $path
     * @return string
     */
    public static function dirname($path)
    {
        return dirname($path) . '/';
    }

    /**
     *
     * @param string $path
     * @return bool
     */
    public static function isDir($path)
    {
        return is_dir($path);
    }

    /**
     *
     * @param string $path
     * @param array $ext
     * @param bool $rec
     * @return bool
     */
    public static function isEmpty($path, $ext = array(), $rec = true)
    {
        return (count(self::getFiles($path, $ext, $rec)) == 0);
    }

    /**
     * vérifie si un répertoire existe sinon le créé
     * @param string $dir le chemin du répertoire à vérifier
     * @return boolean true si existe sinon false
     */
    public static function check($dir)
    {
        if (!self::isDir($dir)) {
            return self::create($dir);
        }
        return true;
    }

    /**
     *
     * @param type $path
     * @param type $rec
     * @param type $mode
     * @return type
     */
    public static function create($path, $rec = true, $mode = 0775)
    {
        $oldumask = umask(0);
        $result = mkdir($path, $mode, $rec);
        umask($oldumask);
        return $result;
    }

    /**
     * Recherche les fichiers dans un répertoire
     * @param string $path le chemin du répertoire
     * @param array $ext si défini, indique les extensions à garder
     * @param boolean $rec [ true | false ] mode récursif
     * @param array $prefix débuts de nom de fichier
     * @param string $prefix_action [ skip (ne prends pas) | keep (prends) ]
     * @param string $base utilisé pour le mode récursif pour conserver le chemin déjà parcourut
     * @return array le résultat des fichiers trouvés
     */
    public static function getFiles($path, $ext = array(), $rec = false, $prefix = array(), $prefix_action = 'skip', $base = '')
    {
        $files = array();
        $path = self::addSepToEnd($path);
        if (self::isDir($path)) {
            $h = opendir($path);
            if ($h) {
                while (($f = readdir($h)) !== false) {
                    if ($f !== '.' && $f !== '..') {
                        if (!self::isDir($path . $f)) {
                            if (count($prefix) == 0
                                or ($prefix_action == 'skip' && !Str::hasPrefix($f, $prefix))
                                or ($prefix_action == 'keep' && Str::hasPrefix($f, $prefix))
                            ) {
                                if (sizeof($ext)) {
                                    if (in_array(File::getExtension($f), $ext)) {
                                        array_push($files, $base . $f);
                                    }
                                } else {
                                    array_push($files, $base . $f);
                                }
                            }
                        } else if ($rec) {
                            $tmp = self::getFiles($path . $f . '/', $ext, $rec, $prefix, $prefix_action, $base . $f . '/');
                            $files = array_merge($files, $tmp);
                        }
                    }
                }
                closedir($h);
            }
        }
        return $files;
    }

    /**
     * récupère tous les répertoires d'un dossier
     * @param string $path le chemin du répertoire à scanner
     * @param boolean $rec recurrsif
     * @param boolean $naturalOrder repertoire parent avant les répertoires enfant
     * @param string $base chemin de base à rajouter devant chaque entrée
     * @return array
     */
    public static function getDirs($path, $rec = false, $naturalOrder = true, $base = '')
    {
        $dirs = array();
        if (self::isDir($path)) {
            $h = opendir($path);
            if ($h) {
                while ($d = readdir($h)) {
                    if ($d != '.' && $d != '..') {
                        if (self::isDir($path . $d)) {
                            if ($naturalOrder) {
                                array_push($dirs, $base . $d);
                            }
                            if ($rec) {
                                $tmp = self::getDirs($path . $d . '/', $rec, $naturalOrder, $base . $d . '/');
                                $dirs = array_merge($dirs, $tmp);
                            }
                            if (!$naturalOrder) {
                                array_push($dirs, $base . $d);
                            }
                        }
                    }
                }
            }
        }
        return $dirs;
    }

    /**
     * alias dirname
     * @param $sDir
     * @return type
     */
    public static function getParent($sDir)
    {
        return self::dirname($sDir);
    }
}