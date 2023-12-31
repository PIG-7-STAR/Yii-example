<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Angular-select2
 * @author cleverstone
 * @since ym1.0
 */
class AngularSelect2 extends BaseAsset
{
    public $js = [
        'libs/angular-1.7.5/angular-select2.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        JqueryAsset::class,
        Select2Asset::class,
        AngularAsset::class,
    ];
}