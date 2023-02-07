<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin;

use Yii;
use yii\web\Response;

/**
 * 后台管理模块
 * @author cleverstone
 * @since ym1.0
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\admin\controllers';

    /**
     * @var string the default route of this module. Defaults to `default`.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     */
    public $defaultRoute = 'index';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->setErrorHandler();

        $this->setEventHandler();
    }

    /**
     * 绑定模块事件处理器
     *
     * 注：两种为组件绑定事件处理器的方法，第一种比第二种运行提前。
     */
    public function setEventHandler()
    {
        Yii::$app->response->on(Response::EVENT_AFTER_SEND, ['app\eventhandlers\AdminBehaviorRecordHandler', 'handleClick'], null, false);
        //Event::on(Response::className(), Response::EVENT_AFTER_SEND, ['app\eventhandlers\AdminBehaviorRecordHandler', 'handleClick'], null, false);
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->on(self::EVENT_BEFORE_ACTION, ['app\eventhandlers\RouteControlHandler', 'handleClick'], null, false);

        return parent::beforeAction($action);
    }

    /**
     * 设置当前模块的错误处理动作
     */
    public function setErrorHandler()
    {
        Yii::$app->errorHandler->errorAction = 'admin/error/error';
    }
}
