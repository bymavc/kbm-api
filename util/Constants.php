<?php
/**
 * Created by PhpStorm.
 * User: mvillalobos
 * Date: 4/14/2018
 * Time: 10:56 PM
 */

defined ('SERVER_URL') or define('SERVER_URL', $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']);
defined ('APPLICATION_URL') or define('APPLICATION_URL', SERVER_URL . "/kbm-web/#!/");
defined ('API_URL') or define('API_URI', SERVER_URL . "/kbm-core/resource/");
defined ('FILE_DIRECTORY') or define ('FILE_DIRECTORY', $_SERVER['DOCUMENT_ROOT'] . "/kbm-files/");
defined ('PROFILE_PICTURE_DIRECTORY') or define ('PROFILE_PICTURE_DIRECTORY', FILE_DIRECTORY . "images/profile/");