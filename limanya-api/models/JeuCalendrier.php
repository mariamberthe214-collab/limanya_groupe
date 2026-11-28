
<?php
require_once __DIR__ . '/BaseModel.php';

class JeuCalendrier extends BaseModel
{
    private function maintenantAbidjan(): DateTimeImmutable
    {
        $timezone = new DateTimeZone('Africa/Abidjan');
        $mode = getenv('APP_ENV') ?: 'production';
        $instantTest = getenv('CALENDRIER_TEST_NOW');

        if ($mode === 'testing' && is_string($instantTest) && trim($instantTest) !== '') {
            try {
                return (new DateTimeImmutable($instantTest))->setTimezone($timezone);
            } catch (Throwable $e) {
                throw new RuntimeException('La date de simulation du calendrier est invalide.');
            }
        }

        return new DateTimeImmutable('now', $timezone);
    }

    /**
     * Récupérer les questions destinées au public.
     * La bonne réponse ne doit jamais être envoyée au navigateur.
     */
    public function getQuestionsPubliques(): array
    {
        $now = $this->maintenantAbidjan();
        $today = $now->format('Y-m-d');
        $isOpen = $now->format('H:i:s') >= '12:00:00';

        $stmt = $this->pdo->prepare(
            "SELECT id, jour, date_jeu, cadeau, question, choix_a, choix_b,
                    choix_c, est_grand_lot, statut
             FROM jeu_questions
             WHERE statut = 'publie' AND date_jeu <= :today
             ORDER BY date_jeu DESC"
        );
        $stmt->execute([':today' => $today]);
        $past = [];
        $todayQuestion = null;
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $question) {
            if ($question['date_jeu'] === $today) {
                if ($isOpen) $todayQuestion = $question;
            } else {
                $past[] = $question;
            }
        }

        $winnerStmt = $this->pdo->prepare(
            "SELECT q.jour, q.date_jeu, q.cadeau, q.media_cadeau,
                    q.type_media_cadeau, p.nom, p.prenoms
             FROM jeu_gagnants g
             INNER JOIN jeu_questions q ON q.id = g.question_id
             INNER JOIN jeu_participants p ON p.id = g.participant_id
             WHERE g.statut = 'publie' AND q.date_jeu < :today
             ORDER BY q.date_jeu DESC"
        );
        $winnerStmt->execute([':today' => $today]);

        $phase = $now->format('Y-m') === '2026-12'
            ? ($isOpen ? 'ouvert' : 'attente')
            : 'hors_periode';

        return [
            'server_now' => $now->format(DateTimeInterface::ATOM),
            'date_aujourdhui' => $today,
            'phase' => $phase,
            'question_du_jour' => $todayQuestion,
            'questions_passees' => $past,
            'gagnants_publies' => $winnerStmt->fetchAll(PDO::FETCH_ASSOC),
        ];
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
        $maintenant = $this->maintenantAbidjan();
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

        $whatsappNormalise = preg_replace('/\D+/', '', $data['whatsapp']);
        if (str_starts_with($whatsappNormalise, '00')) {
            $whatsappNormalise = substr($whatsappNormalise, 2);
        }
        if (str_starts_with($whatsappNormalise, '225')) {
            $whatsappNormalise = substr($whatsappNormalise, 3);
        }
        if ($whatsappNormalise === '' || strlen($whatsappNormalise) < 8 || strlen($whatsappNormalise) > 15) {
            throw new InvalidArgumentException('Le numéro WhatsApp est invalide.');
        }

        $insert = $this->pdo->prepare(
            "INSERT INTO jeu_participants
            (question_id, nom, prenoms, whatsapp, whatsapp_normalise, commune, reponse, est_correcte, date_participation)
            VALUES
            (:question_id, :nom, :prenoms, :whatsapp, :whatsapp_normalise, :commune, :reponse, :est_correcte, :date_participation)"
        );

