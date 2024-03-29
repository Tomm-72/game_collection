<?php

function createGame($nom,$editeur,$date,$desc,$url_img,$url_site,$playstation,$xbox,$nintendo,$pc,$id_user){
    global $db;

    $db_query_create_game = $db->prepare('INSERT INTO jeu(`nom_jeu`,`editeur_jeu`,`date_jeu`,`desc_jeu`,`url_img_jeu`,`url_site_jeu`) VALUES(:nom,:editeur,:date,:desc,:url_img,:url_site)');

    $db_query_create_game->execute([
        'nom' => $nom,
        'editeur' => $editeur,
        'date' => $date,
        'desc' => $desc,
        'url_img' => $url_img,
        'url_site' => $url_site
    ]); 

    $id_game = getIdGame($nom,$editeur,$date,$desc,$url_img,$url_site);

    if(isset($playstation)){
        createDisponibilite($id_game,'Playstation');
    };

    if(isset($xbox)){
        createDisponibilite($id_game,'Xbox');
    };

    if(isset($nintendo)){
        createDisponibilite($id_game,'Nintendo');
    };

    if(isset($pc)){
        createDisponibilite($id_game,'PC');
    };

    addGameInLibrary($id_user ,$id_game, null);
}

function createDisponibilite($id,$plateforme){
    global $db;

    $db_query_create_disponibilite = $db->prepare('INSERT INTO disponibilité(`id_jeu`,`nom_plateforme`) VALUES(:id,:plateforme)');
    
    $db_query_create_disponibilite->execute([
        'id' => $id,
        'plateforme' => $plateforme
    ]);
}

function getIdGame($nom,$editeur,$date,$desc,$url_img,$url_site){
    global $db;

    $db_query_id = $db->prepare('SELECT id_jeu FROM jeu WHERE nom_jeu = :nom AND editeur_jeu = :editeur AND date_jeu = :date AND desc_jeu = :desc AND url_img_jeu = :url_img AND url_site_jeu = :url_site');

    $db_query_id->execute([
            'nom' => $nom,
            'editeur' => $editeur,
            'date' => $date,
            'desc' => $desc,
            'url_img' => $url_img,
            'url_site' => $url_site
    ]);

    $id = $db_query_id->fetch(PDO::FETCH_COLUMN);

    return $id;
}
function getGames(){
    global $db;
    $db_query_games = $db->prepare('SELECT * FROM jeu');
    $db_query_games->execute();
    $games = $db_query_games->fetchAll(PDO::FETCH_ASSOC);
    return $games;
}
function getGamesRank(){
    global $db;
    $db_query_games = $db->prepare('SELECT jeu.nom_jeu,utilisateur.id_utilisateur, utilisateur.nom_utilisateur, utilisateur.prenom_utilisateur, bibliotheque.nb_heure_jeu FROM jeu INNER JOIN bibliotheque ON bibliotheque.id_jeu=jeu.id_jeu INNER JOIN utilisateur ON bibliotheque.id_utilisateur=utilisateur.id_utilisateur ORDER BY bibliotheque.nb_heure_jeu DESC');
    $db_query_games->execute();
    $gamesRank = $db_query_games->fetchAll(PDO::FETCH_ASSOC);
    return $gamesRank;
}
function getGamesWithFilter($filter){
    global $db;
    $db_query_games = $db->prepare('SELECT * FROM jeu WHERE LOWER(nom_jeu) like LOWER(:nom);');
    $db_query_games->execute([
        'nom' => '%' . $filter . '%'
    ]);
    $games = $db_query_games->fetchAll(PDO::FETCH_ASSOC);
    return $games;
}
function getGamesWithFilterAdd($filter){
    global $db;
    $db_query_games = $db->prepare('SELECT * FROM jeu WHERE LOWER(nom_jeu) like LOWER(:nom);');
    $db_query_games->execute([
        'nom' => $filter
    ]);
    $games = $db_query_games->fetchAll(PDO::FETCH_ASSOC);
    return $games;
}
?>