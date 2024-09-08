<?php

use Carbon\Carbon;

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

if (! function_exists('timeAgo')) {
    /**
     * @param string $timestamp
     * @return string
     */
    function timeAgo(string $timestamp): string
    {
        $ago = (int)Carbon::parse($timestamp)->diffInMinutes(now());
        $string = 'minutes ago';
        if ($ago > 59) {
            $ago = (int)Carbon::parse($timestamp)->diffInHours(now());
            $string = 'hours ago';
            if ($ago > 23) {
                $ago = (int)Carbon::parse($timestamp)->diffInDays(now());
                $string = $ago == 1 ? 'day ago' : 'days ago';
                if ($ago > 13) {
                    $ago = (int)Carbon::parse($timestamp)->diffInWeeks(now());
                    $string = 'weeks ago';
                    if ($ago > 4) {
                        $ago = (int)Carbon::parse($timestamp)->diffInMonths(now());
                        $string = $ago == 1 ? 'month ago' : 'months ago';
                        if ($ago > 11) {
                            $ago = (int)Carbon::parse($timestamp)->diffInYears(now());
                            $string = $ago == 1 ? 'year ago' : 'years ago';
                        }
                    }
                }
            }
        }

        return __($ago . ' ' . $string);
    }
}

if (! function_exists('countString')) {
    /**
     * @param string $count
     * @return string
     */
    function countString(string $count): string
    {
        $string = $count;

        if ($count > 1000) {
            $string = number_format($count / 1000, 1, '.', ',') . 'K';
            if ($count > 100000) {
                $string = number_format($count / 1000, 0, '.', ',') . 'K';
            }
            if ($count > 1000000) {
                $string = number_format($count / 1000000, 1, '.', ',') . 'M';
            }
        }

        return $string;
    }
}

if (! function_exists('textToHtml')) {
    /**
     * @param string $text
     * @return string
     */
    function textToHtml(string $text): string
    {
        $text = str_replace("\n", ' <br> ', $text);

        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $text, $matches);

        $urls = [];
        foreach ($matches[0] as $key => $match) {
            $text = str_replace($match, '<a href="'.$match.'" target="_blank" title="'.__('open').'">'.$match.'</a>', $text);
            $urls[] = $match;
        }

        return $text;
    }
}