<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/23
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\helper;

use yii\base\InvalidArgumentException;

/**
 * 日期分割处理助手
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class DateSplitHelper
{
    /**
     * 开始日期
     * @var string|int
     * @since 1.0
     */
    protected $start;

    /**
     * 结束日期
     * @var string|int
     * @since 1.0
     */
    protected $end;

    /**
     * 当前时间戳
     * @var null|int
     * @since 1.0
     */
    protected $now;

    /**
     * 选项
     * @var array
     * @since 1.0
     */
    protected $options = [
        'reformat' => false,
        'timestamp' => false,
    ];

    /**
     * 创建实例
     * @param string $dateStr 日期字符串
     * - 2020-09-23 / 2020-12-01
     * @param string $splitFlag 分隔符
     * - /
     * @param null|int $now 当前时间戳
     * @return static
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function create($dateStr, $now = null, $splitFlag = '/')
    {
        $context = new static();
        @list($start, $end) = array_map('trim', explode($splitFlag, $dateStr));
        if (empty($start) || empty($end)) {
            throw new InvalidArgumentException('Invalid argument ' . $dateStr);
        }

        $context->start = $start;
        $context->end = $end;
        $context->now = $now ?: time();

        return $context;
    }

    /**
     * 重新格式化日期
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function reformat()
    {
        $this->options['reformat'] = true;
        return $this;
    }

    /**
     * 是否转为时间戳
     * @return $this
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function timestamp()
    {
        $this->options['timestamp'] = true;
        return $this;
    }

    /**
     * 输出数组
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function toArray()
    {
        return $this->resolveOptions();
    }

    /**
     * 解析选项
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function resolveOptions()
    {
        $start = $this->start;
        $end = $this->end;
        // reformat
        if ($this->options['reformat']) {
            $start = date('Y-m-d', strtotime($start, $this->now)) . ' 00:00:00';
            $end = date('Y-m-d', strtotime($end, $this->now)) . ' 23:59:59';
        }

        // timestamp
        if ($this->options['timestamp']) {
            $start = strtotime($start, $this->now);
            $end = strtotime($end, $this->now);
        }

        return [$start, $end];
    }
}