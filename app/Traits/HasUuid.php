<?php

namespace App\Traits;

trait HasUuid
{
    /**
     * Boot function to automatically generate UUID on creating.
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = self::generateUuidV4();
            }
        });
    }

    /**
     * Generate an RFC 4122 compliant UUID version 4 string.
     *
     * @return string
     */
    public static function generateUuidV4()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
