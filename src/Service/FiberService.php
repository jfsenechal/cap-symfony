<?php

namespace Cap\Commercio\Service;

use Fiber;

class FiberService
{

    public function launch()
    {
        echo "Main thread started.\n";
        $fibers = [];

        // Create fibers for multiple asynchronous tasks.
        $fibers[] = new Fiber(function () {
            $response = $this->fetchData("https://api.npoint.io/d96cfc702c24a2992c41");
            $value = Fiber::suspend($response);
            print_r($value."\n");
        });

        $fibers[] = new Fiber(function () {
            $response = $this->fetchData("https://api.npoint.io/c116605fac1cb2b75fe9");
            $value = Fiber::suspend($response);
            print_r($value."\n");
        });

        $fibers[] = new Fiber(function () {
            $response = $this->fetchData("https://api.npoint.io/327efbf48fbb524bef09");
            $value = Fiber::suspend($response);
            print_r($value."\n");
        });

        $captureResponseOnSuspension = [];

        // Start all fibers.
        foreach ($fibers as $i => $fiber) {
            echo "Fiber $i started.\n";
            $captureResponseOnSuspension[$i] = $fiber->start();
        }

        // Resume all fibers.
        foreach ($fibers as $i => $fiber) {
            echo "Fiber $i resumed.\n";
            $fiber->resume($captureResponseOnSuspension[$i]);
        }

        echo "Main thread done.\n";
    }

    function fetchData($url)
    {
        return file_get_contents($url);
    }


}