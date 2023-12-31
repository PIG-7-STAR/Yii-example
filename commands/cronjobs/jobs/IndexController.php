<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\commands\cronjobs\jobs;

use yii\helpers\Console;
use yii\console\ExitCode;
use app\commands\cronjobs\Cron;
use app\commands\cronjobs\business\Demo;

/**
 * cron jobs demo
 * @author cleverstone
 * @since ym1.0
 */
class IndexController extends Cron
{
    /**
     * default cron
     * @return int
     */
    public function actionIndex()
    {
        $business = new Demo();
        $this->stdout($business->getResult(), Console::FG_GREEN);

        return ExitCode::OK;
    }
}