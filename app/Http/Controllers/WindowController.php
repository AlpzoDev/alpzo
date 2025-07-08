<?php

namespace App\Http\Controllers;

use Native\Laravel\Facades\Window;

class WindowController extends Controller
{
public function main()
{
$main = Window::open()->minWidth(880)->minHeight(600)->width(900)->height(700);
}
}
