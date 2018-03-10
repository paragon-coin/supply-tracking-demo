<?php
/**
 * Created by LINKeRxUA <lnker.ua@gmail.com>
 * linkedIn:    https://www.linkedin.com/in/bogdan-kotelva/
 * Date: 10.11.17
 * Time: 12:21
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FarmerFile extends Model
{
    protected $fillable
        = [
            // file data
            'filename',
            'extension',
            'bytes',
            'crc32',
            'sha512',
            'md5',
            'eth_address',
        ];

    protected $appends = [
            'download_link',
            'converted_size',
        ];

    protected $hidden = [
            'id',
            'farmer_id',
            'converted_size',
            'created_at',
            'updated_at',
            'eth_address',
            'farmer',
        ];

    public static function boot()
    {
        parent::boot();
        parent::created([static::class, '_moveFile']);

    }

    public function farmer()
    {
        return $this->hasOne(Farmer::class,'eth_address','eth_address');
    }

    public function getDownloadLinkAttribute()
    {
        // all urls must be relative!
        return route('farmer.download', [
            'farmer'  => $this->farmer->eth_address,
            'file' => $this->sha512,
        ],false);

    }

    public function getConvertedSizeAttribute()
    {
        return bytes_convert( $this->bytes );
    }

    /**
     * @var UploadedFile
     */
    protected $_file;
    /**
     * @var Farmer
     */
    protected $_farmer;

    /**
     * @param UploadedFile   $file ONLY VALID FILE
     * @param Farmer $farmer
     *
     * @return $this
     */
    public static function saveUploadedFile( UploadedFile $file, Farmer $farmer,  $filename = null) {

        $filename = trim($filename);
        $filename = (empty($filename))
            ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
            : $filename;
        $extension = pathinfo($file->getClientOriginalName(),
            PATHINFO_EXTENSION);

        $fileORM = new static();
        # for future move_uploaded_file();
        $fileORM->_file = $file;
        # set attributes
        $fileORM->eth_address = $farmer->eth_address;
        $fileORM->filename = trim($filename);
        $fileORM->extension = $extension;
        $fileORM->bytes = $file->getSize();
        $fileORM->crc32 = hash_file('crc32', $file->getRealPath());
        $fileORM->sha512 = hash_file('sha512', $file->getRealPath());
        $fileORM->md5 = hash_file('md5', $file->getRealPath());
        $fileORM->save();

        return $fileORM;
    }

    protected function _moveFile(FarmerFile $file)
    {
        if ($file->_file instanceof UploadedFile) {
            $file->_file->move(
                storage_path(Farmer::storageLocation($file->eth_address)),
                $file->sha512
            );
        }
    }
}