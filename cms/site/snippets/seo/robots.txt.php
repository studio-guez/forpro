<?php

use Kirby\Toolkit\A;

// Overrides kirby-seo's snippet (site/snippets win over plugin ones): the Sitemap line is only emitted when indexing is allowed.

$index = option('tobimori.seo.robots.index');
if (is_callable($index)) {
    $index = $index();
}

if ($content = option('tobimori.seo.robots.content')) {
    if (is_callable($content)) {
        $content = $content();
    }

    if (is_array($content)) {
        $str = [];

        foreach ($content as $ua => $data) {
            $str[] = 'User-agent: ' . $ua;
            foreach ($data as $type => $values) {
                foreach ($values as $value) {
                    $str[] = $type . ': ' . $value;
                }
            }
        }

        $content = A::join($str, PHP_EOL);
    }

    echo $content;
} else {
    echo "User-agent: *\n";

    if ($index) {
        echo 'Allow: /';
        echo "\nDisallow: /panel";
    } else {
        echo 'Disallow: /';
    }
}

if ($index) {
    echo "\n\nSitemap: " . site()->canonicalFor('/sitemap.xml');
}
