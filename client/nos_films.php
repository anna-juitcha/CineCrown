```php
<?php
require '../db.php';

// Récupération de tous les films
$requete = $pdo->query("SELECT * FROM film ORDER BY id_film DESC");
$films = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinéCrown - Nos Films</title>

    <link rel="stylesheet" href="../index.css">

    <style>

        /* CONTENU PRINCIPAL */

        .main-content{
            margin-left: 300px;
            padding: 40px;
            min-height: 100vh;
            font-family:'Cormorant Garamond',serif;
        }

        /* TITRE */

        h1{
            color: #d4af37;
            font-size: 38px;
            margin-bottom: 40px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(212,175,55,0.4);
            letter-spacing: 1px;
            text-shadow: 0 0 10px rgba(212,175,55,0.3);
        }

        /* GRID */

        .movies-grid{
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 35px;
        }

        /* CARD */

        .movie-card{
            background: linear-gradient(45deg, #d9b463, #69530a);
            border: 1px solid rgba(212,175,55,0.2);
            border-radius: 18px;
            overflow: hidden;
            transition: 0.4s;
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        }

        .movie-card:hover{
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(59, 59, 57, 0.2);
        }

        /* IMAGE */

        .poster-container{
            position: relative;
            width: 100%;
            height: 430px;
            overflow: hidden;
            display: block;
            text-decoration: none;
        }

        .movie-poster{
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
            display: block;
        }

        /* OVERLAY */

        .overlay{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: 0.4s;
        }

        .overlay span{
            color: #d4af37;
            font-size: 24px;
            font-weight: bold;
            padding: 12px 22px;
            letter-spacing: 1px;
            text-align: center;
        }

        .poster-container:hover .overlay{
            opacity: 1;
        }

        .poster-container:hover .movie-poster{
            transform: scale(1.08);
            filter: brightness(35%);
        }

        /* CONTENU */

        .movie-content{
            padding: 22px;
        }

        .movie-title{
            font-size: 1.7rem;
            font-weight: bold;
            color: #6c5b25;
            margin-bottom: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .movie-info{
            font-size: 1rem;
            color: #d6d6d6;
            margin: 10px 0;
            line-height: 1.5;
        }

        .movie-info strong{
            color: #ffffff;
        }

        /* RESPONSIVE */

        @media(max-width: 900px){

            .main-content{
                margin-left: 0;
                padding: 20px;
            }

            .movies-grid{
                grid-template-columns: 1fr;
            }

            .poster-container{
                height: 350px;
            }

            h1{
                font-size: 28px;
            }
        }

    </style>

</head>

<body>

    <?php require 'index_client.php'; ?>

    <div class="main-content">

        <h1>🎬 À l'affiche chez CinéCrown</h1>

        <?php if (empty($films)): ?>

            <p>Aucun film disponible pour le moment.</p>

        <?php else: ?>

            <div class="movies-grid">

                <?php foreach ($films as $f): ?>

                    <div class="movie-card">

                        <?php if (!empty($f['bande_annonce'])): ?>

                            <a href="<?= htmlspecialchars($f['lien_annonce']) ?>" 
                               target="_blank" 
                               class="poster-container">

                                <img src="../admin/<?= htmlspecialchars($f['bande_annonce']) ?>" 
                                     alt="Affiche de <?= htmlspecialchars($f['titre']) ?>" 
                                     class="movie-poster">

                                <div class="overlay">
                                    <span>▶ Cliquer pour voir la bande-annonce</span>
                                </div>

                            </a>

                        <?php else: ?>

                            <div class="poster-container">

                                <div class="movie-poster" 
                                     style="display:flex;justify-content:center;align-items:center;background:#222;color:#666;font-size:20px;">

                                    Pas d'image

                                </div>

                            </div>

                        <?php endif; ?>

                        <div class="movie-content">

                            <div class="movie-title">
                                <?= htmlspecialchars($f['titre']) ?>
                            </div>

                            <div class="movie-info">
                                <strong>Genre :</strong>
                                <?= htmlspecialchars($f['genre']) ?>
                            </div>

                            <div class="movie-info">
                                <strong>Réalisateur :</strong>
                                <?= htmlspecialchars($f['realisateur']) ?>
                            </div>

                            <div class="movie-info">
                                <strong>Durée :</strong>
                                <?= htmlspecialchars($f['duree']) ?> min
                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</body>
</html>
```
