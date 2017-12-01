<?php

namespace App\Tasks\Kafka;

use App\Tasks\Task;
use Xin\Cli\Color;

class MainTask extends Task
{

    public function mainAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Kafka测试脚本') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run [action]', Color::FG_LIGHT_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  kafka:main              菜单', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::colorize('  kafka:test@publish      发布消息', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::colorize('  kafka:test@consume      消费消息', Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

}

