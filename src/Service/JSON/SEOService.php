<?php

namespace App\Service\JSON;

use Fagathe\Framework\Database\Model\JSON\DataService;
use Fagathe\Framework\Env\Env;
use Symfony\Component\HttpFoundation\Request;

final class SEOService
{

    private const DEFAULT_TAGS = 'defaultTags';
    private const IMAGES_URL = '/images/projets/mon-site.png';
    private DataService $dataService;

    public function __construct()
    {
        $this->dataService = new DataService('seo');
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * @param string $page
     * 
     * @return array|null
     */
    public function getSEO(string $page): ?array
    {
        $jsonData = $this->dataService->findAll();
        $data = array_pop($jsonData);
        $seo = $data[$page] ?? [];

        $defaultTags = $data[self::DEFAULT_TAGS] ?? [];
        if (is_array($seo) && array_key_exists(self::DEFAULT_TAGS, $seo)) {
            $seo['tags'] = array_unique(array_merge($defaultTags, $seo['tags']));
        } else {
            $seo['tags'] = [...$defaultTags, ...$seo['tags']];
        }

        foreach ($seo['tags'] as $value) {
            if (array_key_exists('content', $value) && $value['content'] === '{{ APP_NAME }}') {
                $value['content'] = Env::getEnv('APP_NAME');
            }
            if (array_key_exists('name', $value) && $value['name'] === 'og:url') {
                $value['content'] = Env::getEnv('APP_NAME');
            }
            $newTags[] = $value;
        }
        
        $seo['tags'] = [
            ...$newTags,
            $this->generateOpenGraph('og:image', $this->getRequest()->getSchemeAndHttpHost() . self::IMAGES_URL),
            $this->generateOpenGraph('og:url', $this->getRequest()->getSchemeAndHttpHost() . $this->getRequest()->getPathInfo()),
            $this->generateOpenGraph('og:site_name', Env::getEnv('APP_NAME')),
        ];

        return $seo;
    }

    /**
     * @param string $name
     * @param string $content
     * @param string $attribute
     * 
     * @return array
     */
    private function generateOpenGraph(string $name, string $content, string $attribute = 'property'): array
    {
        return compact('name', 'attribute', 'content');
    }
}
