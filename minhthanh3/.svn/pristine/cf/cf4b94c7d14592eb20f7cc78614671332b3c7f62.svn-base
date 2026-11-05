<?php

namespace Botble\Language;

use Botble\Base\Interfaces\PluginInterface;
use Schema;

class Plugin implements PluginInterface
{

    /**
     * @author Sang
     */
    public static function activate()
    {
    }

    /**
     * @author Sang
     */
    public static function deactivate()
    {
    }

    /**
     * @author Sang
     */
    public static function remove()
    {
        Schema::dropIfExists('languages');
        Schema::dropIfExists('language_meta');
    }
}
