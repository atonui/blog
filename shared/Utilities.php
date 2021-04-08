<?php

class Utilities
{
    public function getPaging($page, $total_rows, $records_per_page, $page_url)
    {
        //paging array
        $paging_array = [];

        // button for first page
        $paging_array['first'] = $page > 1 ? "{$page_url}page=1" : '';

        // count all posts in the database to calculate total pages
        $total_pages = ceil($total_rows / $records_per_page);

        // range of links to show
        $range = 2;

        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range) + 1;

        $paging_array['pages'] = [];
        $page_count = 0;

        for ($x = $initial_num; $x < $condition_limit_num; ++$x) {
            // be sure $x is greater than 0 and less than or equal to $total pages
            if (($x > 0) && ($x <= $total_pages)) {
                $paging_array['pages'][$page_count]['page'] = $x;
                $paging_array['pages'][$page_count]['url'] = "{$page_url}page={$x}";
                $paging_array['pages'][$page_count]['current_page'] = $x == $page ? 'yes' : 'no';
                ++$page_count;
            }
        }
        //button for last page
        $paging_array['last'] = $page < $total_pages ? "{$page_url}page={$total_pages}" : '';

        return $paging_array;
    }
}
