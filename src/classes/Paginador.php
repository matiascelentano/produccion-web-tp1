<?php
class Paginador {
    private int $totalItems;
    private int $perPage;
    private int $currentPage;
    private int $totalPages;
    private string $baseUrl;
    private array $query;

    public function __construct(int $totalItems, int $currentPage = 1, int $perPage = 10, string $baseUrl = '', array $query = []) {
        $this->totalItems = max(0, $totalItems);
        $this->perPage = max(1, $perPage);
        $this->totalPages = max(1, (int)ceil($this->totalItems / $this->perPage));
        $this->currentPage = min(max(1, $currentPage), $this->totalPages);
        $this->baseUrl = $baseUrl !== '' ? $baseUrl : $_SERVER['PHP_SELF'];
        $this->query = $query;
    }

    public function getLimit(): int {
        return $this->perPage;
    }

    public function getOffset(): int {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getCurrentPage(): int {
        return $this->currentPage;
    }

    public function getTotalPages(): int {
        return $this->totalPages;
    }

    public function getPageUrl(int $page): string {
        $params = $this->query;
        $params['page'] = $page;
        $params = array_filter($params, static fn($value) => $value !== '' && $value !== null);
        return $this->baseUrl . '?' . http_build_query($params);
    }

    private function renderPageItem(int $page): string {
        $activeClass = $page === $this->currentPage ? ' active' : '';
        return '<li class="page-item' . $activeClass . '"><a class="page-link" href="' . htmlspecialchars($this->getPageUrl($page)) . '">' . $page . '</a></li>';
    }

    public function render(): string {
        if ($this->totalPages <= 1) {
            return '';
        }

        $html = '<nav aria-label="Paginación"><ul class="pagination justify-content-center">';

        $previousClass = $this->currentPage <= 1 ? ' disabled' : '';
        $html .= '<li class="page-item' . $previousClass . '">';
        $html .= '<a class="page-link" href="' . htmlspecialchars($this->getPageUrl(max(1, $this->currentPage - 1))) . '">Anterior</a>';
        $html .= '</li>';

        if ($this->totalPages <= 7) {
            for ($i = 1; $i <= $this->totalPages; $i++) {
                $activeClass = $i === $this->currentPage ? ' active' : '';
                $html .= '<li class="page-item' . $activeClass . '">';
                $html .= '<a class="page-link" href="' . htmlspecialchars($this->getPageUrl($i)) . '">' . $i . '</a>';
                $html .= '</li>';
            }
        } else {
            $html .= $this->renderPageItem(1);

            $start = max(2, $this->currentPage - 2);
            $end = min($this->totalPages - 1, $this->currentPage + 2);

            if ($start > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
            }

            for ($i = $start; $i <= $end; $i++) {
                $html .= $this->renderPageItem($i);
            }

            if ($end < $this->totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
            }

            $html .= $this->renderPageItem($this->totalPages);
        }

        $nextClass = $this->currentPage >= $this->totalPages ? ' disabled' : '';
        $html .= '<li class="page-item' . $nextClass . '">';
        $html .= '<a class="page-link" href="' . htmlspecialchars($this->getPageUrl(min($this->totalPages, $this->currentPage + 1))) . '">Siguiente</a>';
        $html .= '</li>';

        $html .= '</ul></nav>';

        return $html;
    }
}
