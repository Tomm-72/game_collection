<?php

require("models/PDO.php");
require("models/game_model.php");
require("models/library_model.php");
require("models/user_model.php");
require("lib/component/header.php");

$games = getGames();

if (isset($_POST['recherche'])) {
    $games = getGamesWithFilter($_POST['recherche_game']);
}

if (empty($games)) {
    header('Location: add_game');
    exit();
} else {
    if (isset($_POST['ajout'])) {
        $id_game = $_POST['id_game'];
        $id_user = $_SESSION['id_utilisateur'];

        $games_user = getLibraryOfUser($id_user);

        $game_inside = false;

        foreach ($games_user as $game_user) {
            if ($game_user['id_jeu'] == $id_game) {
                $game_inside = true;
                break;
            }
        }

        if (!$game_inside) {
            addGameInLibrary($id_user, $id_game, null);
        }
    }

    require("views/library_page.php");
    exit();
}
