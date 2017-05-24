<?php
/**
 * 模块定义
 */
return [
    /**
     * 用户（服务化）
     */
    'user'  => [
        'class' => 'service\modules\user\Module',
    ],
    /**
     * 管理后台配置模块
     */
    'config' => [
        'class' => 'admin\modules\config\Module',
    ],
    /**
     * 管理后台管理员模块
     */
    'admin' => [
        'class' => 'admin\modules\admin\Module',
    ],
];
