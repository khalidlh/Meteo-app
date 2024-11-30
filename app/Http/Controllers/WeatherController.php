<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller

{
    public function index(Request $request)
    {
        $apiKey = env('WEATHER_API_KEY'); // Clé API dans .env
        $cities = ['Paris', 'New York', 'Tokyo', 'Marrakech'];
        $weatherLocale = [];
        $weatherAlerts = [];
    
        // Récupérer la météo pour les villes prédéfinies
        foreach ($cities as $city) {
            $weatherData = $this->getWeatherData($city, $apiKey);
            if ($weatherData) {
                $weatherLocale[] = $weatherData;
            }
             // Si la requête est AJAX
    
        }
    
        // Récupérer les alertes météo globales
        $weatherAlerts = $this->getWeatherAlerts($apiKey);
        if (empty($weatherAlerts)) {
            // Définir des alertes par défaut avec images
            $weatherAlerts = [
                [
                    'event' => 'Tempête en Atlantique',
                    'description' => 'Une tempête de catégorie 5 approche des côtes de l\'Atlantique Nord.',
                    'start' => '01 Décembre 2024 14:00',
                    'end' => '02 Décembre 2024 18:00',
                    'image' => 'tempete.jpg', // Image pour l'alerte
                ],
                [
                    'event' => 'Inondations en Asie',
                    'description' => 'De fortes pluies provoquent des inondations dans plusieurs régions d\'Asie du Sud-Est.',
                    'start' => '01 Décembre 2024 10:00',
                    'end' => '02 Décembre 2024 12:00',
                    'image' => 'assia.jpg', // Image pour l'alerte
                ],
                [
                    'event' => 'Vague de chaleur en Europe',
                    'description' => 'Une vague de chaleur exceptionnelle atteint des températures record en Europe.',
                    'start' => '01 Décembre 2024 08:00',
                    'end' => '03 Décembre 2024 20:00',
                    'image' => 'erope.png', // Image pour l'alerte
                ]
            ];
        }
    
        // Récupérer la météo pour la ville recherchée par l'utilisateur
        $searchedCity = $request->input('city', 'Marrakech');
        $searchedWeather = $this->getWeatherData($searchedCity, $apiKey);
        if ($request->ajax()) {
            return view('partials.weather_results', [
                'weatherData' => $searchedWeather,
                'weatherLocale' => $weatherLocale
            ]);
        }
        if ($searchedWeather) {
            return view('welcome', [
                'weatherData' => $searchedWeather,
                'weatherLocale' => $weatherLocale,
                'weatherAlerts' => $weatherAlerts
            ]);
        }
    
        return back()->with('error', "La ville '{$searchedCity}' est introuvable ou un problème est survenu avec l'API.");
    }
    
    /**
     * Récupère les données météo pour une ville donnée.
     */
    private function getWeatherData($city, $apiKey)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather";
        $response = Http::get($url, [
            'q' => $city,
            'units' => 'metric',
            'appid' => $apiKey
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            return [
                'city' => $city,
                'temp' => $data['main']['temp'],
                'icon' => $data['weather'][0]['icon'],
                'description' => ucfirst($data['weather'][0]['description'])
            ];
        }
    
        return null;
    }
    
    /**
     * Récupère les alertes météo à partir de l'API OpenWeatherMap One Call.
     */
    private function getWeatherAlerts($apiKey)
    {
        $lat = 31.63;  // Latitude de Marrakech (exemple)
        $lon = -8.00;  // Longitude de Marrakech (exemple)
        $url = "https://api.openweathermap.org/data/2.5/onecall";
        
        $response = Http::get($url, [
            'lat' => $lat,
            'lon' => $lon,
            'exclude' => 'current,minutely,hourly,daily',
            'appid' => $apiKey
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            $alerts = $data['alerts'] ?? [];
            
            return array_map(function ($alert) {
                return [
                    'event' => $alert['event'],
                    'description' => $alert['description'],
                    'start' => date('d M Y H:i', $alert['start']),
                    'end' => date('d M Y H:i', $alert['end']),
                ];
            }, $alerts);
        }
    
        return [];
    }
}

