<?php

namespace kaluzki\CurseForge\Model;

/**
 * @property string $id
 * @property string $name
 * @property string $icon
 * @property string[] $images
 * @property string[] $links
 * @property string[] $members
 * @property Category[] $categories
 * @property File[] $files
 */
class Project extends AbstractEntity
{
    /**
     * @var array
     */
    protected $properties = [
        'id' => null,
        'name' => null,
        'icon' => null,
        'images' => [],
        'links' => [],
        'members' => [],
        'categories' => [],
        'files' => [],
    ];
}