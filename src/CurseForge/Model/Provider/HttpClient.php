<?php

namespace kaluzki\CurseForge\Model\Provider;

use kaluzki\CurseForge\Model;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;


/**
 */
class HttpClient implements ProviderInterface
{
    /**
     * @var array[]
     */
    private $_config = [
        'https://dev.bukkit.org' => ['bukkit-plugins'],
        'https://minecraft.curseforge.com' => [
            'modpacks',
            'cusomization',
            'addons',
            'mods',
            'texture-packs',
            'worlds',
        ],
        'https://www.feed-the-beast.com' => ['mod-packs'],
        'https://www.wowace.com' => ['addons'],
        'https://wow.curseforge.com' => ['addons'],
        'https://wildstar.curseforge.com' => ['ws-addons'],
        'https://kerbal.curseforge.com' => ['mods', 'shareables'],
        'https://worldoftanks.curseforge.com' => ['mods', 'skins'],
        'https://www.sc2mapster.com' => ['assets', 'maps'],
        'https://www.skyrimforge.com' => ['mods'],
        'https://teso.curseforge.com' => ['addons'],
        'https://rift.curseforge.com' => ['addons'],
        'https://rom.curseforge.com' => ['addons'],
        'https://tsw.curseforge.com' => ['mods'],
        'https://terraria.curseforge.com' => ['maps'],
    ];

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        $communities = $this->_config;
        array_walk($communities, function(&$community, $communityId) {
            $projects = array_map(function($projectId) {
                return new Model\Project([
                    'id' => $projectId,
                    'name' => function(Model\Project $project) {
                        return "name {$project->id}";
                    }
                ]);
            }, $community);
            $community = new Model\Community(['id' => $communityId, 'projects' => $projects]);
        });

        return new \ArrayObject($communities);
//        $client = new Client();
//        $nodes = $client->request('GET', 'https://mods.curse.com/mc-mods/minecraft?filter-project-game-version=&filter-project-sort=3');
//        $content = $nodes->filter('ul.group')->each(function(Crawler $node) {
//            return $node->text();
//        });
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return iterator_count($this->getIterator());
    }
}