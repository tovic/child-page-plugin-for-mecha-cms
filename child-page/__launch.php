<?php

// Re-define page URL and page title in the backend view of static page list
if(Route::is($config->manager->slug . '/page') || Route::is($config->manager->slug . '/page/(:num)')) {
    Filter::add('shield:lot', function($data) use($config) {
        if(isset($data['pages']) && $data['pages'] !== false) {
            foreach($data['pages'] as &$page) {
                if(isset($page->fields->parent_page_slug) && trim($page->fields->parent_page_slug) !== "") {
                    $page->url = File::D($page->url) . '/' . $page->fields->parent_page_slug . '/' . File::B($page->url);
                    if($parent = Get::pageAnchor($page->fields->parent_page_slug)) {
                        $page->title = $parent->title . $config->title_separator . $page->title;
                    }
                }
            }
            unset($page);
        }
        return $data;
    });
}

// Re-define page URL on the notification message that appears on update success
if($parent = Request::post('fields.parent_page_slug.value')) {
    Filter::add('page:url', function($url) use($parent) {
        $s = explode('/', $url);
        $ss = array_pop($s);
        return implode('/', $s) . '/' . $parent . '/' . $ss;
    });
}