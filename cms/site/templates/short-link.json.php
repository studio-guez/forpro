<?php

echo json_encode([
  'title' => $page->title()->value(),
  'url'   => $page->url_redirection()->value(),
]);
