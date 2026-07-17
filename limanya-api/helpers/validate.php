<?php

/**
 * Valide un tableau de données selon des règles simples.
 * Retourne un tableau d'erreurs (vide si tout est valide).
 *
 * Règles supportées par champ : 'required', 'email', 'max:N'
 *
 * Exemple :
 * validate($input, [
 *   'nom' => 'required|max:150',
 *   'email' => 'email|max:200',
 *   'telephone' => 'required|max:50',
 *   'message' => 'required|max:2000',
 * ]);
 */
function validate(array $data, array $rules): array
{
    $errors = [];

    foreach ($rules as $field => $ruleString) {
        $rulesList = explode('|', $ruleString);
        $value = isset($data[$field]) ? trim((string)$data[$field]) : '';

        foreach ($rulesList as $rule) {
            if ($rule === 'required' && $value === '') {
                $errors[$field] = "Le champ \"$field\" est requis.";
                break;
            }

            if ($value === '') {
                continue; // champ optionnel vide : on saute les autres règles
            }

            if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "L'adresse email n'est pas valide.";
                break;
            }

            if (str_starts_with($rule, 'max:')) {
                $max = (int)substr($rule, 4);
                if (strlen($value) > $max) {
                    $errors[$field] = "Le champ \"$field\" ne doit pas dépasser $max caractères.";
                    break;
                }
            }
        }
    }

    return $errors;
}

/**
 * Nettoie une chaîne de texte simple (retire les balises HTML, espaces superflus).
 */
function sanitizeString(?string $value): ?string
{
    if ($value === null) return null;
    return trim(strip_tags($value));
}

/**
 * Applique sanitizeString() à tous les champs texte d'un tableau associatif.
 */
function sanitizeArray(array $data): array
{
    $clean = [];
    foreach ($data as $key => $value) {
        $clean[$key] = is_string($value) ? sanitizeString($value) : $value;
    }
    return $clean;
}
