<?php
/**
 * Created by LINKeRxUA <lnker.ua@gmail.com>
 * linkedIn:    https://www.linkedin.com/in/bogdan-kotelva/
 * Date: 02.11.17
 * Time: 15:45
 */

namespace App\Theme;


use Illuminate\Support\Facades\Route;

class Sidebar
{

    private static $_elements = [];

    private static $_instance = null;

    private static $_active = null;

    private function __construct()
    {

        self::$_elements = func_get_args();

    }

    /**
     * @return Sidebar
     */
    public static function instance(){

        if(is_null(self::$_instance))
            self::$_instance = new self();

        self::$_instance->addItem('Farmers', 'account_circle', 'farmer.index');
        self::$_instance->addItem('Laboratories', 'colorize', 'lab.index');
        self::$_instance->addItem('Transactions', 'credit_card', 'transaction.index');
        self::$_instance->addItem('Harvests list', 'subtitles', 'harvest.list');
        self::$_instance->addItem('Expertise list', 'library_books', 'expertise.list');

        return self::$_instance;

    }

    public static function makeItem($name, $img, $route, $params = []){

        $routeRGXP = explode('.', $route,2);
        $routeRGXP = preg_quote($routeRGXP[0]);

        $active = preg_match("/^{$routeRGXP}\..*|{$routeRGXP}/", Route::currentRouteName());

        return compact('name', 'img', 'active', 'route', 'params');

    }

    public function getItems(){

        return self::$_elements;

    }

    public function active($key = null, $default = null){

        return (!empty(self::$_active))
            ? is_null($key)
                ? self::$_active
                : array_get(self::$_active, $key, $default)
            : $default;

    }


    public function addItem($name, $img, $route, $params = []){

        self::$_elements[] = self::makeItem($name, $img, $route, $params);

        end(self::$_elements);
        $key = key(self::$_elements);
        reset(self::$_elements);

        if(self::$_elements[$key]['active']){

            self::$_active =& self::$_elements[$key];

        }

    }


}