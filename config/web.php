<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db1 = require __DIR__ . '/db1.php';

use app\components\Uploads;
use app\builder\filters\BeforeResponseFilter;
use app\builder\filters\BeforeHandleActionFilter;

$config = [
    'id' => 'basic',    // 特别注意：该ID在多入口部署中，禁止修改；必须是basic。
    'name' => 'YII MANAGER CRM',
//    'language' => 'en-US', // 终端语言/目标语言 zh-CN / en / en-US
    'language' => 'zh-CN', // 终端语言/目标语言 zh-CN / en / en-US
    'sourceLanguage' => 'en-US', // 代码源语言,用于[[I18n]]翻译的源语言,即: messages/zh-CN/app.php中的[[key]]所使用的语言.
    'timeZone' => 'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@builder' => '@app/builder',
        '@api' => '@app/api',
    ],
    'as beforeHandleAction' => BeforeHandleActionFilter::class,
    'components' => [
        // 应用配置管理器
        'config' => [
            'class' => 'app\components\ConfigManager',
            'cache' => 'cache',
        ],
        // 文件上传管理器
        'uploads' => [
            'class' => 'app\components\Uploads',
            'type' => function () {
                return Uploads::LOCAL_UPLOAD_ENGINE_SYMBOL;
            },
            'configs' => [
                'rootUrl' => '@web/upload', // 外链域名
                'rootPath' => '@webroot' . DIRECTORY_SEPARATOR . 'upload', // 上传地址
            ],
        ],
        // 短信发送管理器
        'sms' => [
            'class' => 'app\components\Sms',
            'configs' => [
                // 飞鸽传书接口
                // `key` 是该接口的方法名
                'feiGe' => [
                    // 是否使用该接口
                    'enabled' => false,
                    // 接口自定义参数
                    'apiUrl' => 'https://api.4321.sh/sms/send',
                    'apiKey' => 'demoKey',
                    'secret' => 'demoSecret',
                    'signId' => 'demoSignId',
                ],
            ],
        ],
        // RBAC管理器
        'rbacManager' => [
            'class' => 'app\components\RbacManager',
            'cache' => 'cache',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XaAN1rnY43OVTxmc',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'name' => 'YMAPPSID',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'as beforeResponseSend' => BeforeResponseFilter::class,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // 接口用户认证类配置
        // 用于api模块
        'apiUser' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableSession' => false,
        ],
        // 网站用户认证类配置
        // 用于basic模块
        // 会话期内,自动登录
        // (!!!!!!!!!!!!! 注: 该模块还未开发 !!!!!!!!!!!!)
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity_front', 'httpOnly' => true],
            'idParam' => '__user_id',
            'returnUrlParam' => '__front_returnUrl',

            // when `enableAutoLogin` is `false`
            'authTimeoutParam' => '__front_expire',
            'absoluteAuthTimeoutParam' => '__front_absoluteExpire',
        ],
        // 后台用户认证类配置
        // 用于admin模块
        // 会话期内,未活动30分钟自动退出
        'adminUser' => [
            'class' => 'yii\web\User',
            // rbac认证
            'accessChecker' => 'rbacManager',
            // 身份认证
            'identityClass' => 'app\models\AdminUser',
            // 登录路由
            'loginUrl' => ['admin/site/login'],

            // ------ 以下参数在配置enableSession=true时生效 ------
            'idParam' => '__admin_id',
            'returnUrlParam' => '__admin_returnUrl',

            // 在会话期内, 是否开启自动登录
            // 开启后再cookie有效期（平台设置的时3天）内登陆，自动刷新cookie有效期。
            'enableAutoLogin' => true,
            // 该属性当前enableAutoLogin=true时,生效
            'identityCookie' => ['name' => '_identity_backend', 'httpOnly' => true],

            // when `enableAutoLogin` is `false`
            'authTimeoutParam' => '__admin_expire',
            'absoluteAuthTimeoutParam' => '__admin_absoluteExpire',
            // 在会话期内(平台设置的时3天),如果用户未活动则在24小时后,自动退出登录.
            // 如果enableAutoLogin=true请注释该配置
            //'authTimeout' => 86400,
            // 在会话期内(平台设置的时3天),自用户登录后24小时以后,自动退出登录(无论用户是否存在活动).
            // 如果enableAutoLogin=true请注释该配置
            // absoluteAuthTimeout => 86400,
        ],
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
        // 邮件管理器
        'mailManager' => [
            'class' => 'app\components\MailManager',
            'sender' => 'mailer',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            // 当`true`时：开启调试模式，邮件不真正发送，只是保存到`@runtime/mail`。
            // `false`时：想要真正发送，需要配置`transport`参数。
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'demo@163.com',
                'password' => 'demoPassword',
                'port' => '994',
                'encryption' => 'ssl',
            ],
            /**
             * @see yii\swiftmailer\Message
             */
            'messageConfig' => [
                'charset' => 'UTF-8',
                //'from' => '',
                //'replyTo' => '',
                //'cc' => '',
                //'bcc' => '',
                //'subject' => '',
                //'headers' => [],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource', // 翻译类
                    //'basePath' => '@app/messages', // 消息源根目录,不设置则使用[[应用主体]]默认的
                    //'sourceLanguage' => 'en-US', // 源语言,不设置则使用[[应用主体]]默认的
                    'fileMap' => [
                        // 种类 => 种类文件
                        'app' => 'app.php',
                        'app.admin' => 'admin.php',
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'db' => [
                    //'class' => 'yii\log\FileTarget',
                    'class' => 'app\components\LogDbTarget',
                    'levels' => ['warning', 'error'],
                ],
            ],
        ],

        'db' => $db,
        'db1' => $db1,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => YII_ENV_DEV,
            /* @see compress/assets.php */
            //'bundles' => require(__DIR__ . '/../compress/assets-prod.php'),
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
