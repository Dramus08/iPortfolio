<?php
namespace Utils ;

use DateTime;
use Exception;

/**
 * Classe utilitaire pour la gestion et le formatage des dates.
 * Elle permet de valider, convertir et manipuler les dates facilement.
 *
 * Exemple :
 *   DateHelper::now();                          // 2025-10-23 02:45:12
 *   DateHelper::format('2025-10-23', 'd/m/Y'); // 23/10/2025
 *   DateHelper::diffInDays('2025-10-23', '2025-11-01'); // 9
 */
class DateHelper
{
    /**
     * Retourne la date/heure actuelle au format voulu.
     */
    public static function now(?string $format = 'Y-m-d H:i:s'): string
    {
        return date($format);
    }

    /**
     * Valide si une chaîne est une date correcte selon un format.
     */
    public static function isValid(?string $date, string $format = 'Y-m-d'): bool
    {
        if (!$date) return false;
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Convertit une date d’un format à un autre.
     */
    public static function format(?string $date, string $toFormat = 'd/m/Y', string $fromFormat = 'Y-m-d'): ?string
    {
        if (!$date || !self::isValid($date, $fromFormat)) return null;
        $d = DateTime::createFromFormat($fromFormat, $date);
        return $d ? $d->format($toFormat) : null;
    }

    /**
     * Transforme un texte de date en objet DateTime.
     */
    public static function toDateTime(?string $date, string $format = 'Y-m-d H:i:s'): ?DateTime
    {
        if (!$date) return null;
        $dt = DateTime::createFromFormat($format, $date);
        return $dt ?: new DateTime($date);
    }

    /**
     * Retourne la différence en jours entre deux dates.
     */
    public static function diffInDays(string $date1, string $date2, string $format = 'Y-m-d'): int
    {
        $d1 = self::toDateTime($date1, $format);
        $d2 = self::toDateTime($date2, $format);
        return abs($d1->diff($d2)->days);
    }

    /**
     * Ajoute un intervalle (ex: +5 days, +1 month) à une date donnée.
     */
    public static function add(string $date, string $interval): ?string
    {
        $dt = new DateTime($date);
        $dt->modify($interval);
        return $dt->format('Y-m-d H:i:s');
    }

    /**
     * Soustrait un intervalle (ex: -7 days) à une date donnée.
     */
    public static function subtract(string $date, string $interval): ?string
    {
        return self::add($date, $interval); // add() gère aussi les intervalles négatifs
    }

    /**
     * Retourne la date dans un format lisible (ex: "23 Octobre 2025 à 14h20")
     */
    public static function humanReadable(?string $date): string
    {
        if (!$date) return '';
        $dt = new DateTime($date);
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        return strftime('%d %B %Y à %Hh%M', $dt->getTimestamp());
    }

    /**
     * Vérifie si une date est dans le futur.
     */
    public static function isFuture(?string $date): bool
    {
        if (!$date) return false;
        $dt = new DateTime($date);
        return $dt > new DateTime();
    }

    /**
     * Vérifie si une date est dans le passé.
     */
    public static function isPast(?string $date): bool
    {
        if (!$date) return false;
        $dt = new DateTime($date);
        return $dt < new DateTime();
    }

    /**
     * Retourne le temps écoulé sous forme humaine ("il y a 5 jours", "hier", etc.)
     */
    public static function timeAgo(?string $datetime): string
    {
        if (!$datetime) return '';
        $dt = new DateTime($datetime);
        $now = new DateTime();
        $diff = $now->diff($dt);

        if ($diff->y > 0) return "il y a {$diff->y} an" . ($diff->y > 1 ? 's' : '');
        if ($diff->m > 0) return "il y a {$diff->m} mois";
        if ($diff->d > 1) return "il y a {$diff->d} jours";
        if ($diff->d === 1) return "hier";
        if ($diff->h > 0) return "il y a {$diff->h} heure" . ($diff->h > 1 ? 's' : '');
        if ($diff->i > 0) return "il y a {$diff->i} minute" . ($diff->i > 1 ? 's' : '');
        return "à l'instant";
    }

    /**
     * Convertit une date vers le format SQL (Y-m-d H:i:s)
     */
    public static function toSqlFormat(string $date): ?string
    {
        try {
            $dt = new DateTime($date);
            return $dt->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Formate une date lisible pour l’utilisateur (ex: 23 Oct 2025)
     */
    public static function toHuman(string $date, string $format = 'd M Y'): ?string
    {
        try {
            $dt = new DateTime($date);
            return $dt->format($format);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Calcule la différence en jours entre deux dates
     */
    public static function diffDays(string $date1, string $date2): ?int
    {
        try {
            $d1 = new DateTime($date1);
            $d2 = new DateTime($date2);
            return $d1->diff($d2)->days;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Calcule l’âge à partir d’une date de naissance
     */
    public static function getAge(string $birthDate): ?int
    {
        try {
            $birth = new DateTime($birthDate);
            $today = new DateTime();
            return $today->diff($birth)->y;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Ajoute ou soustrait des jours à une date
     */
    public static function addDays(string $date, int $days): ?string
    {
        try {
            $dt = new DateTime($date);
            $dt->modify(($days >= 0 ? '+' : '') . "{$days} days");
            return $dt->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }


}
