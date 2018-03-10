<?php

if (! function_exists('setting')) {
    function setting($key, $default = null) {
        if (is_array($key)) {
            \App\Components\Setting::set($key[0], $key[1]);
        }
        $value = \App\Components\Setting::get($key);
        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('bytes_convert')) {
    function bytes_convert($bytes)
    {

        $units = ['B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return number_format($bytes, 2).' '.$units[$i];

    }
}

if (!function_exists('breadcrumbs')) {
    function breadcrumbs($items)
    {
        $output = '';
        if ($items) {
            $output .= '<div class="breadcrumbsHolder">';
            $output .= '<ol class="breadcrumb">';
            $output .= '<li><a href="'.route('home').'">'.trans('Home')
                .'</a></li>';
            if (is_array($items)) {
                foreach ($items as $item) {
                    if (is_array($item)) {
                        $output .= '<li><a href="'.$item['url'].'">'
                            .$item['label'].'</a></li>';
                    } else {
                        $output .= '<li class="active">'.$item
                            .'</li>';
                    }
                }
            } else {
                $output .= '<li class="active">'.$items
                    .'</li>';
            }
            $output .= '</ol>';
            $output .= '</div>';
        }

        return $output ?: null;
    }
}

if (!function_exists('uniqueID_withMixing')) {
    function uniqueID_withMixing($length = 128, $chunkSize = false, array $mixing = null)
    {

        $uid = str_replace('.', '', uniqid('', true));
        $mixing = (empty($mixing)) ? hash('md5',uniqid('', true)) : json_encode($mixing);

        $suffix = "";
        while(strlen($suffix) + strlen($uid) < $length){

            $suffix .= hash('sha512', str_shuffle($mixing));

        }

        $uid = substr($uid . $suffix,0,$length);

        return ($chunkSize > 0)
            ? substr(chunk_split($uid, $chunkSize, '-'), 0, -1)
            : $uid;

    }
}

if (!function_exists('array_diff_assoc_recursive')) {
    function array_diff_assoc_recursive($array1, $array2)
    {
        $difference=array();
        foreach($array1 as $key => $value) {
            if( is_array($value) ) {
                if( !isset($array2[$key]) || !is_array($array2[$key]) ) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = array_diff_assoc_recursive($value, $array2[$key]);
                    if( !empty($new_diff) )
                        $difference[$key] = $new_diff;
                }
            } else if( !array_key_exists($key,$array2) || $array2[$key] != $value ) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }
}

if (! function_exists('compare_data')) {

    /**
     * Compares data between array, json string or \Illuminate\Database\Eloquent\Model
     *
     * @param $model1 \Illuminate\Database\Eloquent\Model|array|string
     * @param $model2 \Illuminate\Database\Eloquent\Model|array|string
     * @param bool $showDiff
     * @param bool $debug
     * @return array|bool
     */
    function compare_data($model1, $model2, $showDiff = false, $debug = false) {

        if ($model1 instanceof \Illuminate\Database\Eloquent\Model)
            $model1 = $model1->toArray();
        else if (is_string($model1))
            $model1 = json_decode($model1, true);

        if ($model2 instanceof \Illuminate\Database\Eloquent\Model)
            $model2 = $model2->toArray();
        else if (is_string($model2))
            $model2 = json_decode($model2, true);

        $data1 = is_array($model1) ? array_dot($model1) : '';
        $data2 = is_array($model2) ?  array_dot($model2) : '';

        if ($showDiff) {
            $diff = array_diff_assoc_recursive($data1, $data2);
            return $debug ? [$model1, $model2, $diff] : $diff;
        } else {

            if (count($data1) != count($data2))
                return false;

            return !array_diff_assoc_recursive($data1, $data2);
        }
    }
}

if (!function_exists('user_unique_id')) {
    function user_unique_id() {
        return \Illuminate\Support\Str::orderedUuid();
    }
}