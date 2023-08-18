<?php

namespace Notabenedev\ProductImport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\YmlFile;


class ImportYml extends Model
{
    use  HasFactory;

    protected $fillable = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->uuid = Str::uuid();
        });

        //триггер во время удаления
        self::deleting(function (self $model) {

            foreach ($model->files as $item) {
                //удаляем модели CmlFile
                $item->delete();
            }

        });
    }

    /**
     * Изменить дату создания.
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return datehelper()->changeTz($value);
    }

    /**
     * Файлы.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(YmlFile::class);
    }

    /**
     * Импорты.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imports()
    {
        return $this->hasMany(YmlFile::class)->where("type", "import");
    }

    /**
     * Офферсы.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(YmlFile::class)->where("type", "offers");
    }

    /**
     * Полные выгрузки.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function catalog()
    {
        return $this->hasMany(YmlFile::class)->where("type", "catalog");
    }

}
