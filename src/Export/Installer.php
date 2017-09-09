<?php

namespace Rmanara\Export;

use Composer\Script\Event;

/**
 * Description of Installer
 *
 * @author Rodrigo Manara <me@rodrigomanara.co.uk>
 */
class Installer {

    public static function Init(Event $event) {

        $path = __DIR__;
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $root = self::getDir($path);
     
        copy($vendorDir .DIRECTORY_SEPARATOR. "rmanara".DIRECTORY_SEPARATOR."export".DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR."console", $root .DIRECTORY_SEPARATOR. "console");
    
        echo "file ready";
        
    }

    /**
     * 
     * @param type $dir
     * @return type
     */
    private static function getDir($dir) {

        preg_match("/(.*?)upload/", $dir, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return $dir;
    }

}
