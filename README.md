api rest permettant la gestion des transactions mobile money
les outils utilisés sont: laravel. la gestion de l'authentification a été gérée avec laravel sanctum. il génère automatiquement un token lors de l'authentification
pour la base de données, j'ai utilisé postgresql car il gère facilement les clés longues par rapport à mysql

pour tester les routes, il faut d'abord lancer le projet grace à la commande php artisan serve, ensuite ouvrir postman et tester les routes graces aux urls

pour que le projet compile sans problème, il faut créer la base de données localement, créer lezs migrations, ensuite configurer le fichier .env (le nom de la bd, l'hote, le nom du user, le password). sans toutefois oublier qu'il faut préalablement avoir node, composer, laravel et laraval sanctum installeés sur le pc

cette api sera connectée avec un frontend react, stylisé avec tailwind css


données tests:
user: name : User,
    numero : 655477203,
    password : secret123,

agent: name : Agent,
        numero : 654177202,
        password :secret123