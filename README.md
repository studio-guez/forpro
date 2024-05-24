# Technical Documentation

## infos

### CMS
- Kirby CMS 4.0
- PHP 8.1

#### Development Environment
php -S localhost:1234 kirby/router.php

### Webiste
- SvelteKit 2.0

#### Development Environment
pnpm run dev

## production

### Backend
config php de base, sur /var/www/cms

### Front
/var/www/website

lister process pm2
```bash
pm2 start build/index.js
```

Il devrait y avoir un seul process, celui qui sert le front du site.

relancer le proces
```bash
npm run build
pm2 restart 0 
```

Si il faut démarrer le process
```bash
npm run build
pm2 start build/index.js
```
