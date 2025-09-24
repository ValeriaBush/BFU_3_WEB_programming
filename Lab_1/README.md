Лабораторная работа №1: Nginx + Docker
👩‍💻 Автор
ФИО: Буш Валерия Михайловна
Группа: 3МО-3

📌 Описание задания
Создать веб-сервер в Docker с использованием Nginx и подключить HTML-страницу.
Результат доступен по адресу http://localhost:8080.

⚙️ Как запустить проект
Клонировать репозиторий:

git clone https://github.com/ValeriaBush/BFU_3_WEB_programming/new/main/Lab_1

cd nginx-lab

Запустить контейнеры:
docker-compose up -d --build

Открыть в браузере: http://localhost:7000 📂 Содержимое проекта

docker-compose.yml — описание сервиса Nginx

code/index.html — главная HTML-страница

code/about.html - HTML-страница с небольшой справкой о Docker

screenshots/ — все скриншоты

📸 Скриншоты работы
![Версия Docker](./screenshots/Dockers_version.png)

![Первый запуск](./screenshots/Container_first_run.png)

![Добавление index.html](./screenshots/Added_index_html.png)

![Улучшение index.html](./screenshots/Updated_index_html.png)

![Добавление about.html](./screenshots/Added_about_html.png)

✅ Результат Сервер в Docker успешно запущен, Nginx отдаёт мою HTML-страницу.
