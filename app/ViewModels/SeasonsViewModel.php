<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\ViewModels\ViewModel;

class SeasonsViewModel extends ViewModel
{
  public $seasons;

  public function __construct($seasons)
  {
    $this->seasons = $seasons;
  }

  public function seasons()
  {
    $seasons = collect($this->seasons['seasons']);

    return collect($seasons)->sortBy('season_number')->map(function ($season) {
      return collect($season)->merge([
        'poster_path' => $season['poster_path']
          ? 'https://image.tmdb.org/t/p/w500' . $season['poster_path']
          : 'https://via.placeholder.com/500x750?text=ERROR',
        'year_date' => Carbon::parse($season['air_date'])->format('Y'),
        'air_date' => $season['air_date'] != null
          ? ' aired on ' . Carbon::parse($season['air_date'])->format('d F Y')
          : ' will be aired soon',
        'season_link' => '/season/' . $season['season_number'],
        'overview' => $season['overview'] ? $season['overview'] : "We don't have enough data for this series.",
      ]);
    });
  }

  public function getTv()
  {
    return collect($this->seasons)->merge([
      'slug' =>  $this->seasons['id'] . '/' . Str::slug($this->seasons['name']),
      'tv_poster' => $this->seasons['poster_path']
        ? 'https://image.tmdb.org/t/p/w500' . $this->seasons['poster_path']
        : 'https://via.placeholder.com/500x750?text=ERROR',
      'backdrop_path' => $this->seasons['backdrop_path']
        ? 'https://image.tmdb.org/t/p/original' . $this->seasons['backdrop_path']
        : 'https://via.placeholder.com/750x500?text=ERROR',
      'tv_year_date' => Carbon::parse($this->seasons['first_air_date'])->format('Y'),
    ])->only([
      'id', 'name', 'original_name', 'poster_path', 'tv_poster', 'first_air_date', 'tv_year_date', 'slug', 'backdrop_path'
    ]);
  }
}
