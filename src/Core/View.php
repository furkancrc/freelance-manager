<?php

declare(strict_types=1);

namespace App\Core;

/** Petites fonctions d'affichage utilisées dans les vues. */
final class View
{
    private const LABELS = [
        "draft" => "Brouillon",
        "open" => "Ouverte",
        "in_progress" => "En cours",
        "closed" => "Terminée",
        "pending" => "En attente",
        "accepted" => "Acceptée",
        "rejected" => "Refusée",
        "available" => "Disponible",
        "busy" => "Occupé",
    ];

    /** Échappe une valeur pour l'afficher dans le HTML (protection XSS). */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ""));
    }

    public static function money(mixed $value): string
    {
        if ($value === null || $value === "") {
            return "—";
        }

        return number_format((float) $value, 0, ",", "\u{00A0}") . "\u{00A0}€";
    }

    public static function date(?string $value): string
    {
        if ($value === null || $value === "") {
            return "—";
        }

        return date("d/m/Y", (int) strtotime($value));
    }

    public static function label(string $value): string
    {
        return self::LABELS[$value] ?? $value;
    }

    public static function tag(string $status): string
    {
        return '<span class="tag tag-' .
            self::e($status) .
            '">' .
            self::e(self::label($status)) .
            "</span>";
    }

    /** @param string[] $values */
    public static function options(array $values, ?string $selected): string
    {
        $html = "";

        foreach ($values as $value) {
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                self::e($value),
                $value === $selected ? " selected" : "",
                self::e(self::label($value)),
            );
        }

        return $html;
    }

    /** URL de la page $page en conservant les filtres de recherche. */
    public static function pageUrl(int $page): string
    {
        $params = array_filter($_GET, "is_string");
        $params["page"] = $page;

        return "?" . http_build_query($params);
    }
}
