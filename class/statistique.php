<?php

class Statistics {
    private $pdo;
    private $cours_id;
    private $nombre_etudiants_inscrits;
    private $nombre_de_cours;
    private $top_3_enseignants;

    // Constructeur pour initialiser les propriétés
    public function __construct($pdo, $cours_id = null) {
        $this->pdo = $pdo;
        $this->cours_id = $cours_id;
        $this->nombre_etudiants_inscrits = 0;
        $this->nombre_de_cours = 0;
        $this->top_3_enseignants = '';
    }

    // Ajouter ou mettre à jour les statistiques d'un cours
    public function addStatistics() {
        if ($this->cours_id === null) {
            return false; // Vérification de la propriété cours_id
        }

        // Nombre d'étudiants inscrits à ce cours
        $stmt = $this->pdo->prepare('
            SELECT COUNT(*) AS total_etudiants 
            FROM Enrollment 
            WHERE cours_id = ?
        ');
        $stmt->execute([$this->cours_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->nombre_etudiants_inscrits = $result['total_etudiants'];

        // Nombre de cours (total)
        $stmt = $this->pdo->query('SELECT COUNT(*) AS total_cours FROM Cours');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->nombre_de_cours = $result['total_cours'];

        // Top 3 enseignants avec le plus d'étudiants inscrits
        $stmt = $this->pdo->query('
            SELECT enseignant_id, COUNT(*) AS total_inscriptions 
            FROM Enrollment 
            GROUP BY enseignant_id 
            ORDER BY total_inscriptions DESC 
            LIMIT 3
        ');
        $enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->top_3_enseignants = implode(', ', array_map(function($enseignant) {
            return $enseignant['enseignant_id'];
        }, $enseignants));

        // Insérer ou mettre à jour les statistiques
        $stmt = $this->pdo->prepare('
            INSERT INTO Statistiques (cours_id, nombre_etudiants_inscrits, nombre_de_cours, top_3_enseignants)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                nombre_etudiants_inscrits = VALUES(nombre_etudiants_inscrits),
                nombre_de_cours = VALUES(nombre_de_cours),
                top_3_enseignants = VALUES(top_3_enseignants)
        ');
        return $stmt->execute([
            $this->cours_id,
            $this->nombre_etudiants_inscrits,
            $this->nombre_de_cours,
            $this->top_3_enseignants
        ]);
    }

    // Récupérer les statistiques pour un cours spécifique
    public function getStatisticsByCourse() {
        if ($this->cours_id === null) {
            return []; // Vérification de la propriété cours_id
        }

        $stmt = $this->pdo->prepare('
            SELECT * 
            FROM Statistiques 
            WHERE cours_id = ?
        ');
        $stmt->execute([$this->cours_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer les statistiques globales
    public function getGlobalStatistics() {
        $stmt = $this->pdo->query('
            SELECT COUNT(*) AS total_courses FROM Cours
        ');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_courses = $result['total_courses'];

        $stmt = $this->pdo->query('
            SELECT COUNT(DISTINCT etudiant_id) AS total_students FROM Enrollment
        ');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_students = $result['total_students'];

        return [
            'total_courses' => $total_courses,
            'total_students' => $total_students,
        ];
    }

    // Définir l'ID du cours pour ajouter les statistiques
    public function setCourseId($cours_id) {
        $this->cours_id = $cours_id;
    }
}

?>
