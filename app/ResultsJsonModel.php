<?php


namespace App;


class ResultsModel
{

    public $results = [];

    public function setPingRate($rate)
    {
        $this->results['ping'] = $rate;

        return $this;
    }

    public function getPingRate()
    {
        return $this->results['ping'];
    }

    public function setDownLoad($rate)
    {
        $this->results['download'] = $rate;

        return $this;
    }

    public function getDownload()
    {
        return $this->results['download'];
    }

    public function setUpload($rate)
    {
        $this->results['upload'] = $rate;

        return $this;
    }

    public function getUpload()
    {
        return $this->results['upload'];
    }
}