# Un chat, genre comme celui de WhatsApp mais en (beaucoup) moins bien

Le but ici était d'utiliser Symfony avec le bundle Mercure pour créer un chat privé, avec un système d'auth et de
pérennisation des messages en DB

Le système est donc composé des briques suivantes :
- Un back Symfony qui va gérer l'auth des users et l'enregistrement des messages et conversations en DB
- Un front en React qui prend la charge d'être un terminal de gestion de messages
- Un protocole Mercure pour gérer les notifications instantanées (messages)

Pour lancer le projet, il faut:
```
docker-compose up -d
cd frontend
npm install
npm run start
```

Puis, depuis l'intérieur du container Symfony
```
shell
cd /var/www/symfony_project
composer install
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

Rendez-vous ensuite sur http://localhost:8080 <br>
user : root <br>
password : password

et prenez des utilisateurs au hasard, leurs password est 'password'