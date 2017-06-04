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
     * @inheritdoc
     */
    public function getIterator()
    {
        $communities = $this->_get('//curseforge.com')
            ->filter('.author-our-communties ul > li > a')
            ->each(
                function(Crawler $node) {
                    return $this->_community($node);
                }
            );

        return new \ArrayObject($communities);
    }

    /**
     * @param Crawler $node
     *
     * @return Model\Community
     */
    private function _community(Crawler $node)
    {
        $attributes = [
            'id' => $node->attr('href'),
            'logo' => $node->filter('.community-logo')->image()->getUri(),
            'image' => $node->filter('.community-bg')->image()->getUri(),
            'name' => function (Model\Community $community) {
                return trim(
                    str_ireplace(
                        'CurseForge',
                        null,
                        $this->_get($community->id)->filter('title')->text()
                    )
                );
            },
        ];

        return new Model\Community($attributes);
    }

    /**
     * @param $uri
     * @param array $params
     * @return Crawler
     */
    private function _get($uri, array $params = [])
    {
        $client = new Client();
        return $client->request('GET', $uri, $params);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return iterator_count($this->getIterator());
    }
}