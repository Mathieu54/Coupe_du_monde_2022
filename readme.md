[![forthebadge](https://forthebadge.com/images/badges/built-with-love.svg)](https://forthebadge.com)[![forthebadge](https://forthebadge.com/images/badges/it-works-why.svg)](https://forthebadge.com)

# ⚽ Coupe du monde parie 2022

Permet de faire des paries sur les matchs de la coupe du monde 2022 avec des amis.

## ⚙️Installation

Pour installer le projet executé la commande docker suivante pour installer les containers.

```bash
  docker-compose up -d
```

Une fois que les containers sont crées. Installer les dépendances de composer avec cette commande :

```bash
  composer install
```

Les dépendances installés faites de même avec la commande npm :

```bash
  npm install
```

Faites un build pour vérifier que vous n'avez pas d'erreur.

```bash
  npm run build
```

Pour terminer faites la commande pour exécuter l'ensemble des migrations avec cette commande :
```bash
  php bin/console doctrine:migrations:migrate
```

## 🌱 Environment Variables

Pour exécuter ce projet proprement, vous devrez modifier les variables d'environnement suivantes à votre fichier .env

`DATABASE_URL`

`MAILER_DSN`

## 👁️‍🗨️ Auteur

- [Mathieu54](https://www.github.com/Mathieu54)

## 🔗 Liens
[![portfolio](https://img.shields.io/badge/mon_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://harmant-mathieu.fr/) [![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://fr.linkedin.com/public-profile/in/mathieu-harmant)

