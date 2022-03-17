<?php

namespace Helori\Aws;

use Symfony\Component\Console\Output\ConsoleOutput;


trait WritesToConsole
{
    public function line($string, $style = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;
        $output = new ConsoleOutput();
        $output->writeln($styled);
    }

    public function warn($string)
    {
        $this->line($string, 'warning');
    }

    public function info($string)
    {
        $this->line($string, 'info');
    }

    public function error($string)
    {
        $this->line($string, 'error');
    }

    public function comment($string)
    {
        $this->line($string, 'comment');
    }
}
