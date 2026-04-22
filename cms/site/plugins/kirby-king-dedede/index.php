<?php

Kirby::plugin('medium-sans/king-dedede', [
  'sections' => [
    'google-preview' => require_once __DIR__ . '/sections/google-preview.php',
    'facebook-preview' => require_once __DIR__ . '/sections/facebook-preview.php',
    'twitter-preview' => require_once __DIR__ . '/sections/twitter-preview.php'
  ],
  'blueprints' => [
    'tabs/seo-site' => __DIR__ . '/blueprints/tabs/seo-page.yml',
    'tabs/seo-page' => __DIR__ . '/blueprints/tabs/seo-site.yml',

    'fields/global-meta' => __DIR__ . '/blueprints/fields/global-meta.yml',
    'fields/global-open-graph' => __DIR__ . '/blueprints/fields/global-open-graph.yml',
    'fields/global-twitter' => __DIR__ . '/blueprints/fields/global-twitter.yml',
    'fields/global-robots' => __DIR__ . '/blueprints/fields/global-robots.yml',
    'fields/basic-meta' => __DIR__ . '/blueprints/fields/basic-meta.yml',
    'fields/open-graph' => __DIR__ . '/blueprints/fields/open-graph.yml',
    'fields/twitter' => __DIR__ . '/blueprints/fields/twitter.yml',
    'fields/robots' => __DIR__ . '/blueprints/fields/robots.yml',

    'files/seo-image' => __DIR__ . '/blueprints/files/seo-image.yml',
  ],
  'snippets' => [
    'meta-information' => __DIR__ . '/snippets/meta-information.php',
    'robots' => __DIR__ . '/snippets/robots.php'
  ],
  'options' => [
    'canonicalURLIncludesWWW' => false,
    'defaultTemplate' => '{{ title }} - {{ site.title }}',
    'defaultTwitter' => '{{ title }} - {{ site.title }}',
    'defaultOpenGraph' => '{{ title }}'
  ],
  'pageMethods' => [
    'metaTemplateComputed' => function () {
      return $this->metaTemplate()->or(
        $this->parent() ?
          ($this->parent()->inheritMetaTemplate()->toBool() ?
            $this->parent()->metaTemplateComputed() :
            $this->site()->metaTemplate()->or(Config::get('mediumsans.king-dedede.defaultTemplate')))
          : $this->site()->metaTemplate()->or(Config::get('mediumsans.king-dedede.defaultTemplate'))
      );
    },
    'metaTitleComputed' => function () {
      return $this->toSafeString(
        $this->metaTemplateComputed(),
        ['title' => $this->metaTitle()->or($this->title())]
      );
    },
    'twitterTemplateComputed' => function () {
      return $this->twitterTemplate()->or(
        $this->parent() ?
          ($this->parent()->inheritTwitterTemplate()->toBool() ?
            $this->parent()->twitterTemplateComputed() :
            $this->site()->twitterTemplate()->or(Config::get('mediumsans.king-dedede.defaultTwitter')))
          : $this->site()->twitterTemplate()->or(Config::get('mediumsans.king-dedede.defaultTwitter'))
      );
    },
    'twitterTitleComputed' => function () {
      return $this->toSafeString(
        $this->twitterTemplateComputed(),
        ['title' => $this->twitterTitle()->or($this->metaTitle()->or($this->title()))]
      );
    },
    'ogTemplateComputed' => function () {
      return $this->ogTemplate()->or(
        $this->parent() ?
          ($this->parent()->inheritOgTemplate()->toBool() ?
            $this->parent()->ogTemplateComputed() :
            $this->site()->ogTemplate()->or(Config::get('mediumsans.king-dedede.defaultOpenGraph')))
          : $this->site()->ogTemplate()->or(Config::get('mediumsans.king-dedede.defaultOpenGraph'))
      );
    },
    'ogTitleComputed' => function () {
      return $this->toSafeString(
        $this->ogTemplateComputed(),
        ['title' => $this->ogTitle()->or($this->metaTitle()->or($this->title()))]
      );
    },
    'canonicalUrl' => function () {
      if (option('mediumsans.king-dedede.canonicalURLIncludesWWW') === false) {
        return preg_replace(array('/http:/', '/www\./'), array('https:', ''), $this->url());
      } else {
        return preg_replace('/http(s)?:\/\/(www.)?/', 'https://www.', $this->url());
      }
    }
  ]
]);
