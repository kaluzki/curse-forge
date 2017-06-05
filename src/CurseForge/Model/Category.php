<?php

namespace kaluzki\CurseForge\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $icon
 * @property Category[] $categories
 * @property Project[] $projects
 */
class Category extends AbstractEntity
{
    /**
     * @var array
     */
    protected $properties = [
        'id' => null,
        'name' => null,
        'icon' => null,
        'categories' => [],
        'projects' => [],
    ];
}