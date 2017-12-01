<?php

namespace App;

use Auth;
use App\Media;
use Illuminate\Database\Eloquent\Model;

class UserMedia extends Model
{
  protected $table = 'user_media';
  protected $fillable = ['media_id', 'user_id'];

  public static function findById($mediaId, $userId)
  {
    return self::where('media_id', $mediaId)
      ->where('user_id', $userId);
  }

  public static function collection($type = false)
  {
    $mediaIds = self::all()->pluck('media_id');

    $query = new Media;
    if($type)
      $query = Media::where('type', $type);

    return $query->find($mediaIds);
  }

  public static function didCollect($mediaId)
  {
    return self::where('media_id', $mediaId)
      ->where('user_id', Auth::user()->id)
      ->exists();
  }
}
