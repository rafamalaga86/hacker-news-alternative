<?php

namespace HackerNewsGTD;

/**
 * Helper class for the controllers
 */
class ControllerHelper
{
    public static function calculateOffset($page, $rootsToFetch)
    {
        $page = is_numeric($page) && (int) $page > 0 ? (int) $page : 1;
        return $rootsToFetch * ($page - 1);
    }
}
