<?php

echo json_encode([
  'short_links' => array_values($page->children()->listed()->map(function ($item) {
    return [
      'title' => $item->title()->value(),
      'url' => $item->url(),
    ];
  })->data()),
]);
