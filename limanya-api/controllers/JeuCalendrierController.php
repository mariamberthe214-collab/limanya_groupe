
<?php

require_once __DIR__ . '/../models/JeuCalendrier.php';

class JeuCalendrierController
{


    private JeuCalendrier $model;

    public function __construct()
    {
        $this->model = new JeuCalendrier();
    }

    private function validerMediaCadeau(array &$input): bool
    {
        $url = $input['media_cadeau'] ?? null;
        $type = $input['type_media_cadeau'] ?? null;

        if ($url === null || $url === '') {
            $input['media_cadeau'] = null;
            $input['type_media_cadeau'] = null;
            return true;
        }

        $urlValide = is_string($url) && strlen($url) <= 2048 && (
            (str_starts_with($url, 'https://') && filter_var($url, FILTER_VALIDATE_URL)) ||
            preg_match('#^/uploads/[a-zA-Z0-9._-]+$#', $url)
        );

        if (!$urlValide || !in_array($type, ['image', 'video'], true)) {
            $this->sendJson(['message' => 'Le média du cadeau est invalide.'], 422);
            return false;
        }

        return true;
    }

    // Questions accessibles au public
    public function questionsPubliques()
    {
        header('Cache-Control: no-store, private');
        try {
            $questions = $this->model->getQuestionsPubliques();

            $this->sendJson($questions);
        } catch (Throwable $e) {
            $this->sendJson([
                'message' => 'Impossible de récupérer les questions.'
            ], 500);
        }
    }

    // Questions accessibles à l'administration
    public function toutesLesQuestions()
    {
        try {
            $questions = $this->model->getToutesLesQuestions();

            $this->sendJson($questions);
        } catch (Throwable $e) {
            $this->sendJson([
                'message' => 'Impossible de récupérer les questions.'
            ], 500);
        }
    }

    public function candidatsGagnants(): void
    {
        requireRole(['gestionnaire_contenu']);
        $questionId = filter_var($_GET['question_id'] ?? null, FILTER_VALIDATE_INT);
        if ($questionId === false || $questionId === null || $questionId <= 0) {
            $this->sendJson(['message' => 'Identifiant de question invalide.'], 400);
            return;
        }
        try {
            $this->sendJson($this->model->getParticipantsEligiblesTirage($questionId));
        } catch (RuntimeException $e) {
            $this->sendJson(['message' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->sendJson(['message' => 'Impossible de charger les candidats.'], 500);
        }
    }

    // Enregistrer une participation
    public function participer()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            $this->sendJson([
                'message' => 'Données invalides.'
            ], 400);
            return;
        }

        $champs = ['nom', 'prenoms', 'whatsapp', 'commune', 'reponse'];

        foreach ($champs as $champ) {
            if (
                !isset($input[$champ]) ||
                !is_string($input[$champ]) ||
                trim($input[$champ]) === ''
            ) {
                $this->sendJson([
                    'message' => "Le champ $champ est obligatoire."
                ], 422);
                return;
            }
        }

        if (
            strlen($input['nom']) > 100 ||
            strlen($input['prenoms']) > 150 ||
            strlen($input['whatsapp']) > 30 ||
            strlen($input['commune']) > 100
        ) {
            $this->sendJson([
                'message' => 'Certaines informations sont trop longues.'
            ], 422);
            return;
        }

        try {
            $resultat = $this->model->participer($input);

            $this->sendJson($resultat, 201);

        } catch (InvalidArgumentException $e) {
            $this->sendJson([
                'message' => $e->getMessage()
            ], 422);

        } catch (RuntimeException $e) {
            $this->sendJson([
                'message' => $e->getMessage()
            ], 400);

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->sendJson([
                    'message' => 'Ce numéro WhatsApp a déjà participé à cette journée.'
                ], 409);
                return;
            }

            $this->sendJson([
                'message' => 'Une erreur est survenue lors de la participation.'
            ], 500);

        } catch (Throwable $e) {
            $this->sendJson([
                'message' => 'Une erreur inattendue est survenue.'
            ], 500);
        }
    }

    private function sendJson($data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );
    }

    public function enregistrerQuestion()
   {
    requireRole(['gestionnaire_contenu']);

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        $this->sendJson(['message' => 'Données invalides.'], 400);
        return;
    }

    $champs = [
        'jour', 'date_jeu', 'cadeau', 'question',
        'choix_a', 'choix_b', 'choix_c',
        'bonne_reponse', 'statut'
    ];

    foreach ($champs as $champ) {
        if (!isset($input[$champ]) || trim((string) $input[$champ]) === '') {
            $this->sendJson([
                'message' => "Le champ $champ est obligatoire."
            ], 422);
            return;
        }
    }

    if (!$this->validerMediaCadeau($input)) {
        return;
    }

    if (
        !filter_var($input['jour'], FILTER_VALIDATE_INT) ||
        (int) $input['jour'] < 1 ||
        (int) $input['jour'] > 31
    ) {
        $this->sendJson(['message' => 'Le jour doit être compris entre 1 et 31.'], 422);
        return;
    }

    if (!in_array(strtoupper($input['bonne_reponse']), ['A', 'B', 'C'], true)) {
        $this->sendJson(['message' => 'La bonne réponse doit être A, B ou C.'], 422);
        return;
    }

    if (!in_array($input['statut'], ['brouillon', 'publie'], true)) {
        $this->sendJson(['message' => 'Le statut choisi est invalide.'], 422);
        return;
    }

    $date = DateTime::createFromFormat('!Y-m-d', $input['date_jeu']);

    if (!$date || $date->format('Y-m-d') !== $input['date_jeu']) {
        $this->sendJson(['message' => 'La date est invalide.'], 422);
        return;
    }
    if ($date->format('Y-m') !== '2026-12' || (int) $date->format('j') !== (int) $input['jour']) {
        $this->sendJson(['message' => 'Le jour doit correspondre à la date de décembre 2026.'], 422);
        return;
    }

    try {
        $resultat = $this->model->enregistrerQuestion($input);
        $this->sendJson($resultat, 201);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $this->sendJson([
                'message' => 'Ce jour ou cette date possède déjà une question.'
            ], 409);
            return;
        }

        $this->sendJson(['message' => 'Erreur lors de l’enregistrement.'], 500);
    } catch (Throwable $e) {
        $this->sendJson(['message' => 'Une erreur inattendue est survenue.'], 500);
    }
}


