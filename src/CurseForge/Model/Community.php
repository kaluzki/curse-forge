<?php

namespace kaluzki\CurseForge\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $icon
 * @property string $image
 * @property Category[] $categories
 * @property Project[] $projects
 */
class Community extends AbstractEntity
{
    /**
     * @var array
     */
    protected $properties = [
        'id' => null,
        'name' => null,
        'icon' => null,
        'image' => null,
        'categories' => [],
        'projects' => [],
    ];
}