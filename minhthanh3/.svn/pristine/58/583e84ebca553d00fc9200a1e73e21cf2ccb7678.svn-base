<?php

namespace Botble\Widget;

class WidgetId
{
    /**
     * Static incrementing widget id.
     *
     * @var int
     */
    protected static $id = 0;

    /**
     * Getter for widget id.
     *
     * @return int
     * @author Sang
     */
    public static function get()
    {
        return self::$id;
    }

    /**
     * Setter for widget id.
     *
     * @param int $id
     * @author Sang
     */
    public static function set($id)
    {
        self::$id = $id;
    }

    /**
     * Increment widget id by one.
     * @author Sang
     */
    public static function increment()
    {
        self::$id++;
    }

    /**
     * Resets widget id to zero.
     * @author Sang
     */
    public static function reset()
    {
        self::$id = 0;
    }
}
