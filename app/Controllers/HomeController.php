<?php

class HomeController extends Controller
{

    public function index()
    {
        return View::get('home');
    }
}
