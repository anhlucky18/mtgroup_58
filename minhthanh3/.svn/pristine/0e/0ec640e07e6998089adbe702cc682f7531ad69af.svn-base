<?php

namespace Botble\Member;

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
        Schema::dropIfExists('members');
        Schema::dropIfExists('member_password_resets');
    }
}
