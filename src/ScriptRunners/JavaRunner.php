<?php

namespace ProcessMaker\ScriptRunners;

class JavaRunner extends Base
{
    /**
     * Configure docker with java executor
     *
     * @param string $code
     * @param array $dockerConfig
     *
     * @return array
     */
    public function config($code, array $dockerConfig)
    {
        $dockerConfig['image'] = config('script-runners.java.image');
        $dockerConfig['command'] = '/bin/sh /opt/executor/run.sh';
        $dockerConfig['inputs']['/opt/executor/Script.java'] = $code;

        return $dockerConfig;
    }
}
