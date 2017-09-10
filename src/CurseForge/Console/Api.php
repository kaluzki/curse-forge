<?php

namespace kaluzki\CurseForge\Console;

use kaluzki\CurseForge\Model;
use Symfony\Component\Console;
use fn;

/**
 */
class Api extends Console\Application
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct('curse-forge api', '0.1');

        $this->_add('communities', 'get all curse communities', new Model\Provider\HttpClient);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function __invoke()
    {
        $args = func_get_args();
        array_unshift($args, $this->getName());
        return $this->run(new Console\Input\ArgvInput($args));
    }

    /**
     * @param string $name
     * @param string $description
     * @param iterable|callable $candidate
     */
    private function _add($name, $description, $candidate)
    {
        if ($command = $this->add(new Console\Command\Command($name))) {
            $command
                ->setDescription($description)
                ->setCode(function (
                    Console\Input\InputInterface $in,
                    Console\Output\OutputInterface $out
                ) use (&$candidate) {
                    $candidate = is_callable($candidate) ? $candidate($in, $out) : $candidate;
                    fn\map($candidate, function($line) use($out) {
                        $out->writeln((string)$line);
                    }, true);
                });
        }
    }
}