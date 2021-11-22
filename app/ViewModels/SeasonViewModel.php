<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\ViewModels\ViewModel;

class SeasonViewModel extends ViewModel
{
  public $season;
  public $tv;

  public function __construct($season, $tv)
  {
    $this->season = $season;
    $this->tv = $tv;
  }

  public function season()
  {
    return collect($this->season)->merge([
      'poster_path' => $this->season['poster_path']
        ? 'https://image.tmdb.org/t/p/w500' . $this->season['poster_path']
        : 'https://via.placeholder.com/750x500?text=ERROR',
      'air_date' => Carbon::parse($this->season['air_date'])->format('Y'),
      'season_link' => '/season/' . $this->season['season_number'],
    ]);
  }

  public function getEpisode()
  {
    $episodes = collect($this->season)->get('episodes');

    return collect($episodes)->map(function ($episode) {
      return collect($episode)->merge([
        'air_date' => Carbon::parse($episode['air_date'])->format('d F Y'),
        'still_path' => $episode['still_path']
          ? 'https://image.tmdb.org/t/p/w500' . $episode['still_path']
          : 'https://via.placeholder.com/750x500?text=ERROR',
        'episode_link' => '/episode/' . $episode['episode_number'],
        'overview' => $episode['overview'] ? $episode['overview'] : "We don't have enough data for this episode"
      ]);
    });
  }

  public function getTv()
  {
    return collect($this->tv)->merge([
      'slug' =>  $this->tv['id'] . '/' . Str::slug($this->tv['name']),
    ])->only([
      'id', 'name', 'slug', 'original_name'
    ]);
  }
}
