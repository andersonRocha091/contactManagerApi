<?php
// app/Http/Controllers/HelloController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Hello, world!']);
    }
}