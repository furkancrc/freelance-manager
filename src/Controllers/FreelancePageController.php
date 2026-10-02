<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AbstractPageController;
use App\Core\Security;
use App\Models\Freelance;
use App\Models\Mission;
use App\Models\Review;

final class FreelancePageController extends AbstractPageController
{
    private const AVAILABILITIES = ["available", "busy"];

    public function __construct(
        private Freelance $freelances,
        private Review $reviews,
        private Mission $missions,
    ) {}

    public function index(): void
    {
        Security::requireRole(["admin", "manager"]);
        $user = Security::currentUser();

        $filters = [
            "q" => $this->query("q"),
            "availability" => $this->query("availability"),
            "location" => $this->query("location"),
            "min_rate" => $this->query("min_rate"),
            "max_rate" => $this->query("max_rate"),
        ];

        $total = $this->freelances->count($filters);
        $pages = $this->pageCount($total);
        $page = min($this->currentPage(), $pages);
        $freelances = $this->freelances->search(
            $filters,
            self::PER_PAGE,
            ($page - 1) * self::PER_PAGE,
        );
        $availabilities = self::AVAILABILITIES;

        require __DIR__ . "/../Views/freelance/index.php";
    }

    public function show(int $id): void
    {
        Security::requireRole(["admin", "manager"]);
        $user = Security::currentUser();

        $freelance = $this->freelances->find($id);
        if ($freelance === null) {
            $this->notFound();
        }

        $reviews = $this->reviews->listForFreelance($id);
        $average =
            $reviews === []
                ? null
                : array_sum(array_column($reviews, "rating")) / count($reviews);

        $closedMissions =
            $user["role"] === "manager"
                ? $this->missions->search([
                    "status" => "closed",
                    "manager_user_id" => $user["id"],
                ])
                : [];

        require __DIR__ . "/../Views/freelance/show.php";
    }

    public function create(): void
    {
        Security::requireRole(["admin"]);

        $freelance = ["availability" => "available"];
        $availabilities = self::AVAILABILITIES;
        $isProfile = false;

        require __DIR__ . "/../Views/freelance/form.php";
    }

    public function edit(int $id): void
    {
        Security::requireRole(["admin"]);

        $freelance = $this->freelances->find($id);
        if ($freelance === null) {
            $this->notFound();
        }

        $availabilities = self::AVAILABILITIES;
        $isProfile = false;

        require __DIR__ . "/../Views/freelance/form.php";
    }
}
