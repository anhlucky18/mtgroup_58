<?php

namespace Botble\Support\Providers;

use File;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * @author Sang
     */
    public function register()
    {
        File::requireOnce(__DIR__ . '/../../helpers/common.php');
    }
}