public function modifierQuestion($id)
{
    requireRole(['gestionnaire_contenu']);

    $id = (int) $id;

    if ($id <= 0) {
        $this->sendJson(['message' => 'Identifiant invalide.'], 400);
        return;
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        $this->sendJson(['message' => 'Données invalides.'], 400);
        return;
    }

    $champs = [
        'jour', 'date_jeu', 'cadeau', 'question',
        'choix_a', 'choix_b', 'choix_c',
        'bonne_reponse', 'statut'
    ];

    foreach ($champs as $champ) {
        if (!isset($input[$champ]) || trim((string) $input[$champ]) === '') {
            $this->sendJson([
                'message' => "Le champ $champ est obligatoire."
            ], 422);
            return;
        }
    }

    if (!$this->validerMediaCadeau($input)) {
        return;
    }

    if (
        filter_var($input['jour'], FILTER_VALIDATE_INT) === false ||
        (int) $input['jour'] < 1 ||
        (int) $input['jour'] > 31
    ) {
        $this->sendJson(['message' => 'Le jour doit être compris entre 1 et 31.'], 422);
        return;
    }

    if (!in_array(strtoupper($input['bonne_reponse']), ['A', 'B', 'C'], true)) {
        $this->sendJson(['message' => 'La bonne réponse doit être A, B ou C.'], 422);
        return;
    }

    if (!in_array($input['statut'], ['brouillon', 'publie'], true)) {
        $this->sendJson(['message' => 'Le statut choisi est invalide.'], 422);
        return;
    }

    $date = DateTime::createFromFormat('!Y-m-d', $input['date_jeu']);

    if (!$date || $date->format('Y-m-d') !== $input['date_jeu']) {
        $this->sendJson(['message' => 'La date est invalide.'], 422);
        return;
    }
    if ($date->format('Y-m') !== '2026-12' || (int) $date->format('j') !== (int) $input['jour']) {
        $this->sendJson(['message' => 'Le jour doit correspondre à la date de décembre 2026.'], 422);
        return;
    }

    try {
        $resultat = $this->model->modifierQuestion($id, $input);
        $this->sendJson($resultat);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $this->sendJson([
                'message' => 'Ce jour ou cette date possède déjà une question.'
            ], 409);
            return;
        }

        $this->sendJson(['message' => 'Erreur lors de la modification.'], 500);
    } catch (RuntimeException $e) {
        $this->sendJson(['message' => $e->getMessage()], 409);
    } catch (Throwable $e) {
        $this->sendJson(['message' => 'Une erreur inattendue est survenue.'], 500);
    }
}



public function supprimerQuestion(int $id): void
{
    requireRole(['gestionnaire_contenu']);

    if ($id <= 0) {
        $this->sendJson([
            'message' => 'Identifiant de question invalide.'
        ], 400);
        return;
    }

    try {
        $resultat = $this->model->supprimerQuestion($id);
        $this->sendJson($resultat, 200);

    } catch (RuntimeException $e) {
        $this->sendJson([
            'message' => $e->getMessage()
        ], 409);

    } catch (PDOException $e) {
        error_log($e->getMessage());

        $this->sendJson([
            'message' => 'Une erreur est survenue lors de la suppression.'
        ], 500);
    }
}


