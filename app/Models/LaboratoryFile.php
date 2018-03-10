<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LaboratoryFile extends Model
{
    protected $fillable = [
            'filename',
            'extension',
            'bytes',
            'crc32',
            'sha512',
            'md5',
            'eth_address'
        ];

    protected $appends = [
            'download_link',
            'converted_size',
        ];

    protected $visible = [
            'filename',
            'extension',
            'bytes',
            'crc32',
            'sha512',
            'md5',
            'eth_address',
            'download_link',
        ];

    /**
     * @var UploadedFile
     */
    protected $_file;
    /**
     * @var Laboratory
     */
    protected $_lab;

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function getDownloadLinkAttribute()
    {
        return route('lab.download', [
            'lab'  => $this->eth_address,
            'file' => $this->sha512,
        ],false);
    }

    public function getConvertedSizeAttribute()
    {
        return bytes_convert( $this->bytes );
    }

    /**
     * @param UploadedFile   $file ONLY VALID FILE
     * @param Laboratory $lab
     * @param String|null $filename
     *
     * @return $this
     */
    public static function saveUploadedFile(UploadedFile $file, Laboratory $lab, $filename = null)
    {
        $filename = trim($filename);
        $filename = (empty($filename))
            ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
            : $filename;
        $extension = pathinfo($file->getClientOriginalName(),
            PATHINFO_EXTENSION);

        $labFile = new static();
        $labFile->_file = $file;
        $labFile->filename = trim($filename);
        $labFile->extension = $extension;
        $labFile->eth_address = $lab->eth_address;
        $labFile->bytes = $file->getSize();
        $labFile->crc32 = hash_file('crc32', $file->getRealPath());
        $labFile->sha512 = hash_file('sha512', $file->getRealPath());
        $labFile->md5 = hash_file('md5', $file->getRealPath());
        $labFile->save();

        return $labFile;
    }

    public static function boot()
    {
        parent::boot();
        parent::created([static::class, '_moveFile']);
    }

    protected function _moveFile($labFile)
    {
        if ($labFile->_file instanceof UploadedFile) {
            $labFile->_file->move(
                storage_path(Laboratory::storageLocation($labFile->eth_address)),
                $labFile->sha512
            );
        }
    }
}
