<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\builder\table\Table;
use app\builder\table\ToolbarFilterOptions;

/* @var $this \yii\web\View     当前视图实例 */
/* @var array $columns          数据列选项 */
/* @var boolean $hideCheckbox   是否隐藏第一列复选框 */
/* @var array $checkboxOptions  第一列复选框选项 */
/* @var array $rowActions       表格行操作项 */
/* @var array $rowActionIds     表格行操作项ID */
/* @var array $widgets          切点处要加入组件 */
/* @var array $toolbars         工具栏操作项 */
/* @var array $filterColumns    筛选表单选项 */
/* @var boolean $exportFlag     是否导出 */
?>

<div class="panel panel-default" ng-controller="_EasyApp_tableCtrl">
    <!--页面标题-->
    <?php if(!empty($this->title)): ?>
        <div class="panel-heading border-bottom">
            <span class="f-13"><?= $this->title ?></span>
        </div>
    <?php endif; ?>

    <!--工具栏开始-->
    <?php Table::beginTableTool($widgets); ?>

    <!--头部工具栏-->
    <?php if (!empty($toolbars)): ?>
        <div class="panel-body border-bottom">

            <!--工具栏左-->
            <div class="col-sm-12 col-md-6 px-0 py-3 clearfix">
                <?php if (!empty($toolbars['left']) && is_array($toolbars['left'])): ?>
                    <div class="btn-group btn-group-sm pull-left">
                        <!--自定义-->
                        <?php foreach ($toolbars['left'] as $item): ?>
                            <a href="#" type="button" class="btn btn-default" ng-click="triggerTableCustomMethod(<?= html_escape(Json::encode($item)) ?>)">
                                <i class="<?= !empty($item['icon']) ? $item['icon'] : '' ?>" aria-hidden="true"></i>
                                <span><?= !empty($item['title']) ? $item['title'] : '' ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!--工具栏右-->
            <div class="col-sm-12 col-md-6 px-0 py-3 clearfix">
                <?php if (!empty($toolbars['right']) && is_array($toolbars['right'])): ?>
                    <div class="btn-group btn-group-sm pull-right">
                        <?php foreach ($toolbars['right'] as $item): ?>
                            <?php switch ($item['type']): case 'refresh': // refresh ?>
                                <!--刷新-->
                                <a href="#" type="button" id="ym_script_refresh" class="btn btn-default" title="刷新">
                                    <i class="<?= !empty($item['icon']) ? $item['icon'] : 'glyphicon glyphicon-refresh' ?>" aria-hidden="true"></i>
                                    <span><?= !empty($item['title']) ? $item['title'] : '' // 刷新 ?></span>
                                </a>
                                <?php break; case 'filter': // filter ?>
                                <!--筛选-->
                                <a href="#" type="button" class="btn btn-default" ng-click="triggerTableFilterMethod()" title="筛选">
                                    <i class="<?= !empty($item['icon']) ? $item['icon'] : 'glyphicon glyphicon-filter' ?>" aria-hidden="true"></i>
                                    <span><?= !empty($item['title']) ? $item['title'] : '' // 筛选 ?></span>
                                </a>
                                <?php break; case 'export': // export ?>
                                <!--导出-->
                                <a href="#" type="button" class="btn btn-default" ng-click="triggerTableExportMethod()" title="导出">
                                    <i class="<?= !empty($item['icon']) ? $item['icon'] : 'glyphicon glyphicon-export' ?>" aria-hidden="true"></i>
                                    <span><?= !empty($item['title']) ? $item['title'] : '' // 导出 ?></span>
                                </a>
                                <?php break; default: // custom ?>
                                <!--自定义-->
                                <a href="#" type="button" class="btn btn-default" ng-click="triggerTableCustomMethod(<?= html_escape(Json::encode($item)) ?>)">
                                    <i class="<?= !empty($item['icon']) ? $item['icon'] : '' ?>" aria-hidden="true"></i>
                                    <span><?= !empty($item['title']) ? $item['title'] : '' ?></span>
                                </a>
                            <?php endswitch; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    <?php endif; ?>

    <!--工具栏结束-->
    <?php Table::endTableTool($widgets); ?>

    <!--表格-->
    <div class="panel-body overflow-x">
        <table class="table table-bordered table-hover">
            <thead>
            <tr class="bg-light">

                <!--隐藏多选框-->
                <?php if(!$hideCheckbox): ?>
                    <th style="<?= $checkboxOptions['style'] ?>"<?= $checkboxOptions['attribute'] ?>>
                        <label for="th_0"></label>
                        <input type="checkbox" id="th_0" class="tableCheckboxTool hidden">
                    </th>
                <?php endif; ?>

                <!--操作项渲染-->
                <?php if (!empty($rowActions)): ?>
                    <th style="width:50px;"><?= t('operation', 'app.admin') ?></th>
                <?php endif; ?>

                <!--渲染表头-->
                <?php foreach ($columns as $field => $item): ?>
                    <?php if (!ArrayHelper::isIn($field, $rowActionIds)): ?>
                        <th style="<?= $item['options']['style'] ?>"<?= $item['options']['attribute'] ?>>
                            <?= $item['title'] ?>
                        </th>
                    <?php endif; ?>
                <?php endforeach; ?>

            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="(key, value) in tableListData track by key" on-finish-render="ev-repeat-finished">

                <!--隐藏多选框-->
                <?php if(!$hideCheckbox): ?>
                    <td style="<?= $checkboxOptions['style'] ?>"<?= $checkboxOptions['attribute'] ?>>
                        <label for="td_{{key}}"></label>
                        <input type="checkbox" id="td_{{key}}" class="tableCheckbox hidden" value="{{value}}">
                    </td>
                <?php endif; ?>

                <!--操作项渲染-->
                <?php if (!empty($rowActions)): ?>
                    <?php if (count($rowActions) >= 2): ?>
                        <td class="row-handle" style="width:50px;">
                            <div class="dropdown">
                                <a href="javascript:void 0;" type="button" class="btn btn-sm btn-default dropdown-toggle"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= t('operation', 'app.admin') ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!--遍历设置操作项-->
                                    <?php foreach ($rowActions as $actionItem): ?>
                                        <?php switch ($actionItem['type']): case 'division': ?>
                                            <!--存在分割线,并渲染-->
                                            <li role="separator" class="divider"></li>
                                            <?php break; default: ?>
                                            <?php if (empty($actionItem['options']['actionId'])): ?>
                                                <!--操作ID为空,直接显示操作项-->
                                                <li>
                                                    <a href="javascript:void 0;" ng-click="triggerTableRowActions(value, <?= html_escape(Json::encode($actionItem)) ?>)">
                                                        <i class="actions-icon <?= $actionItem['options']['icon'] ?>"></i>
                                                        &nbsp;
                                                        <?= html_escape($actionItem['options']['title']) ?>
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <!--操作ID存在,判断是否允许显示-->
                                                <li ng-show="value['<?= $actionItem["options"]["actionId"] ?>']">
                                                    <a href="javascript:void 0;" ng-click="triggerTableRowActions(value, <?= html_escape(Json::encode($actionItem)) ?>)">
                                                        <i class="actions-icon <?= $actionItem['options']['icon'] ?>"></i>
                                                        &nbsp;
                                                        <?= html_escape($actionItem['options']['title']) ?>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endswitch; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </td>
                    <?php else: ?>
                        <td style="max-width: 200px">
                            <div class="btn-group">
                                <?php foreach ($rowActions as $actionItem): ?>
                                    <?php switch ($actionItem['type']): case 'division': ?>
                                        <?php break;default: ?>
                                        <!--操作项;根据操作ID判断是否显示该操作项-->
                                        <?php if (empty($actionItem['options']['actionId'])): ?>
                                            <!--操作ID为空,直接显示操作项-->
                                            <a href="javascript:void 0;" type="button" class="btn btn-sm btn-default" ng-click="triggerTableRowActions(value, <?= html_escape(Json::encode($actionItem)) ?>)">
                                                <i class="actions-icon <?= $actionItem['options']['icon'] ?>"></i>
                                                <?= html_escape($actionItem['options']['title']) ?>
                                            </a>
                                        <?php else: ?>
                                            <!--操作ID存在,判断是否允许显示-->
                                            <a ng-show="value['<?= $actionItem["options"]["actionId"] ?>']" href="javascript:void 0;" type="button" class="btn btn-sm btn-default" ng-click="triggerTableRowActions(value, <?= html_escape(Json::encode($actionItem)) ?>)">
                                                <i class="actions-icon <?= $actionItem['options']['icon'] ?>"></i>
                                                <?= html_escape($actionItem['options']['title']) ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endswitch; ?>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    <?php endif; ?>
                <?php endif; ?>

                <!--渲染列表-->
                <?php foreach ($columns as $field => $item): ?>
                    <?php if (!ArrayHelper::isIn($field, $rowActionIds)): ?>
                        <td style="<?= $item['options']['style'] ?>"<?= $item['options']['attribute'] ?>>
                            <span ng-bind-html="value['<?= $field ?>'] | toHtml"></span>
                        </td>
                    <?php endif; ?>
                <?php endforeach; ?>

            </tr>
            </tbody>
        </table>
        <!--空-->
        <div class="panel-body text-center" ng-if="isEmptyOfTable">
            <img style="margin-top:48px;font-size:0;" src="<?= Yii::getAlias('@web/media/image/empty.png') ?>" alt>
        </div>
    </div>

    <!--分页开始-->
    <?php Table::beginTablePage($widgets); ?>

    <!--分页-->
    <div class="panel-body border-top" ng-show="tablePaging" angular-ajax-page page-model="tablePaging"></div>

    <!--分页结束-->
    <?php Table::endTablePage($widgets); ?>

    <!--筛选表单-->
    <?php if (!empty($filterColumns) && is_array($filterColumns)): ?>
        <div class="panel-body px-24 pt-16" style="display:none;" id="_EasyApp_tableFilterForm">
            <form>
                <?php foreach ($filterColumns as $field => $options): ?>
                    <?php switch ($options['control']): case ToolbarFilterOptions::CONTROL_TEXT: // text ?>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="addon-fix"><?= $options['label'] ?></span>
                                </div>
                                <input type="text"<?= $options['attribute'] ?> style="<?= $options['style'] ?>" ng-model="tableFilterData['<?= $field ?>']" class="form-control" placeholder="<?= $options['placeholder'] ?>">
                            </div>
                        </div>
                        <?php break; case ToolbarFilterOptions::CONTROL_SELECT: // select ?>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="addon-fix"><?= $options['label'] ?></span>
                                </div>
                                <select id="ymTableFilter_<?= $field ?>"<?= $options['attribute'] ?> style="<?= $options['style'] ?>" ui-select2="{width:'100%'}" ng-model="tableFilterData['<?= $field ?>']" data-placeholder="<?= $options['placeholder'] ?>">
                                    <option value=""><?= $options['placeholder'] ?></option>
                                    <?php foreach ($options['options'] as $value => $label): ?>
                                        <option value="<?= $value ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php break; case ToolbarFilterOptions::CONTROL_NUMBER: // number ?>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="addon-fix"><?= $options['label'] ?></span>
                                </div>
                                <input type="number"<?= $options['attribute'] ?> style="<?= $options['style'] ?>" string-to-number ng-model="tableFilterData['<?= $field ?>']" class="form-control" placeholder="<?= $options['placeholder'] ?>">
                            </div>
                        </div>
                        <?php break; case ToolbarFilterOptions::CONTROL_DATETIME: // datetime ?>
                    <?php case ToolbarFilterOptions::CONTROL_DATE: // date ?>
                    <?php case ToolbarFilterOptions::CONTROL_YEAR: // year ?>
                    <?php case ToolbarFilterOptions::CONTROL_MONTH: // month ?>
                    <?php case ToolbarFilterOptions::CONTROL_TIME: // time ?>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="addon-fix"><?= $options['label'] ?></span>
                                </div>
                                <input type="text"<?= $options['attribute'] ?> style="<?= $options['style'] ?>" ng-model="tableFilterData['<?= $field ?>']" tag="<?= $options['control'] ?>" range="<?= $options['range'] ?>" id="ymTableFilter_<?= $field ?>" class="ymTablefilterDate form-control" placeholder="<?= $options['placeholder'] ?>" readonly>
                            </div>
                        </div>
                        <?php break; case ToolbarFilterOptions::CONTROL_CUSTOM: // custom ?>
                        <?= $options['widget']->render() ?>
                        <?php break; ?>
                    <?php endswitch; ?>
                <?php endforeach; ?>
            </form>
        </div>
    <?php endif; ?>

    <!--数据导出列表-->
    <?php if($exportFlag): ?>
        <div class="panel-body px-24 f-13" style="display:none;" id="YmExportForm">
            <ul class="list-group">
                <li class="list-group-item" ng-repeat="(key, value) in tableExportList track by key">
                    <div class="row text-dark">
                        <p class="col-sm-4 f-14 m-0 text-left">
                            <span><?= t('number', 'app.admin') ?></span><span ng-bind="value.page"></span><span><?= t('block', 'app.admin') ?></span>
                        </p>
                        <p class="col-sm-4 f-14 m-0 text-center">
                            <span><?= t('total', 'app.admin') ?></span><span ng-bind="value.rows"></span><span><?= t('rows', 'app.admin') ?></span>
                        </p>
                        <a class="col-sm-4 f-14 text-right" ng-href="{{value.url}}" ng-click="triggerTableFlagExport($event)">
                            <i class="glyphicon glyphicon-export"></i>
                            <span><?= t('export', 'app.admin') ?></span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    <?php endif; ?>
</div>
