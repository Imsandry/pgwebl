<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
    protected $table = 'polygons';
    protected $guarded = ['id'];

    public function gejson_polygons()
    {
        $polygons = $this->select(DB::raw('id, ST_AsGeoJSON(geom) AS geom,
            name,
            description,
            ST_Area(geom, true) AS area_m2,
            ST_Area(geom, true) / 1000000 AS area_km2,
            ST_Area(geom, true) / 10000 AS area_hektar,
            created_at,
            updated_at, image'))
            ->get();

        $geojson = [
            'type'=> 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'area_m2' => $p->area_2,
                    'area_km' => $p->area_km,
                    'area_hektar' => $p->area_hektar,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' =>$p->image,
                ],
            ];

            array_push($geojson['features'], $feature);

        }
        return($geojson);
    }
}
