<?php

namespace taskforce\models\dto;

class LocationDto
{
    public float $latitude;
    public float $longitude;
    public int $city_id;

    public function __construct(float $latitude, float $longitude, int $city_id)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->city_id = $city_id;
    }
}