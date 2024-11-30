@if($weatherData)
    <h3>Météo à {{ $weatherData['city'] }}</h3>
    <p class="display-4">{{ $weatherData['temp'] }}°C</p>
    <img src="https://openweathermap.org/img/wn/{{ $weatherData['icon'] }}@2x.png" alt="Icône Météo">
    <p>{{ ucfirst($weatherData['description']) }}</p>
@else
    <p class="text-danger">Aucune donnée météo trouvée pour la ville spécifiée.</p>
@endif
