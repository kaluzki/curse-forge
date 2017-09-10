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
                function (Crawler $node) {
                    return new Model\Community($this->_community($node));
                }
            );

        return new \ArrayObject($communities);
    }

    /**
     * @param Crawler $node
     *
     * @return array
     */
    private function _community(Crawler $node)
    {
        $page = $this->_get($node->attr('href'));

        return [
            'id' => $page->getUri(),
            'name' => trim(
                str_ireplace(
                    'CurseForge',
                    null,
                    $page->filter('title')->text()
                )
            ),
            'icon' => $node->filter('.community-logo')->image()->getUri(),
            'image' => $node->filter('.community-bg')->image()->getUri(),
            'categories' => function (Model\Community $community) {
                return $this->_categories($community);
            },
            'projects' => [],
        ];
    }

    /**
     * @param Model\Community $community
     * @return array
     */
    private function _categories(Model\Community $community)
    {
        return array_map(
            function ($uri) {
                $page = $this->_get($uri, ['filter-sort' => 'updated', 'page' => 9999]);

                return new Model\Category($this->_category($page));
            },
            $this->_categoryUris($community)
        );
    }

    /**
     * @param Model\Community $community
     * @return array
     */
    private function _categoryUris(Model\Community $community)
    {
        $page = $this->_get("{$community->id}/projects");

        if (end(explode('/', $page->getUri())) !== 'projects') {
            return [$page->getUri()];
        }

        return $page->filter('.project-category > a')->each(
            function (Crawler $node) {
                return $node->link()->getUri();
            }
        );
    }

    /**
     * @param Crawler $page
     * @return array
     */
    private function _category(Crawler $page)
    {
        $title = $page->filter('h4.project-listing-header-title');

        $itemCount = $page->filter('li.project-list-item')->count();
        $pageCount = $page->filter('li.b-pagination-item > span.active');
        $pageCount = $pageCount->count() ? (int)$pageCount->text() - 1 : 0;

        return [
            'id' => reset(explode('?', $page->getUri())),
            'name' => $title->filter('a')->text(),
            'icon' => $title->filter('img')->image()->getUri(),
            'categories' => $page->filter('ul.categories-tier li.level-categories-nav > a')
                ->each(
                    function (Crawler $node) {
                        return new Model\Category($this->_subCategory($node));
                    }
                ),
            'projects' => $pageCount * 20 + $itemCount,
        ];
    }

    /**
     * @param Crawler $node
     * @return array
     */
    private function _subCategory(Crawler $node)
    {
        return [
            'id' => $node->link()->getUri(),
            'name' => $node->filter('span')->text(),
            'icon' => $node->filter('img')->image()->getUri(),
        ];
    }

    /**
     * @param string $uri
     * @param mixed $params
     * @return Crawler
     */
    private function _get($uri, array $params = [])
    {
        $client = new Client();

        $params = $params ? '?' . http_build_query($params) : '';

        return $client->request('GET', $uri.$params);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return iterator_count($this->getIterator());
    }
}