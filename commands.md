scoop install symfony-cli


symfony check:requirements

composer -v
composer -V // moins verbeux

composer self-update

symfony new my_project_directory --version="7.4.*" --webapp


// install ssl
symfony server:ca:install

// disable ssl
// symfony serve --no-tls

// lance le server de symfony
symfony serve


// .env
DATABASE_URL="mysql://root:@127.0.0.1:3306/maboutique"
DATABASE_URL="mysql://root:@127.0.0.1:3306/maboutique?serverVersion=8.0.32&charset=utf8mb4"

APP_ENV=dev # pour mode dev ou prod


symfony console // fonctionne uniquement sur windows avec synfony-cli

// cree la base de donnees
symfony console doctrine:database:create

// creer un controller
symfony console make:controller

// ajout de bootstrap

symfony console importmap:require bootstrap

// app.js
// les chemins sont dans le importmap.php
import './styles/css/app.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

// create scss folder in style/
// create css folder in style/


symfony console importmap:require @fortawesome/fontawesome-free/css/all.css@7.2.0
