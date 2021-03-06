<?php
/**
 * 所有运行脚本均需继承此基类
 */
namespace App\Script;

use App\Boot\Initializer;

abstract class ScriptBase
{
    protected $app;

    /**
     * ScriptBase constructor. 加载app实例，以便获取服务和配置
     */
    public function __construct()
    {
        $initializer = new Initializer();

        $app = $initializer->getApp();

        $initializer->prepareService($app);

        $initializer->prepareRoute($app);

        $this->app = $app;
    }

    /**
     * 主逻辑
     * @param array $params 命令行参数
     * @return mixed
     */
    abstract public function run($params);
}