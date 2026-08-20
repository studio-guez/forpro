<?php

use Kirby\Toolkit\A;

// Overrides the kirby-seo plugin's default snippet (site/snippets always win
// over plugin-registered ones): same output as upstream, except the
// auto-appended "Sitemap:" line is only shown when indexing is allowed —
// sitemap.xml itself stays reachable either way (see config.php).

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
    // output default
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
