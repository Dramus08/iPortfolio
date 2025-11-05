<?php
namespace Utils;

/**
 * Classe utilitaire pour le traitement et la mise en forme des textes.
 * Elle centralise les conversions entre HTML et texte brut,
 * la limitation du contenu, et la sécurisation des chaînes.
 *
 * Usage :
 *   TextHelper::sanitizeInput($text);
 *   TextHelper::nl2brSafe($text);
 *   TextHelper::br2nl($text);
 *   TextHelper::limitWords($text, 30);
 *   TextHelper::formatForTextarea($text);
 */
class TextHelper
{
    /**
     * Nettoie le texte pour éviter les injections XSS
     * (utile avant insertion ou affichage dans des formulaires).
     */
    public static function sanitizeInput(?string $text): string
    {
        if ($text === null) return '';
        return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Convertit les retours à la ligne (\n) en balises <br> pour affichage HTML.
     * Utiliser avant de sauvegarder ou d’afficher un texte en HTML.
     */
    public static function nl2brSafe(?string $text): string
    {
        if ($text === null) return '';
        return nl2br(self::sanitizeInput($text));
    }

    /**
     * Convertit les balises <br> en retours à la ligne (\n)
     * — utile pour recharger un texte dans un <textarea> par exemple.
     */
    public static function br2nl(?string $text): string
    {
        if ($text === null) return '';
        return str_replace(['<br>', '<br/>', '<br />'], "\n", $text);
    }

    /**
     * Supprime toutes les balises HTML sauf celles autorisées.
     * (exemple : autoriser <b>, <i>, <u>)
     */
    public static function stripTags(?string $text, array $allowed = []): string
    {
        if ($text === null) return '';
        $allowedTags = !empty($allowed) ? '<' . implode('><', $allowed) . '>' : '';
        return strip_tags($text, $allowedTags);
    }

    /**
     * Limite un texte à un certain nombre de caractères.
     * Coupe proprement sans briser les mots.
     */
    public static function limitChars(?string $text, int $limit = 100, string $suffix = '...'): string
    {
        if ($text === null) return '';
        if (strlen($text) <= $limit) return $text;

        $cut = substr($text, 0, $limit);
        return substr($cut, 0, strrpos($cut, ' ')) . $suffix;
    }

    /**
     * Limite un texte à un certain nombre de mots.
     */
    public static function limitWords(?string $text, int $limit = 30, string $suffix = '...'): string
    {
        if ($text === null) return '';
        $words = preg_split('/\s+/', trim($text));
        if (count($words) <= $limit) return $text;
        return implode(' ', array_slice($words, 0, $limit)) . $suffix;
    }

    /**
     * Prépare le texte pour affichage dans un <textarea>.
     * (Enlève les balises <br> et restaure les sauts de ligne)
     */
    public static function formatForTextarea(?string $text): string
    {
        if ($text === null) return '';
        return self::br2nl(htmlspecialchars_decode($text, ENT_QUOTES));
    }

    /**
     * Formate un texte long en HTML avec paragraphes automatiques.
     * (Similaire à la fonction wpautop de WordPress)
     */
    public static function autoParagraph(?string $text): string
    {
        if ($text === null) return '';
        $text = trim($text);
        if ($text === '') return '';

        // Convertir les doubles retours à la ligne en paragraphes
        $paragraphs = preg_split('/\n\s*\n/', $text);
        $html = '';
        foreach ($paragraphs as $p) {
            $p = nl2br(trim($p));
            $html .= "<p>$p</p>\n";
        }
        return $html;
    }
}
