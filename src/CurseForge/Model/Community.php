<?php

namespace kaluzki\CurseForge\Model;

/**
 * @property string $id
 * @property string $name
 * @property Project[] $projects
 */
class Community extends AbstractEntity
{
    /**
     * @var array
     */
    protected $properties = [
        'id' => null,
        'name' => 0,
        'projects' => [],
    ];
}