<?php

namespace App\Includes;

use Scabbia\Extensions\Helpers\Date;
use Scabbia\Extensions\I18n\I18n;
use Scabbia\Extensions\Http\Http;
use Scabbia\Extensions\LarouxJs\LarouxJs;
use Scabbia\Request;

/**
 * @ignore
 */
class ViewHelpers
{
    /**
     * @ignore
     */
    public static function printBool($uCondition)
    {
        if ($uCondition === true || intval($uCondition) === 1) {
            return I18n::_('Yes');
        }

        return I18n::_('No');
    }

    /**
     * @ignore
     */
    public static function printDate($uDate, $uIncludeHour = false)
    {
        if ($uDate === '' || $uDate === null) {
            return I18n::_('(Unspecified)');
        }

        return Date::convert($uDate, 'Y-m-d H:i:s', $uIncludeHour ? 'd/m/Y H:i' : 'd/m/Y');
    }

    /**
     * @ignore
     */
    public static function printProject($uProject)
    {
        return '<a href="' . Http::url('projects/show/' . $uProject['id']) . '">' . $uProject['title'] . '</a>';
    }

    /**
     * @ignore
     */
    public static function printProjectId($uProjectId, $uProjectName, $uProjectTitle)
    {
        return '<a href="' . Http::url('projects/show/' . $uProjectId) . '">' . $uProjectTitle . '</a>';
    }

    /**
     * @ignore
     */
    public static function printUser($uUser)
    {
        if ($uUser === null) {
            return I18n::_('(Unassigned)');
        }

        return '<a href="#">' . $uUser['name'] . '</a>'; // ' . Http::url('users/show/' . $uUser['id']) . '
    }

    /**
     * @ignore
     */
    public static function printUserId($uUserId, $uUserName)
    {
        if ($uUserId === 0) {
            return I18n::_('(Unassigned)');
        }

        return '<a href="#">' . $uUserName . '</a>'; // ' . Http::url('users/show/' . $uUserId) . '
    }

    /**
     * @ignore
     */
    public static function printStatus($uConstants, $uStatusId)
    {
        if (isset($uConstants['open_task_type'][(int)$uStatusId])) {
            echo $uConstants['open_task_type'][(int)$uStatusId]['name'];
        } elseif (isset($uConstants['closed_task_type'][(int)$uStatusId])) {
            echo $uConstants['closed_task_type'][(int)$uStatusId]['name'];
        } else {
            echo '- ' . I18n::_('Unspecified');
        }
    }

    /**
     * @ignore
     */
    public static function printConstant($uConstants, $uKey, $uValue)
    {
        $uIntValue = (int)$uValue;

        if ($uIntValue !== 0 && isset($uConstants[$uKey][$uIntValue])) {
            echo $uConstants[$uKey][$uIntValue]['name'];
        } else {
            echo '- ' . I18n::_('Unspecified');
        }
    }

    /**
     * @ignore
     */
    public static function printPage($uPage)
    {
        return '<a href="' . Http::url('page/' . $uPage['name']) . '">' . $uPage['title'] . '</a>';
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]

     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public static function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g' ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";

        return $url;
    }
}
