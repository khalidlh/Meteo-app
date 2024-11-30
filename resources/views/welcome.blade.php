<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo Mondiale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .hero {
            background: url("{{asset('assets/images/meteo.avif')}}") no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            position: relative;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Overlay sombre */
            z-index: 1;
        }

        .hero .text-center {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 20px;
        }

        .weather-card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .weather-card:hover {
            transform: translateY(-10px);
        }

        .card-img-top {
            height: 200px;
            /* Hauteur fixe */
            object-fit: cover;
            /* Ajuste l'image à la taille sans déformation */
            width: 100%;
            /* Largeur de l'image */
        }
    </style>
</head>

<body style="background-color: #f0f8ff;">

    <!-- Hero Section -->
    <div class="hero">
        <div class="text-center">
            <h1>🌍 Bienvenue à la Météo Mondiale</h1>
            <p>Consultez les dernières prévisions météo dans le monde entier</p>
            <a href="#search" class="btn btn-light mt-3">Rechercher une ville</a>
        </div>
    </div>

    <div class="container py-5">

        <section>
            <h2 class="text-center mb-5">📰 Dernières nouvelles météo dans le monde</h2>
            <div class="row">
                @forelse ($weatherAlerts as $alert)
                    <div class="col-md-4 mb-3">
                        <div class="card weather-card">
                            <img src="{{ asset('assets/images/' . $alert['image']) }}" class="card-img-top"
                                alt="Image Météo">
                            <div class="card-body">
                                <h5 class="card-title">{{ $alert['event'] }}</h5>
                                <p class="card-text">{{ $alert['description'] }}</p>
                                <p><small>Début : {{ $alert['start'] }}</small></p>
                                <p><small>Fin : {{ $alert['end'] }}</small></p>
                                <!-- Lien pour ouvrir la modal -->
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#alertModal{{ $loop->index }}">Lire plus</a>
                            </div>
                        </div>
                    </div>

                    <!-- Modal pour chaque alerte -->
                    <div class="modal fade" id="alertModal{{ $loop->index }}" tabindex="-1"
                        aria-labelledby="alertModalLabel{{ $loop->index }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="alertModalLabel{{ $loop->index }}">
                                        {{ $alert['event'] }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Description :</h6>
                                    <p>{{ $alert['description'] }}</p>
                                    <h6>Période :</h6>
                                    <p>Début : {{ $alert['start'] }}</p>
                                    <p>Fin : {{ $alert['end'] }}</p>
                                    <img src="{{ asset('assets/images/' . $alert['image']) }}" class="img-fluid"
                                        alt="Image Alerte">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Aucune alerte météo disponible pour le moment.</p>
                @endforelse
            </div>
        </section>



        <!-- Section Rechercher la Météo -->
        <section id="search" class="mt-5">
            <h2 class="text-center mb-5">🔍 Rechercher la météo d'une ville</h2>
            <form id="weatherSearchForm" class="text-center">
                <div class="input-group mb-3 mx-auto" style="max-width: 500px;">
                    <input type="text" id="cityInput" class="form-control" name="city"
                        placeholder="Entrez une ville ou un pays" required>
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                </div>
            </form>

            <!-- Section pour afficher les résultats -->
            <div id="weatherResults" class="mt-5 text-center">
                @if (isset($weatherData))
                    <h3>Météo à {{ $weatherData['city'] }}</h3>
                    <p class="display-4">{{ $weatherData['temp'] }}°C</p>
                    <img src="https://openweathermap.org/img/wn/{{ $weatherData['icon'] }}@2x.png" alt="Icône Météo">
                    <p>{{ ucfirst($weatherData['description']) }}</p>
                @endif
            </div>
        </section>

        <!-- Section Météo dans plusieurs villes -->
        <section class="mt-5">
            <h2 class="text-center mb-5">🏙️ Météo dans plusieurs villes</h2>
            <div class="row">
                @foreach ($weatherLocale as $weather)
                    <div class="col-md-3 mb-3">
                        <div class="card weather-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $weather['city'] }}</h5>
                                <p class="display-6">{{ $weather['temp'] }}°C</p>
                                <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png"
                                    alt="Icône Météo">
                                <p>{{ ucfirst($weather['description']) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Soumettre le formulaire sans recharger la page
            $('#weatherSearchForm').on('submit', function(e) {
                e.preventDefault(); // Empêche la soumission du formulaire normale

                var city = $('#cityInput').val(); // Récupérer la ville saisie

                // Vérifier si la ville n'est pas vide
                if (city) {
                    $.ajax({
                        url: "{{ route('weather.index') }}", // L'URL du contrôleur
                        method: 'GET',
                        data: {
                            city: city, // Passer la ville en paramètre
                            _token: '{{ csrf_token() }}' // Inclure le token CSRF
                        },
                        success: function(response) {
                            // Mettre à jour la section avec les résultats de la recherche
                            $('#weatherResults').html(response);
                        },
                        error: function() {
                            $('#weatherResults').html(
                                '<p class="text-danger">Une erreur s\'est produite lors de la recherche.</p>'
                            );
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
