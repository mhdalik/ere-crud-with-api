<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// class ImageHelpr // fix the spelling
class ImageHelper
{
    /**
     *  Method to save an image to specific folder
     */
    public static  function store($image, $recordId)
    {
        try {
            $file_name =  $recordId . '_' . date('Ymd_His') . '.' . $image->extension();
            $save_status = $image->storePubliclyAs('images/', $file_name);

            if ($save_status) {
                return $file_name;
            } else {
                Log::error('image not saved ......');
                // log folder name and other details
            }
        } catch (\Exception $e) {
            Log::error('Error occured when updating image:error given below');
            Log::error($e->getMessage());
        }
    }



    /**
     *  Method to store new image and delete old image (update)
     */
    public static  function update($image, $recordId, $oldImgFileName)
    {
        try {
            $file_name =  $recordId . '_' . date('Ymd_His') . '.' . $image->extension();
            $save_status = $image->storePubliclyAs('images/', $file_name);

            if ($oldImgFileName) { // deleteing old img
                Storage::delete('images/' . $oldImgFileName);
            }
            if ($save_status) {
                return $file_name;
            } else {
                Log::error('image save error......');
            }
        } catch (\Exception $e) {
            Log::error('Error occured when updating image:error downl below');
            Log::error($e->getMessage());
        }
    }



    // handle image delete   
    /**
     *  Method to delete an image from storage
     *
     */
    public static  function delete($imgFileName)
    {
        try {
            if ($imgFileName) {
                $status = Storage::delete('images/' . $imgFileName);
                return  $status ?? false;
            }
            Log::error('Image file or folder name invalid when try to delete image from storage');
            return false;
        } catch (\Exception $e) {
            Log::error('Error occured when updating image:error downl below');
            Log::error($e->getMessage());
            return false;
        }
    }
}