        $insert->execute([
            ':question_id' => $question['id'],
            ':nom' => trim($data['nom']),
            ':prenoms' => trim($data['prenoms']),
            ':whatsapp' => trim($data['whatsapp']),
            ':whatsapp_normalise' => $whatsappNormalise,
            ':commune' => trim($data['commune']),
            ':reponse' => $reponse,
            ':est_correcte' => $estCorrecte ? 1 : 0,
            ':date_participation' => $maintenant->format('Y-m-d H:i:s')
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
                media_cadeau,
                type_media_cadeau,
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
                :media_cadeau,
                :type_media_cadeau,
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
        ':media_cadeau' => $data['media_cadeau'] ?? null,
        ':type_media_cadeau' => $data['type_media_cadeau'] ?? null,
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
    $participationsExistantes = $this->pdo->prepare(
        "SELECT COUNT(*) FROM jeu_participants WHERE question_id = :id"
    );
    $participationsExistantes->execute([':id' => $id]);
    if ((int) $participationsExistantes->fetchColumn() > 0) {
        throw new RuntimeException(
            'Cette journée a déjà reçu des participations. Ses questions ne peuvent plus être modifiées.'
        );
    }

    $sql = "UPDATE jeu_questions SET
                jour = :jour,
                date_jeu = :date_jeu,
                cadeau = :cadeau,
                media_cadeau = :media_cadeau,
                type_media_cadeau = :type_media_cadeau,
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
        ':media_cadeau' => $data['media_cadeau'] ?? null,
        ':type_media_cadeau' => $data['type_media_cadeau'] ?? null,
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

public function getParticipantsCorrectsJoursTermines(?int $questionId = null): array
{
    $today = $this->maintenantAbidjan()->format('Y-m-d');
    $sql = "SELECT p.id, p.nom, p.prenoms, p.whatsapp, p.commune, p.reponse,
                   p.date_participation, q.id AS question_id, q.jour,
                   q.date_jeu, q.cadeau
            FROM jeu_participants p
            INNER JOIN jeu_questions q ON q.id = p.question_id
            WHERE p.est_correcte = 1 AND q.date_jeu < :today";
    if ($questionId !== null) $sql .= ' AND q.id = :question_id';
    $sql .= ' ORDER BY q.date_jeu DESC, p.date_participation ASC';
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':today', $today);
    if ($questionId !== null) $stmt->bindValue(':question_id', $questionId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getParticipantsEligiblesTirage(int $questionId): array
{
    $timezone = new DateTimeZone('Africa/Abidjan');
    $maintenant = $this->maintenantAbidjan();

    // Vérifier que la question existe
    $stmt = $this->pdo->prepare("
        SELECT id, date_jeu
        FROM jeu_questions
        WHERE id = :question_id
        LIMIT 1
    ");

    $stmt->execute([
        ':question_id' => $questionId
    ]);

    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$question) {
        throw new RuntimeException("Question introuvable.");
    }

    // Calculer la fin de la journée concernée
    $dateFin = new DateTimeImmutable(
        $question['date_jeu'] . ' 00:00:00',
        $timezone
    );

    $dateFin = $dateFin->modify('+1 day');

    // Interdire le tirage avant minuit
    if ($maintenant < $dateFin) {
        throw new RuntimeException(
            "Le tirage est possible uniquement après la fermeture de la journée."
        );
    }

    // Vérifier qu'aucun gagnant n'a déjà été enregistré
    $verification = $this->pdo->prepare("
        SELECT id
        FROM jeu_gagnants
        WHERE question_id = :question_id
        LIMIT 1
    ");

    $verification->execute([
        ':question_id' => $questionId
    ]);

    if ($verification->fetch(PDO::FETCH_ASSOC)) {
        throw new RuntimeException(
            "Un gagnant existe déjà pour cette journée."
        );
    }

    // Récupérer uniquement les bonnes réponses
    $participants = $this->pdo->prepare("
        SELECT
            id,
            nom,
            prenoms,
            whatsapp,
            commune,
            date_participation
        FROM jeu_participants
        WHERE question_id = :question_id
        AND est_correcte = 1
        ORDER BY id ASC
    ");

    $participants->execute([
        ':question_id' => $questionId
    ]);

    return $participants->fetchAll(PDO::FETCH_ASSOC);
}



public function enregistrerGagnant(int $questionId, int $participantId): array
{   
    $candidats = $this->getParticipantsEligiblesTirage($questionId);
    $eligible = false;
    foreach ($candidats as $candidat) {
        if ((int) $candidat['id'] === $participantId) {
            $eligible = true;
            break;
        }
    }
    if (!$eligible) {
        throw new RuntimeException('Ce participant n’est pas éligible pour cette journée.');
    }

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
         VALUES (:question_id, :participant_id, :date_tirage, 'en_attente')"
    );

    $insert->execute([
        ':question_id' => $questionId,
        ':participant_id' => $participantId,
        ':date_tirage' => $this->maintenantAbidjan()->format('Y-m-d H:i:s')
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

    $current = $this->pdo->prepare("SELECT statut FROM jeu_gagnants WHERE id = :id");
    $current->execute([':id' => $id]);
    $statutActuel = $current->fetchColumn();
    if ($statutActuel === false) {
        throw new RuntimeException('Gagnant introuvable.');
    }
    if ($statut === 'publie' && $statutActuel !== 'confirme') {
        throw new InvalidArgumentException('Confirmez d’abord l’identité du gagnant avant la publication.');
    }
    if ($statut === 'confirme' && $statutActuel !== 'en_attente') {
        throw new InvalidArgumentException('Seul un gagnant en attente peut être confirmé.');
    }
    if ($statutActuel === 'publie' && $statut !== 'publie') {
        throw new InvalidArgumentException('Un gagnant publié ne peut pas être modifié ici.');
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
