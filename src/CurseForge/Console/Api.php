<?php

namespace kaluzki\CurseForge\Console;

use kaluzki\CurseForge\Model;
use Symfony\Component\Console;

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

        $this->_add('communities', 'get all curse communities', function() {
            return new Model\Provider\HttpClient();
        });
    }

    /**
     * @inheritdoc
     */
    public function __invoke($input = null)
    {
        if ($input !== null) {
            $input = (array)$input;
            array_unshift($input, $this->getName());
            $input = new Console\Input\ArgvInput($input);
        }
        return $this->run($input);
    }

    /**
     * @param string $name
     * @param string $description
     * @param callable $code
     */
    private function _add($name, $description, callable $code)
    {
        $this->add(new Console\Command\Command($name))
            ->setDescription($description)
            ->setCode(function(Console\Input\InputInterface $in, Console\Output\OutputInterface $out) use(&$code) {
                $lines = ($lines = call_user_func($code, $in, $out)) instanceof \Traversable ? $lines : (array) $lines;
                foreach ($lines as $line) {
                    $out->writeln((string) $line);
                }
            });
    }
}