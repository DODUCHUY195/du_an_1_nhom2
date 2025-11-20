<?php
class TourController
{
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
