# a2dissite menus-le-ssl.conf && a2ensite menus-le-ssl.conf && systemctl reload apache2
systemctl reload apache2


a2dissite menus.conf && a2ensite menus.conf && systemctl reload apache2

## CSR
## Exclure les fichiers statiques comme .zip, .js, etc., de la redirection vers index.html
#RewriteCond %{REQUEST_URI} !\.(?:js|css|json|png|jpg|jpeg|gif|svg|webp|ico|woff|woff2|ttf|zip|pdf|txt|xml)$ [NC]
#RewriteCond %{REQUEST_URI} !\.(js|css|json|png|jpg|jpeg|gif|svg|webp|ico|woff|woff2|ttf|zip|pdf|txt|xml|xlsx)$ [NC]
## Rediriger uniquement les URLs HTML ou sans extension vers index.html si ce n'est pas un fichier ou dossier existant
#RewriteCond %{REQUEST_FILENAME} !-f
#RewriteCond %{REQUEST_FILENAME} !-d
#RewriteRule ^(.+)$ /index.html [L]
