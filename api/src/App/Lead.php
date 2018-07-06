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

class Lead extends \Spot\Entity
{
    protected static $table = "leads";

    public static function fields()
    {
        return [
            "id" => ["type" => "integer", "unsigned" => true, "primary" => true, "autoincrement" => true],
            "user_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "gestor_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "plan_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "region_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "city_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "brand_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "model_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "version_id" => ["type" => "integer", "unsigned" => true, "default" => NULL, 'index' => true],
            "code" => ["type" => "string", "length" => 255],            
            "origin" => ["type" => "string", "length" => 50],
            "picture" => ["type" => "string", "length" => 255],
            "background" => ["type" => "string", "length" => 255],
            "first_name" => ["type" => "string", "length" => 50],
            "last_name" => ["type" => "string", "length" => 50],
            "full_name" => ["type" => "string", "length" => 50],
            "tel" => ["type" => "string", "length" => 50],
            "use" => ["type" => "string", "length" => 50],
            "email" => ["type" => "string", "length" => 50],
            "comment" => ["type" => "text"],
            "observations" => ["type" => "text"],
            "region" => ["type" => "string", "length" => 50],
            "doc_poliza" => ["type" => "string", "length" => 255],
            "locality" => ["type" => "string", "length" => 50],
            "administrative_area_level_1" => ["type" => "string", "length" => 50],
            "administrative_area_level_2" => ["type" => "string", "length" => 50],
            "formatted_address" => ["type" => "string", "length" => 250],
            "country" => ["type" => "string", "length" => 50],
            "vicinity" => ["type" => "string", "length" => 50],
            "map_icon" => ["type" => "string", "length" => 250],
            "map_url" => ["type" => "string", "length" => 250],
            "address" => ["type" => "string", "length" => 250],
            "utc" => ["type" => "string", "length" => 20],
            "lat" => ["type" => "string", "length" => 50],
            "lng" => ["type" => "string", "length" => 50],                                    
            "brand" => ["type" => "string", "length" => 50],
            "ref" => ["type" => "string", "length" => 50],
            "fecha" => ["type" => "string", "length" => 50],
            "refn" => ["type" => "string", "length" => 50],
            "model" => ["type" => "string", "length" => 50],
            "version" => ["type" => "string", "length" => 50],
            "chasis" => ["type" => "string", "length" => 50],
            "mt_year" => ["type" => "integer", "length" => 4],
            "gas" => ["type" => "boolean", "value" => false, "notnull" => true],
            "status" => ["type" => "string", "length" => 50],
            "accept_terms" => ["type" => "boolean", "value" => false, "notnull" => true],
            "financing" => ["type" => "boolean", "value" => false, "notnull" => true],
            "newsletter" => ["type" => "boolean", "value" => false, "notnull" => true],
            "request_sent" => ["type" => "datetime"],
            "product_since" => ["type" => "datetime"],
            "complete" => ["type" => "boolean", "value" => false, "notnull" => true],
            "deleted" => ["type" => "boolean", "value" => false, "notnull" => true],
            "enabled" => ["type" => "boolean", "value" => false],
            "created" => ["type" => "datetime", "value" => new \DateTime()],
            "updated" => ["type" => "datetime", "value" => new \DateTime()]
        ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'user' => $mapper->belongsTo($entity, 'App\User', 'user_id'),
            'gestor' => $mapper->belongsTo($entity, 'App\User', 'gestor_id'),
            'region' => $mapper->belongsTo($entity, 'App\Region', 'region_id'),
            'city' => $mapper->belongsTo($entity, 'App\City', 'city_id'),
            'brand' => $mapper->belongsTo($entity, 'App\Brand', 'brand_id'),
            'model' => $mapper->belongsTo($entity, 'App\Model', 'model_id'),
            'version' => $mapper->belongsTo($entity, 'App\Version', 'version_id'),
            'plan' => $mapper->belongsTo($entity, 'App\Plan', 'plan_id'),
            'plans' => $mapper->hasMany($entity, 'App\Plan', 'lead_id')->order(['price' => 'ASC']),
            'uploads' => $mapper->hasMany($entity, 'App\Upload', 'lead_id')->order(['created' => 'DESC'])
        ];
    }
    
    public function transform(Lead $entity)
    {

        $plans = [];
        foreach($entity->plans as $item){
            $plans[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'text' => $item->text,
                'currency' => $item->currency,
                'updated' => $item->updated->format('U')
            ];
        }

        $uploads = [];
        foreach($entity->uploads as $item){
            $uploads[] = [
                'id' => $item->id,
                'file_url' => $item->file_url,
                'filesize' => $plan->filesize,
                'updated' => $item->updated->format('U')
            ];
        }

        return [
            "id" => (integer) $entity->id ?: null,
            "plan_id" => (integer) $entity->plan_id ?: null,
            "full_name" => (string) $entity->full_name ?: "",
            "first_name" => (string) $entity->first_name ?: "",
            "last_name" => (string) $entity->last_name ?: "",
            "address" => (string) $entity->address ?: "",
            "administrative_area_level_1" => (string) $entity->administrative_area_level_1 ?: "",
            "administrative_area_level_2" => (string) $entity->administrative_area_level_2 ?: "",
            "formatted_address" => (string) $entity->formatted_address ?: "",
            "vicinity" => (string) $entity->vicinity ?: "",
            "country" => (string) $entity->country ?: "",
            "mt_year" => (string) $entity->mt_year ?: "",
            "doc_poliza" => (string) $entity->doc_poliza ?: "",
            "comment" => (string) $entity->comment ?: "",
            "observations" => (string) $entity->observations ?: "",
            "plan" => (object) $entity->plan ?: [],
            "plans" => (array) $plans ?: [],
            "uploads" => (array) $uploads ?: [],
            "email" => (string) $entity->email ?: "",
            "code" => (string) $entity->code ?: "",
            //"city" => (string) $entity->city ?: "",
            "brand" => (string) $entity->brand ?: "",
            "use" => (string) $entity->use ?: "",
            "gas" => !!$entity->gas,
            "model" => (string) $entity->model ?: "",
            "status" => (string) $entity->status ?: "",
            "plan" => [
                'id' => (integer) $entity->plan->id ?: null,
                "name" => (string) $entity->plan->name ?: "",
                "text" => (string) $entity->plan->text ?: "",
                "price" => (string) $entity->plan->price ?: "",
                "currency" => (string) $entity->plan->currency ?: ""
            ],            
            "user" => [
                'id' => (integer) $entity->user->id ?: null,
                "username" => (string) $entity->user->username ?: "",
                "first_name" => (string) $entity->user->first_name ?: "",
                "last_name" => (string) $entity->user->last_name ?: "",
                "picture" => (string) $entity->user->picture ?: ""
            ],
            "gestor" => [
                'id' => (integer) $entity->gestor->id ?: null,
                "username" => (string) $entity->gestor->username ?: "",
                "first_name" => (string) $entity->gestor->first_name ?: "",
                "last_name" => (string) $entity->gestor->last_name ?: "",
                "picture" => (string) $entity->gestor->picture ?: ""
            ],
            //"request_sent" => (string) $entity->request_sent ? $entity->request_sent->format('U') : "",
            "product_since" => (string) $entity->product_since ? $entity->product_since->format('U') : "",
            "created" => (string) $entity->created->format('U') ?: ""
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
