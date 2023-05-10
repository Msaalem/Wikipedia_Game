<?php

require_once "Wiki.php";

class Player {

    private string $name;                   // имя игрока
    private int $currentMovesCount = 0;     // количество сделанных ходов
    private string $currentPage;            // текущая страница
    private Wiki $wiki;                     // набор страниц wiki
    private bool $finished = false;         // флаг, указывающий на то, достиг ли игрок финишной страницы

    // конструктор
    public function __construct($name) {
        $this->name = $name;
        $this->wiki = new Wiki();
        $this->currentPage = $this->wiki->getStartPage();
    }

    // метод для получения имени игрока
    public function getName(): string
    {
        return $this->name;
    }

    // метод для получения списка страниц
    public function getAvailablePages($count) {
        return $this->wiki->getRandomPages($count);
    }

    // метод для получения количества ходов
    public function getMovesCount(): int
    {
        return $this->currentMovesCount;
    }

    // метод получения текущей страниы
    public function getCurrentPage(): string
    {
        return $this->currentPage;
    }

    // метод для проверки, достиг ли игрок финишной страницы
    public function isFinished(): bool
    {
        return $this->finished;
    }

    // метод для перемещения на новую страницу
    public function goToPage($page) {
        $this->currentMovesCount++;
        $this->currentPage = $page['title'];
        $this->wiki->removePage($page['index']);
        if( $page['title'] == $this->wiki->getFinishPage() ) {
            $this->finishGame();
        }
    }

    // метод для завершения игры
    public function finishGame() {
        $this->finished = true;
    }
}
