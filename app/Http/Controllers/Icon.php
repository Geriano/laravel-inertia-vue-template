<?php

namespace App\Http\Controllers;

class Icon extends Controller
{
  /**
   * @param int $file
   * @param int $r
   * @param int $g
   * @param int $b
   * @param int $a
   * @return \Illuminate\Http\Response
   */
  public function rgba(string $file, int $r, int $g, int $b, float $a = 1)
  {
    if (file_exists($file = public_path("/icons/{$file}.svg"))) {
      $source = file_get_contents($file);
      $source = str_replace('$r', $r, $source);
      $source = str_replace('$g', $g, $source);
      $source = str_replace('$b', $b, $source);
      $source = str_replace('$a', $a, $source);

      return response($source, headers: [
        'Content-Type' => 'image/svg+xml',
      ]);
    }

    return abort(404);
  }
}