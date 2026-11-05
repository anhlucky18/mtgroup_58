<?php

namespace Botble\Base\Interfaces;

interface PluginInterface
{

    /**
     * @author Sang
     */
    public static function activate();

    /**
     * @author Sang
     */
    public static function deactivate();

    /**
     * @author Sang
     */
    public static function remove();
}
