<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Common;

use Phalcon\Text;

final class StringUtils
{
    public static function camelize($str)
    {
        // TODO impl StringUtils::camelize
        return Text::camelize($str);
    }
}