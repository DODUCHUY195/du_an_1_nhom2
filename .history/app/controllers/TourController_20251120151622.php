<?php
class TourController
{
    public $modelTour;
    public function __construct()
    {
        $this->modelTour = new Tour();
    }
    public function index()
    {
        require_once './views/tours/index.php';
    }
    public function create()
    {
        require_once './views/tours/addForm.php';
    }

    public function edit() {

    }
}
