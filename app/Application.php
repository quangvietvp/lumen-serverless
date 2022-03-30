<?php
namespace App;

use Laravel\Lumen\Application as LumenApplication;

class Application extends LumenApplication
{
    public function storagePath($path = '')
    {
        $this->storagePath = '/tmp';
        return ($this->storagePath ?: $this->basePath.DIRECTORY_SEPARATOR.'storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
