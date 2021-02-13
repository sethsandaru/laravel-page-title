<?php


namespace App\Libraries;

/**
 * Class PageTitle
 * @desc We will setup the <title>{PageTitle}</title> for our Laravel application
 */
class PageTitle
{
    /**
     * Our main pageTitle
     * @var string $pageTitle
     */
    protected static $pageTitle = "";

    public static function setPageTitle(string $title) {
        static::$pageTitle = $title;
    }

    /**
     * Get the postfix of the Page Title
     * @example <your title> - <post fix name here>
     * @example News - Google
     * @return string
     */
    protected static function getPostfixPageTitle() : string {
        // could be from config or env or DB, your choice
        return __('Super Application');
    }

    /**
     * Get the final page title for View
     * @return string
     */
    public static function getTitle() : string {
        if (empty(static::$pageTitle)) {
            // empty page title => only return the postfix
            return static::getPostfixPageTitle();
        }

        // merge the pageTitle with the postFix, together with a delimiter in the middle.
        return static::$pageTitle . " - " . static::getPostfixPageTitle();
    }
}