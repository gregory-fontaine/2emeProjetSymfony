<?php

// se connecter à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gros_fil_rouge";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// récupérer les événements depuis la table "cours"
$sql = "SELECT * FROM cours";
$result = $conn->query($sql);

// encoder les événements en JSON
$events = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $event = array(
            'id' => $row['id'],
            'title' => $row['libelle'],
            'start' => $row['date_debut'].$row['heure_debut'],
            'end' => $row['date_fin'].$row['heure_fin'],
            "color"=> "red",
            // 'date_debut' => $row['date_debut'],
            // 'date_fin' => $row['date_fin'],
            // 'day' => $row['date_hebdomadaire'],
            // 'description' => $row['description'],
            // 'max_eleves' => $row['max_eleves']
        );
        array_push($events, $event);
    }
}

// echo json_encode($events);

// sauvegarder les événements dans un fichier JSON
file_put_contents('public/events.json', json_encode($events));



$conn->close();

?>