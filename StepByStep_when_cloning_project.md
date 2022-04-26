# Etapes à suivre après avoir cloné le projet


## Symfony
Création du /vendor :
```
composer install
```

Créer le fichier .env.local
```
sudo nano .env.local
```

Création de la database (si ce n'est pas déjà fait)
```
php bin/console d:d:c
```

Migrations
```
php bin/console d:m:m
```

Ajout des fixtures
```
php bin/console d:f:l
```

PHP développement
```
php -S localhost:8080 -t public
```

## NodeJS / Tailwind / Postcss-loader / Purgecss-webpack-plugin

création du dossier nodes_modules
```
npm install -D
```

Démarrer le webpack
```
npm run dev
```

Il doit apparaitre dans le dossier public/build/ les fichiers app.css, app.js, entrypoints.json, manifest.json ...
En cas de problème ou quelconques messages d'erreurs -> contacter le lead dev front

---



## Composition des fichiers de config

### postcss.config.js
```js
let tailwindcss = require("tailwindcss")

module.exports = {
  plugins: [
    tailwindcss('./tailwind.config.js'),
    require('postcss-import'),
    require('autoprefixer')
  ]
}
```

### webpack.config.js
```js
const Encore = require('@symfony/webpack-encore');

// ...

.enablePostCssLoader((options) => {
        options.postcssOptions = {
            config: './postcss.config.js'
        }
    })
```

### tailwind.config.js
```js
module.exports = {
  content: [
    "./assets/**/*.{vue,js,ts,jsx,tsx}",
    "./templates/**/*.{html,twig}"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```


### assets/styles/app.css
```css
@tailwind base;

@tailwind components;

@tailwind utilities;

/* ... plus tous les éventuels components ... */
```

### Commandes compiler tailwind
```
npx tailwindcss -i ./assets/styles/app.css -o ./public/build/app.css --watch
```







