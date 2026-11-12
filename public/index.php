<?php

use Zayd54500\TouiteurSae\Action\ActionFactory;
use Zayd54500\TouiteurSae\Dispatcher\Dispatcher;

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$utilisateurRepository = new \Zayd54500\TouiteurSae\Repository\UtilisateurRepository();
$utilisateurs = $utilisateurRepository->obtenirUtilisateurs();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['utilisateur_id'])) {
    $id = filter_var($_POST["utilisateur_id"], FILTER_VALIDATE_INT);

    foreach ($utilisateurs as $utilisateur) {
        if ($id !== false && $id === (int) $utilisateur['id']) {
            $_SESSION['utilisateur_id'] = $id;
            break;
        }
    }
}


$action = $_GET["action"] ?? "liste";

$factory = new ActionFactory();
$dispatcher = new Dispatcher($factory);


?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touiteur</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Notre CSS personnalisé -->
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <main class="container-xl">
        <div class="row min-vh-100">

            <!-- Navigation gauche -->
            <aside class="col-md-3 col-lg-3 border-end p-3">
                <h2 class="fw-bold mb-4">Touiteur</h2>
                <form method="POST" class="mb-4">
                    <label for="utilisateur" class="form-label">
                        Utilisateur courant
                    </label>

                    <select name="utilisateur_id" id="utilisateur"
                        class="form-select">
                        <?php foreach ($utilisateurs as $utilisateur): ?>
                            <option
                                value="<?= (int) $utilisateur['id'] ?>"
                                <?= (int) ($_SESSION['utilisateur_id'] ?? 0) === (int) $utilisateur['id']
                                    ? 'selected'
                                    : '' ?>>
                                <?= htmlspecialchars($utilisateur['pseudo'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-primary mt-2">
                        Sélectionner
                    </button>
                </form>
                <nav class="touiteur-nav d-flex flex-column gap-2">
                    <a href="?action=liste" class="nav-link-touiteur">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Accueil</span>
                    </a>

                    <a href="?action=mur" class="nav-link-touiteur">
                        <i class="bi bi-people-fill"></i>
                        <span>Mon mur</span>
                    </a>

                    <a href="?action=tag&tag=PHP" class="nav-link-touiteur">
                        <i class="bi bi-search"></i>
                        <span>Explorer</span>
                    </a>

                    <a href="?action=publication" class="btn-publier mt-3">
                        Publier
                    </a>
                </nav>
            </aside>

            <!-- Fil central -->
            <section class="col-12 col-md-9 col-lg-6 border-end p-3">
                <h4 class="mb-4">Accueil</h4>

                <?php
                echo $dispatcher->run($action);
                ?>
            </section>

            <!-- Tendances droite -->
            <aside class="d-none d-lg-block col-lg-3 p-3">
                <h5>Tendances</h5>
                <p class="text-secondary">#Touiteur</p>
                <p class="text-secondary">#PHP</p>
                <p class="text-secondary">#DevWeb</p>
            </aside>

        </div>
    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="/js/publication.js" defer></script>
</body>

</html>