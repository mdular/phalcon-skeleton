<?php
namespace Cli\Tasks;

class VersionTask extends \Phalcon\Cli\Task
{
    public function mainAction()
    {
        $config = $this->config->application;

        echo $config['version'];
    }

}
