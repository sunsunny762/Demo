<?php
use Spatie\Image\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;

if (!function_exists('getPerPageOptions')) {
    /**
     * Get per page options
     *
     * @return array
     */

    function getPerPageOptions()
    {
        return 100;
    }
}

if (!function_exists('prepareInputRequestArray')) {
    /**
     * Prepare Input Request Array
     *
     * @param array $param
     * @return array
     */

    function prepareInputRequestArray($param = [])
    {
        $input_fields = ['per_page','search','search_referrer','status','order_by','order'];
        $input = Request::only($input_fields);


        if (!empty($param)) {
            foreach ($param as $name=>$value) {
                $input[$name] = $value;
            }
        }

        return $input;
    }
}

if (!function_exists('addOrderByCSSClass')) {
    /**
     * Add css class when order by applied on specific fiel
     *
     * @param string $field
     * @return string
     */
    function addOrderByCSSClass($field)
    {
        if ($field == Request::get('order_by')) {
            return Request::get('order') == 'asc' ? 'icon-keyboard_arrow_up' : 'icon-keyboard_arrow_down';
        }
        return 'icon-unfold_more';
    }
}

if (!function_exists('getImageUrl')) {
    /**
     * Get image url by specific dimention
     *
     * @param string $image
     * @param string $size
     * @return string
     */
    function getImageUrl($image, $folder = 'uploads', $size = '')
    {
        if (!empty($size) && file_exists(config('filesystems.disks.local.root').'/'.$folder.'/'.$size.'-'.$image)) {
            return config('filesystems.disks.public.url').'/'.$folder.'/'.$size.'-'.$image;
        } elseif (!empty($size) && file_exists(config('filesystems.disks.local.root').'/'.$folder.'/'.$image) && !empty(config('image-sizes')[$folder][$size])) {
            $resize_info = config('image-sizes')[$folder][$size];
            $reized_image = Image::load(config('filesystems.disks.local.root').'/'.$folder.'/'.$image);
            switch ($resize_info['method']) {
                case 'crop': {
                    $reized_image->crop($resize_info['type'], $resize_info['width'], $resize_info['height']);
                    break;
                };
                case 'fit': {
                    $reized_image->fit($resize_info['type'], $resize_info['width'], $resize_info['height']);
                    break;
                }
            }
            $reized_image->save(config('filesystems.disks.local.root').'/'.$folder.'/'.$size.'-'.$image);
            return config('filesystems.disks.public.url').'/'.$folder.'/'.$size.'-'.$image;
        } elseif (file_exists(config('filesystems.disks.local.root').'/'.$folder.'/'.$image)) {
            return config('filesystems.disks.public.url').'/'.$folder.'/'.$image;
        }

        return '';
    }
}


if (!function_exists('isAdminActiveMenu')) {
    /**
     * Check is admin menu active
     *
     * @param string $route_name
     * @param array $param
     * @return boolean
     */
    function isAdminActiveMenu($route_name, $param = [])
    {
        $current_route = Route::currentRouteName();
        if (!empty($current_route)) {
            if (in_array($current_route, array('comment.type.filter','comment.index','comment.edit')) && $route_name == 'blog.index') {
                return true;
            } else {
                return request()->is(trim(route($route_name, $param, false), '/')) || request()->is(trim(route($route_name, $param, false), '/').'/*');
            }
        }
    }
}

if (!function_exists('getImageRecommendedSize')) {
    /**
     * Get image recommended size
     *
     * @param string $folder
     * @return string
     */
    function getImageRecommendedSize($folder)
    {
        $recommended_size = '';
        if (is_array(config('image-sizes')[$folder])) {
            foreach (config('image-sizes')[$folder] as $k=>$v) {
                if (!empty($v['size']) && !empty($v['is_recommended'])) {
                    $recommended_size .= "Max size ". $v['size'] ."(MB) and ";
                }
                if (!empty($v['is_recommended'])) {
                    $recommended_size .= 'Recommended Size: '.(isset($v['recommended_custom']) ? $v['recommended_custom'] : $v['width'].'PX x '.$v['height'].'PX').' (Allowed only jpg, jpeg, gif and png images)';
                }
            }
        }
        return $recommended_size;
    }
}

