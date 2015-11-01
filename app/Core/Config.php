<?php

namespace Core;

use Exceptions\FileNotFoundException;

class Config
{

    const CONFIG_FILES_PATH = '/../../configs/';

    use Singletonable;

    public function get($configFile)
    {
        $file = __DIR__ . self::CONFIG_FILES_PATH . $configFile . '.php';
        if (!file_exists($file)) {
            throw new FileNotFoundException();
        }

        return include($file);
    }

}