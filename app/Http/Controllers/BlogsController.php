<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogsController extends Controller
{
    public function get(String $name){
        $content = '';

        $path = Storage::disk('public')->path('blogs/' . $name . '.md');

        $file = fopen($path, 'r');

        $index = 0;

        while (($line = fgets($file)) !== false) {

            if (str_starts_with(trim($line), '---')) {
                $index++;
                continue;
            }

            if ($index >= 2) {
                $content .= $line;
            }
        }

        fclose($file);

        $content = Str::markdown($content);
        return response()->json(['content' => $content]);
    }

    public function get_all_names(){
        $names = Storage::disk('public')->files('blogs');

        $blogs = [];

        foreach($names as $name){
            $file = Storage::disk('public')->readStream($name);

            if($file){
                $blog = [
                    'name' => '',
                    'date' => null,
                ];
                $mod = str_replace('.md', '', $name);

                $mod = str_replace('blogs/', '', $mod);

                $mod = str_replace('-', ' ', $mod);

                $blog['name'] = $mod;

                while(($line = fgets($file)) !== false){
                    if(str_starts_with($line, 'date:')){
                        $date = str_replace('date: ', '', $line);
                        $date = str_replace("\n", '', $date);
                        $blog['date'] = $date;
                        break;
                    }
                }

                $blogs[] = $blog;
            }

        }

        return response()->json(['blogs' => $blogs]);
    }
}
