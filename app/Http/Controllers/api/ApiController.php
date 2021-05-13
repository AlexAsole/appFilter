<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function appRequest(Request $request)
    {
        $name = $request->query('name');
        $category = $request->query('category');
        $response = Http::get('https://itunes.apple.com/us/rss/toppaidapplications/limit=200/json');
        if (isset($name)) {
            $name = strtolower($name);
            $filtered = [];
            foreach ($response->json()['feed']['entry'] as $element) {
                if (str_contains(strtolower($element['im:name']['label']), $name)) {
                    array_push($filtered, $element);
                }
            }
            return $filtered;
        } else if (isset($category)) {
            $filtered = [];
            foreach ($response->json()['feed']['entry'] as $element) {
                if ($element['category']['attributes']['label'] === $category) {
                    array_push($filtered, $element);
                }
            }
            return $filtered;
        } else {
            return $response;
        }
    }
}
