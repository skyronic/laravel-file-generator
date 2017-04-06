<?php

namespace Skyronic\Cookie;

class FormatHelper
{
    public static function namespaceForClass ($path, $rootPath = null, $rootNs = null) {
        if (is_null($rootPath)) {
            $rootPath = 'app';
            $rootNs = "App";
        }

        // if there's a ".php" or similar extension let's strip it.
        $parts = explode(".", $path);
        $path = $parts[0];

        $nsString = str_replace($rootPath, $rootNs, $path);
        $nsString = str_replace("/", "\\", $nsString);
        return $nsString;
    }

    /**
     * Strips
     * @param $input
     */
    public static function getBaseName ($input) {
        $input = FileHelper::fixDirSeparator($input);
        $basename = basename($input);
        $parts = explode('.', $basename);
        return $parts[0];
    }
}