<?php

class Wiki {

    private string $baseUrl = 'https://ru.wikipedia.org/w/api.php';     // URL API Википедии
    private mixed $startPage;                                           // стартовая страница
    private mixed $finishPage;                                          // финишная страница
    private mixed $availablePages;                                      // доступные страницы

    private int $maxLinksInPage = 05;                                   // настройка - максимальное кол-во ссылок

    // конструктор
    public function __construct() {
        $this->startPage = $this->getRandomPage();
        $this->availablePages = $this->getLinksFromPage($this->startPage);
        $this->finishPage = $this->availablePages[array_rand($this->availablePages)]['title'];
    }

    // метод для получения случайной страницы из Википедии
    public function getRandomPage() {
        $params = [
            'action' => 'query',
            'generator' => 'random',
            'grnnamespace' => '0',
            'prop' => 'info',
            'inprop' => 'url',
            'format' => 'json',
            'formatversion' => '2',
            'grnlimit' => '1'
        ];

        $url = $this->baseUrl . '?' . http_build_query($params);
        $response = json_decode(file_get_contents($url), true);

        return $response['query']['pages'][0]['title'];
    }

    // метод для получения стартовой страницы
    public function getStartPage() {
        return $this->startPage;
    }

    // метод для получения финишной страницы
    public function getFinishPage() {
        return $this->finishPage;
    }

    // метод для получения доступных страниц
    public function getAvailablePages() {
        return $this->availablePages;
    }

    // метод получения нескольких слуучайных страниц
    function getRandomPages($numOfPages) {

        // Определяем количество доступных страниц
        $totalPages = count($this->availablePages);

        // Инициализируем результирующий массив
        $result = array();

        // Если количество запрашиваемых страниц превышает количество доступных страниц, возвращаем все доступные страницы
        if ($numOfPages >= $totalPages) {
            foreach ($this->availablePages as $key => $value) {
                $result[] = array(
                    'index' => $key,
                    'title' => $value['title']
                );
            }
            return $result;
        }

        // Получаем случайные элементы из массива доступных страниц
        $randomPages = array_rand($this->availablePages, $numOfPages);

        // Создаем результирующий массив
        foreach ($randomPages as $index) {
            $result[] = array(
                'index' => $index,
                'title' => $this->availablePages[$index]['title']
            );
        }

        return $result;
    }

    // метод удаления страницы из доступных
    public function removePage($index) {
        unset($this->availablePages[$index]);
    }

    // метод для получения списка страниц, на которые можно перейти по ссылкам с текущей страницы
    public function getLinksFromPage($page) {
        $params = [
            'action' => 'query',
            'prop' => 'links',
            'titles' => $page,
            'pllimit' => 'max',
            'format' => 'json',
            'formatversion' => '2'
        ];

        $url = $this->baseUrl . '?' . http_build_query($params);
        $response = json_decode(file_get_contents($url), true);

        $links = $response['query']['pages'][0]['links'];

        if( count($links) > $this->maxLinksInPage ) {
            $result = array(); // Инициализируем результирующий массив
            // Получаем случайные элементы из массива доступных страниц
            $randomPages = array_rand($links, $this->maxLinksInPage);
            // Создаем результирующий массив
            foreach ($randomPages as $index) {
                $result[] = $links[$index];
            }
            return $result;
        } else {
            return $links;
        }
    }
}
