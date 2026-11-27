
<?php
require_once __DIR__ . '/BaseModel.php';

class JeuCalendrier extends BaseModel
{

    /**
     * Récupérer les questions destinées au public.
     * La bonne réponse ne doit jamais être envoyée au navigateur.
     */
    public function getQuestionsPubliques(): array
    {
        $sql = "SELECT
                    id, jour, date_jeu, cadeau, question,
                    choix_a, choix_b, choix_c, est_grand_lot, statut
                FROM jeu_questions
                WHERE statut = 'publie'
                ORDER BY jour ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer toutes les questions pour l'administration.
     */
    public function getToutesLesQuestions(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM jeu_questions ORDER BY jour ASC"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Enregistrer une participation.
     * La bonne réponse est vérifiée côté serveur.
     */
    public function participer(array $data): array
    {
        date_default_timezone_set('Africa/Abidjan');

        $maintenant = new DateTimeImmutable('now');
        $dateDuJour = $maintenant->format('Y-m-d');
        $heure = $maintenant->format('H:i:s');

        if ($heure < '12:00:00') {
            throw new RuntimeException(
                "Les participations ouvrent à 12h."
            );
        }

        $stmt = $this->pdo->prepare(
            "SELECT id, bonne_reponse
             FROM jeu_questions
             WHERE date_jeu = :date_jeu
             AND statut = 'publie'
             LIMIT 1"
        );

        $stmt->execute([':date_jeu' => $dateDuJour]);
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$question) {
            throw new RuntimeException(
                "Aucune participation n'est ouverte aujourd'hui."
            );
        }

        $reponse = strtoupper(trim($data['reponse']));

        if (!in_array($reponse, ['A', 'B', 'C'], true)) {
            throw new InvalidArgumentException(
                "La réponse choisie est invalide."
            );
        }

        $estCorrecte = $reponse === $question['bonne_reponse'];

        $insert = $this->pdo->prepare(
            "INSERT INTO jeu_participants
            (question_id, nom, prenoms, whatsapp, commune, reponse, est_correcte)
            VALUES
            (:question_id, :nom, :prenoms, :whatsapp, :commune, :reponse, :est_correcte)"
        );

        $insert->execute([
            ':question_id' => $question['id'],
            ':nom' => trim($data['nom']),
            ':prenoms' => trim($data['prenoms']),
            ':whatsapp' => trim($data['whatsapp']),
            ':commune' => trim($data['commune']),
            ':reponse' => $reponse,
            ':est_correcte' => $estCorrecte ? 1 : 0
        ]);

        return [
            'message' => 'Participation enregistrée avec succès.'
        ];
    }

        public function enregistrerQuestion(array $data): array
{
    $sql = "INSERT INTO jeu_questions (
                jour,
                date_jeu,
                cadeau,
                question,
                choix_a,
                choix_b,
                choix_c,
                bonne_reponse,
                est_grand_lot,
                statut
            ) VALUES (
                :jour,
                :date_jeu,
                :cadeau,
                :question,
                :choix_a,
                :choix_b,
                :choix_c,
                :bonne_reponse,
                :est_grand_lot,
                :statut
            )";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':jour' => (int) $data['jour'],
        ':date_jeu' => $data['date_jeu'],
        ':cadeau' => trim($data['cadeau']),
        ':question' => trim($data['question']),
        ':choix_a' => trim($data['choix_a']),
        ':choix_b' => trim($data['choix_b']),
        ':choix_c' => trim($data['choix_c']),
        ':bonne_reponse' => strtoupper($data['bonne_reponse']),
        ':est_grand_lot' => !empty($data['est_grand_lot']) ? 1 : 0,
        ':statut' => $data['statut']
    ]);

    return [
        'message' => 'Question enregistrée avec succès.',
        'id' => (int) $this->pdo->lastInsertId()
    ];
}


public function modifierQuestion(int $id, array $data): array
{
    $sql = "UPDATE jeu_questions SET
                jour = :jour,
                date_jeu = :date_jeu,
                cadeau = :cadeau,
                question = :question,
                choix_a = :choix_a,
                choix_b = :choix_b,
                choix_c = :choix_c,
                bonne_reponse = :bonne_reponse,
                est_grand_lot = :est_grand_lot,
                statut = :statut
            WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':jour' => (int) $data['jour'],
        ':date_jeu' => $data['date_jeu'],
        ':cadeau' => trim($data['cadeau']),
        ':question' => trim($data['question']),
        ':choix_a' => trim($data['choix_a']),
        ':choix_b' => trim($data['choix_b']),
        ':choix_c' => trim($data['choix_c']),
        ':bonne_reponse' => strtoupper($data['bonne_reponse']),
        ':est_grand_lot' => !empty($data['est_grand_lot']) ? 1 : 0,
        ':statut' => $data['statut'],
        ':id' => $id
    ]);

    return [
        'message' => 'Question modifiée avec succès.'
    ];
}