if (!function_exists('getImageUploadSizeInMB')) {
    /**
     * Get image upload size in MB
     *
     * @param string $module
     * @return int
     */
    function getImageUploadSizeInMB($module)
    {
        $recommended_size = '';
        if (is_array(config('image-sizes')[$module])) {
            foreach (config('image-sizes')[$module] as $k=>$v) {
                if (!empty($v['size']) && !empty($v['is_recommended'])) {
                    $recommended_size = $v['size'];
                }
            }
        }
        return $recommended_size;
    }
}

if (!function_exists('getSlugText')) {
    /**
     * Get Slug
     *
     * @param string $title
     * @param $model
     * @return string
     */
    function getSlugText($title, $model)
    {
        $slug = Str::slug($title);
        if ($slug == '') {
            $slug = 1;
        }
        $slugCount = count($model->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get());
        $slug =  ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
        return $slug;
    }
}

if (!function_exists('getPasswordPolicy')) {
    /**
     * Get password minimum length and password regexp on basis of password strength
     *
     * @param array $passwordValidationRule
     * @return array
    */
    function getPasswordPolicy($passwordValidationRule)
    {
        if (count($passwordValidationRule)== 0) {
            $passwordValidationRule = [];
        }
        $min = 6;
        $passwordValidationRule[] = 'min:'.$min;
        $passwordStrength = 'medium';
        $passwordValidationRule[] = 'regex:/^[A-Za-z0-9!"#.@_~$%^*:|-]*$/';
        $passwordValidationRule[] = 'regex:/[a-z]/';
        $passwordValidationRule[] = 'regex:/[A-Z]/';
        $passwordValidationRule[] = 'regex:/[0-9]/';
        return array('passwordValidationRule'=> $passwordValidationRule,'passwordMinLength'=>$min,'passwordStrength'=>$passwordStrength);
    }
}

if (!function_exists('convertToBytes')) {
    /**
     * Convert to Byte size
     *
     * @return array
     */
    function convertToBytes($input, $convertFrom = "mb")
    {
        switch ($convertFrom) {
            case "kb":
                $output = $input * 1024;
                break;
            case "mb":
                $output = $input * 1024 * 1024;
                break;
            case "gb":
                $output = $input * 1024 * 1024 * 1024;
                break;
        }

        return $output;
    }
}

if (!function_exists('readMoreDescription')) {
    /**
     * get read more html from content
     * @param string $content
     *
     * @return string
     */
    function readMoreDescription($content, $limit = 600)
    {
        $allowed_tags = "<b><br><em><hr><i><li><ol><p><s><span><u><ul><strong>";
        if (strpos($content, "<!-- pagebreak -->") !== false) {
            $content = substr($content, 0, strpos($content, "<!-- pagebreak -->"));
        } else {
            if (strlen($content) > $limit) {
                $last = '...';
            } else {
                $last = '';
            }
            $content = truncateStringWithWholeWord($content, $limit);
        }
        $content = strip_tags($content, $allowed_tags).$last;
        return $content;
    }
}

if (!function_exists('removeSpecialCharacter')) {
    /**
     * remove special characters
     * @param string $content
     *
     * @return string
     */
    function removeSpecialCharacter($content)
    {
        $content = str_replace(' ', '-', $content);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $content);
    }
}

if (!function_exists('displayMessages')) {
    function displayMessages($module, $status)
    {
        $message = '';
        if ($status =='1') {
            $message = __($module.'.record_active_success');
        } elseif ($status =='0') {
            $message = __($module.'.record_inactive_success');
        }
        return $message;
    }
}

if (! function_exists('displayDate')) {
    function displayDate($value)
    {
        if (isset($value)) {
            $timestamp = $value;
            $date = \Carbon\Carbon::parse($timestamp)->format(config('app.date_format'));
            return $date;
        }
        return false;
    }
}

if (! function_exists('displayTime')) {
    function displayTime($value)
    {
        if (isset($value)) {
            $timestamp = $value;
            $date = \Carbon\Carbon::parse($timestamp)->format(config('app.time_format'));
            return $date;
        }
        return false;
    }
}

if (! function_exists('truncateStringWithWholeWord')) {
    function truncateStringWithWholeWord($string, $limit_number)
    {
        $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
            $length += strlen($parts[$last_part]);
            if ($length > $limit_number) {
                break;
            }
        }

        return implode(array_slice($parts, 0, $last_part));
    }
}
