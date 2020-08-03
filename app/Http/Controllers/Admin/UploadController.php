<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Image\Image;

class UploadController extends Controller
{
    public function store(Request $request)
    {

        // Set max execution time to zero to avoid max execution time error.
        ini_set('max_execution_time', '0');

        $size = $request->post('size') * 1000;

        $rules = ['file' => 'required|mimes:jpeg,png,jpg,gif|max:'.$size];

        $messages = [
            'file.mimes' => 'The uploaded image extension is invalid. Image must be file of type: JPEG/JPG/PNG/GIF',
            'file.max' => 'The file may not be greater than '.$request->post('size').' MB.'
        ];

        $this->validate($request, $rules, $messages);

        $folder = $request->get('folder', 'uploads');
        $file = request()->file('file');
        $imgname = removeSpecialCharacter($file->getClientOriginalName());
        $extension = $file->getClientOriginalExtension();
        //dd(get_class_methods($file));
       
        $filename_arr = explode('.'.$extension, $imgname);
        $renameBaseName = sha1($filename_arr[0].time());
        $filename = $renameBaseName.'.'.$extension;
        $uploadedPath = $file->storeAs($folder, $filename);

        // Generate Resized Images
        if (!empty(config('image-sizes')[$folder])) {
            foreach (config('image-sizes')[$folder] as $prefix => $image) {
                $reized_image = new Image(config('filesystems.disks.local.root').'/'.$uploadedPath);
                $reized_image->useImageDriver(config('filesystems.image_library'));

                switch ($image['method']) {
                    case 'crop': {
                        $reized_image->crop($image['type'], $image['width'], $image['height']);
                        break;
                    };
                    case 'fit': {
                        $reized_image->fit($image['type'], $image['width'], $image['height']);
                        break;
                    }
                }

                $reized_image->save(config('filesystems.disks.local.root').'/'.$folder.'/'.$prefix.'-'.$filename);
            }
        }

        return response()->json(['location' => config('filesystems.disks.public.url').'/'.$uploadedPath, 'file' => $filename]);
    }
}
