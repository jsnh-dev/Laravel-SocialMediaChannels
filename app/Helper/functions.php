<?php

if (! function_exists('getShareElements')) {
    /**
     * @param string $text
     * @param string $url
     * @return stdClass[]
     */
    function getShareElements($text = '', $url = ''): array
    {
        $linkedin = new \stdClass();
        $linkedin->icon = 'fa-linkedin';
        $linkedin->text = 'Share on LinkedIn';
        $linkedin->url = 'https://www.linkedin.com/feed/?' .
            'linkOrigin=LI_BADGE&' .
            'shareActive=true&' .
            'text=' . $text . '&' .
            'shareUrl=' . $url . '';

        $x = new \stdClass();
        $x->icon = 'fa-x-twitter text-dark';
        $x->text = 'Share on X';
        $x->url = 'https://x.com/intent/post?' .
            'text=' . $text . '&' .
            'url=' . $url . '';

        return [
            'linkedin' => $linkedin,
            'x' => $x
        ];
    }
}