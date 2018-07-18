<?php

/*
 * This file is part of the Slim API skeleton package
 *
 * Copyright (c) 2016 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/slim-api-skeleton
 *
 */

namespace App;

use Spot\EntityInterface as Entity;
use Spot\MapperInterface as Mapper;
use Spot\EventEmitter;

use Tuupola\Base62;

use Ramsey\Uuid\Uuid;
use Psr\Log\LogLevel;

class Post extends \Spot\Entity
{
    protected static $table = "posts";

    public static function fields()
    {
        return [
            "id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "section_id" => ["type" => "integer", "unsigned" => true, "default" => 0, 'index' => true],
            "position_id" => ["type" => "integer", "unsigned" => true, "default" => 0, 'index' => true],
            "pic1_url" => ["type" => "string", "length" => 255],
            "pic2_url" => ["type" => "string", "length" => 255],
            "pic3_url" => ["type" => "string", "length" => 255],
            "pic4_url" => ["type" => "string", "length" => 255],
            "pic5_url" => ["type" => "string", "length" => 255],
            "pic6_url" => ["type" => "string", "length" => 255],
            "picshare_url" => ["type" => "string", "length" => 255],
            "background_url" => ["type" => "string", "length" => 255],
            "title" => ["type" => "string", "length" => 250],
            "intro" => ["type" => "text"],
            "content_text" => ["type" => "text"],
            "deleted" => ["type" => "boolean", "value" => false, "notnull" => true],
            "enabled" => ["type" => "boolean", "value" => false],
            "from_datetime" => ["type" => "datetime", "value" => new \DateTime()],
            "to_datetime" => ["type" => "datetime", "value" => new \DateTime()],
            "created" => ["type" => "datetime", "value" => new \DateTime()],
            "updated" => ["type" => "datetime", "value" => new \DateTime()]
        ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'section' => $mapper->belongsTo($entity, 'App\ThemeSection', 'section_id'),
            'position' => $mapper->belongsTo($entity, 'App\ThemePosition', 'position_id')
        ];
    }
    
    public function transform(Vehicle $entity)
    {

        return [
            "id" => (integer) $entity->id ?: null,
            "picture" => (string) $entity->picture_url ?: "",
            "background" => (string) $entity->background_url ?: "",
            "uploads" => (array) $uploads ?: [],
            "brand" => (string) $entity->model ?: "",
            "model" => (string) $entity->brand ?: "",
            "status" => (string) $entity->status ?: "",
            "version" => (string) $entity->version ?: "",
            "created" => (string) $entity->created->format('U') ?: "",
            "user" => [
                'id' => (integer) $entity->user->id ?: null,
                "username" => (string) $entity->user->username ?: "",
                "first_name" => (string) $entity->user->first_name ?: "",
                "last_name" => (string) $entity->user->last_name ?: "",
                "picture" => (string) $entity->user->picture ?: ""
            ],
            "model" => [
                "id" => (integer) $entity->model_id ?: null,
                "title" => (string) $entity->model->title ?: null
            ]
        ];
    }

    public function timestamp()
    {
        return $this->updated->getTimestamp();
    }

    public function clear()
    {
        $this->data([
            "fullname" => null
        ]);
    }
}
