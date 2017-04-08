<?php


namespace Skyronic\FileGenerator;


class FileHelper
{
    public static function fixDirSeparator ($path) {
        $path = str_replace("\\", DIRECTORY_SEPARATOR, $path);
        $path = str_replace("/", DIRECTORY_SEPARATOR, $path);

        return $path;
    }
}