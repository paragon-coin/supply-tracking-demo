<?php

namespace App\Components;

use App\Setting as SettingModel;
use Cache;

class Setting
{
    const CACHE_KEY = 'settings.all';

    /**
     * Get all the settings
     *
     * @return mixed
     */
    public static function getAllSettings()
    {
        return Cache::rememberForever(self::CACHE_KEY, function() {
            return SettingModel::all();
        });
    }

    /**
     * Flush the cache
     */
    public static function flushCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get a setting value
     *
     * @param $key
     * @param null $default
     * @return bool|\Illuminate\Config\Repository|int|mixed
     */
    public static function get($key, $default = null)
    {
        if (self::has($key)) {
            $setting = self::getAllSettings()
                ->where('key', $key)
                ->first();
            return self::castValue($setting->value, $setting->type);
        }

        return self::getDefaultValue($key, $default);
    }

    /**
     * Set a value for setting
     *
     * @param $key
     * @param $value
     * @param string $type
     * @return bool
     */
    public static function set($key, $value, $type = 'string')
    {
        if ($setting = self::getAllSettings()->where('key', $key)->first()) {
            self::flushCache();
            return $setting->update([
                'key' => $key,
                'value' => $value,
                'type' => $type
            ]) ? $value : false;
        }

        if ($setting = SettingModel::create([
            'key' => $key,
            'value' => $value,
            'type' => $type
        ])) {
            self::flushCache();
            return $setting ? $value : false;
        }
    }

    /**
     * Remove a setting
     *
     * @param $key
     * @return bool|null
     */
    public static function remove($key)
    {
        if (self::has($key)) {
            try {
                self::flushCache();
                return SettingModel::where('key', $key)->delete();
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    public static function has($key)
    {
        return (boolean) self::getAllSettings()
            ->whereStrict('key', $key)
            ->count();
    }

    /**
     * Cast value into respective type
     *
     * @param $value
     * @param $castTo
     * @return bool|int
     */
    private static function castValue($value, $castTo)
    {
        switch ($castTo) {
            case 'int':
            case 'integer':
                return intval($value);
                break;

            case 'bool':
            case 'bollean':
                return boolval($value);

            default:
                return $value;
        }
    }

    /**
     * Get default value form a setting
     *
     * @param $key
     * @param $default
     * @return \Illuminate\Config\Repository|mixed
     */
    private static function getDefaultValue($key, $default)
    {
        return is_null($default) ? self::getDefaultValueFromConfig($key) : $default;
    }

    /**
     * Get default value from config if no value passed
     *
     * @param $key
     * @return \Illuminate\Config\Repository|mixed
     */
    private static function getDefaultValueFromConfig($key)
    {
        return config($key);
    }
}