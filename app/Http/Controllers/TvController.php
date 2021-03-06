<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ViewModels\TvViewModel;
use App\ViewModels\TvShowViewModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
{
	public function index()
	{
		$airingToday = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/airing_today')
			->json()['results'];

		$onTheAir = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/on_the_air')
			->json()['results'];

		$popularTv = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/popular')
			->json()['results'];

		$topRated = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/top_rated')
			->json()['results'];

		$viewModel = new TvViewModel(
			$airingToday,
			$onTheAir,
			$popularTv,
			$topRated,
		);

		return view('tv.index', $viewModel);
	}

	public function show($id)
	{
		$tvshow = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/' . $id . '?append_to_response=credits,videos,images')
			->json();

		$recommendTv = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/' . $id . '/recommendations')
			->json()['results'];

		$keywords = Http::withToken(config('services.tmdb.token'))
			->get('https://api.themoviedb.org/3/tv/' . $id . '/keywords')
			->json()['results'];

		$viewModel = new TvShowViewModel($tvshow, $recommendTv, $keywords);

		return view('tv.show', $viewModel);
	}
}