public function supprimerQuestion(int $id): array
{
    // Vérifier que la question existe
    $verification = $this->pdo->prepare(
        "SELECT id FROM jeu_questions WHERE id = :id"
    );

    $verification->execute([':id' => $id]);

    if (!$verification->fetch(PDO::FETCH_ASSOC)) {
        throw new RuntimeException("Cette question n'existe pas.");
    }

    // Vérifier si des personnes ont déjà participé
    $participants = $this->pdo->prepare(
        "SELECT COUNT(*) FROM jeu_participants
         WHERE question_id = :id"
    );

    $participants->execute([':id' => $id]);

    if ((int) $participants->fetchColumn() > 0) {
        throw new RuntimeException(
            "Impossible de supprimer cette question : des participants y sont déjà associés."
        );
    }

    // Supprimer la question
    $suppression = $this->pdo->prepare(
        "DELETE FROM jeu_questions WHERE id = :id"
    );

    $suppression->execute([':id' => $id]);

    return [
        'message' => 'Question supprimée avec succès.'
    ];
}


public function getTousLesParticipants(?int $questionId = null): array
{
    $sql = "
        SELECT
            p.id,
            p.nom,
            p.prenoms,
            p.whatsapp,
            p.commune,
            p.reponse,
            p.est_correcte,
            p.date_participation,
            q.id AS question_id,
            q.jour,
            q.date_jeu,
            q.cadeau
        FROM jeu_participants p
        INNER JOIN jeu_questions q
            ON p.question_id = q.id
    ";

    if ($questionId !== null) {
        $sql .= " WHERE q.id = :question_id";
    }

    $sql .= " ORDER BY q.jour DESC, p.date_participation DESC";

    $requete = $this->pdo->prepare($sql);

    if ($questionId !== null) {
        $requete->bindValue(':question_id', $questionId, PDO::PARAM_INT);
    }

    $requete->execute();

    return $requete->fetchAll(PDO::FETCH_ASSOC);
}


public function enregistrerGagnant(int $questionId, int $participantId): array
{
    // Vérifier que le participant existe et a donné la bonne réponse
    $stmt = $this->pdo->prepare(
        "SELECT id
         FROM jeu_participants
         WHERE id = :participant_id
         AND question_id = :question_id
         AND est_correcte = 1"
    );

    $stmt->execute([
        ':participant_id' => $participantId,
        ':question_id' => $questionId
    ]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        throw new RuntimeException(
            "Ce participant n'est pas éligible pour ce tirage."
        );
    }

    // Vérifier qu'il n'a pas déjà été tiré pour ce jour
    $verification = $this->pdo->prepare(
        "SELECT id
         FROM jeu_gagnants
         WHERE question_id = :question_id
         AND participant_id = :participant_id"
    );

    $verification->execute([
        ':question_id' => $questionId,
        ':participant_id' => $participantId
    ]);

    if ($verification->fetch(PDO::FETCH_ASSOC)) {
        throw new RuntimeException(
            "Ce participant a déjà été tiré pour ce jour."
        );
    }

    // Enregistrer le gagnant
    $insert = $this->pdo->prepare(
        "INSERT INTO jeu_gagnants
         (question_id, participant_id, date_tirage, statut)
         VALUES (:question_id, :participant_id, NOW(), 'en_attente')"
    );

    $insert->execute([
        ':question_id' => $questionId,
        ':participant_id' => $participantId
    ]);

    return [
        'message' => 'Gagnant enregistré avec succès.',
        'id' => (int) $this->pdo->lastInsertId()
    ];
}

public function getTousLesGagnants(): array
{
    $stmt = $this->pdo->prepare(
        "SELECT
            g.id,
            g.question_id,
            g.participant_id,
            g.date_tirage,
            g.statut,
            g.observation,
            p.nom,
            p.prenoms,
            p.whatsapp,
            p.commune,
            q.jour,
            q.cadeau
         FROM jeu_gagnants g
         INNER JOIN jeu_participants p ON p.id = g.participant_id
         INNER JOIN jeu_questions q ON q.id = g.question_id
         ORDER BY q.jour DESC, g.date_tirage DESC"
    );

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function modifierStatutGagnant(
    int $id,
    string $statut,
    ?string $observation = null
): array {
    $statutsAutorises = [
        'en_attente',
        'confirme',
        'publie',
        'annule'
    ];

    if (!in_array($statut, $statutsAutorises, true)) {
        throw new InvalidArgumentException("Statut invalide.");
    }

    $stmt = $this->pdo->prepare(
        "UPDATE jeu_gagnants
         SET statut = :statut,
             observation = :observation
         WHERE id = :id"
    );

    $stmt->execute([
        ':statut' => $statut,
        ':observation' => $observation,
        ':id' => $id
    ]);

    if ($stmt->rowCount() === 0) {
        $check = $this->pdo->prepare(
            "SELECT id FROM jeu_gagnants WHERE id = :id"
        );
        $check->execute([':id' => $id]);

        if (!$check->fetch(PDO::FETCH_ASSOC)) {
            throw new RuntimeException("Gagnant introuvable.");
        }
    }

    return ['message' => 'Statut du gagnant mis à jour.'];
}



}