public function tousLesParticipants(): void
{
    requireRole(['gestionnaire_contenu']);

    try {
        $questionId = null;

        if (isset($_GET['question_id']) && $_GET['question_id'] !== '') {
            $questionId = filter_var(
                $_GET['question_id'],
                FILTER_VALIDATE_INT
            );

            if ($questionId === false || $questionId <= 0) {
                $this->sendJson([
                    'message' => 'Identifiant de question invalide.'
                ], 400);
                return;
            }
        }

        $participants = $this->model->getTousLesParticipants($questionId);

        $this->sendJson($participants);

    } catch (Throwable $e) {
        error_log($e->getMessage());

        $this->sendJson([
            'message' => 'Impossible de récupérer les participants.'
        ], 500);
    }
}


public function exporterParticipants(): void
{
    requireRole(['gestionnaire_contenu']);

    try {
        $questionId = null;

        if (isset($_GET['question_id']) && $_GET['question_id'] !== '') {
            $questionId = filter_var(
                $_GET['question_id'],
                FILTER_VALIDATE_INT
            );

            if ($questionId === false || $questionId <= 0) {
                $this->sendJson([
                    'message' => 'Identifiant de question invalide.'
                ], 400);
                return;
            }
        }

        $participants = $this->model->getParticipantsCorrectsJoursTermines($questionId);

        $nomFichier = 'participants-calendrier-' .
            date('Ymd-His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header(
            'Content-Disposition: attachment; filename="' .
            $nomFichier . '"'
        );
        header('Cache-Control: no-cache, must-revalidate');

        $sortie = fopen('php://output', 'w');

        if ($sortie === false) {
            throw new RuntimeException("Impossible de créer le fichier CSV.");
        }

        // Permet à Excel de reconnaître les accents
        fwrite($sortie, "\xEF\xBB\xBF");

        fputcsv($sortie, [
            'Nom',
            'Prénoms',
            'WhatsApp',
            'Commune',
            'Jour',
            'Cadeau',
            'Réponse',
            'Résultat',
            'Date de participation'
        ], ';');

        foreach ($participants as $participant) {
            fputcsv($sortie, [
                $participant['nom'],
                $participant['prenoms'],
                $participant['whatsapp'],
                $participant['commune'],
                $participant['jour'],
                $participant['cadeau'],
                $participant['reponse'],
                'Bonne réponse',
                $participant['date_participation']
            ], ';');
        }

        fclose($sortie);
        exit;

        } catch (Throwable $e) {
            error_log($e->getMessage());

            if (!headers_sent()) {
                $this->sendJson([
                    'message' => "Impossible d'exporter les participants."
                ], 500);
            }
        }
    } // Fin de exporterParticipants()


public function effectuerTirage(): void
{
    http_response_code(410);
    $this->sendJson(['message' => 'Le tirage doit être effectué manuellement.'], 410);
}


public function enregistrerGagnant(): void
{
    requireRole(['gestionnaire_contenu']);
    

    try {
        $data = json_decode(file_get_contents('php://input'), true);

        $questionId = (int) ($data['question_id'] ?? 0);
        $participantId = (int) ($data['participant_id'] ?? 0);

        if ($questionId <= 0 || $participantId <= 0) {
            $this->sendJson(['message' => 'Informations invalides.'], 400);
            return;
        }

        $resultat = $this->model->enregistrerGagnant(
            $questionId,
            $participantId
        );

        $this->sendJson($resultat, 201);

    } catch (RuntimeException $e) {
        $this->sendJson(['message' => $e->getMessage()], 400);
    } catch (Throwable $e) {
        error_log($e->getMessage());
        $this->sendJson(['message' => 'Erreur lors de l’enregistrement du gagnant.'], 500);
    }
}

public function tousLesGagnants(): void
{
    

    try {
        $gagnants = $this->model->getTousLesGagnants();
        $this->sendJson($gagnants);
    } catch (Throwable $e) {
        error_log($e->getMessage());
        $this->sendJson(['message' => 'Erreur lors du chargement des gagnants.'], 500);
    }
}

public function modifierStatutGagnant(int $id): void
{
    

    try {
        $data = json_decode(file_get_contents('php://input'), true);

        $statut = $data['statut'] ?? '';
        $observation = $data['observation'] ?? null;

        if (!is_string($statut)) {
            $this->sendJson(['message' => 'Statut invalide.'], 400);
            return;
        }

        $resultat = $this->model->modifierStatutGagnant(
            $id,
            $statut,
            $observation
        );

        $this->sendJson($resultat);

    } catch (InvalidArgumentException $e) {
        $this->sendJson(['message' => $e->getMessage()], 400);
    } catch (RuntimeException $e) {
        $this->sendJson(['message' => $e->getMessage()], 404);
    } catch (Throwable $e) {
        error_log($e->getMessage());
        $this->sendJson(['message' => 'Erreur lors de la mise à jour du gagnant.'], 500);
    }
}


}// Fin de la classe JeuCalendrierController


