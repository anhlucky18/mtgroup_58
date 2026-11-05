<?php

namespace Botble\AuditLog;

use Botble\Base\Interfaces\PluginInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
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
        Schema::dropIfExists('audit_history');
        app(DashboardWidgetInterface::class)->deleteBy(['name' => 'widget_audit_logs']);
    }
}
