# Récapitulatif des changements de la plateforme ForPro

Ce document résume les principaux changements apportés
à la plateforme ForPro au cours de la refonte de Août-Septembre 2026.

La plateforme regroupe quatre parties, qui vivent désormais dans un seul projet :
le **CMS** (l'administration où vous éditez les contenus), le **site web**
for-pro.ch, le **site du restaurant** FoodLab et les **écrans des menus** du
FoodLab et du FoodCourt.

## En bref

- Toute la base technique a été mise à jour, et le projet a été fortement allégé :
  le CMS n'y est plus stocké mais installé automatiquement, et près de 20 000
  lignes de modules inutilisés ont été supprimées.
- Le restaurant dispose de son propre espace, séparé du site web.
- Le site web a été refait avec trois améliorations de fond : accessibilité,
  référencement, et lisibilité par les assistants IA.
- Les modules inutilisés ont été supprimés.

## 1. Une base technique à jour et plus simple

**Tout a été mis à jour.** Le CMS Kirby est passé de la version 4 à la version 5,
et les technologies du site web, du site du restaurant et des écrans de menus ont
été portées vers leurs versions actuelles. Les écrans de menus, jusqu'ici un
projet séparé, ont été rapatriés dans le même projet que le reste.
*Pourquoi :* les anciennes versions ne reçoivent plus de correctifs de sécurité.
La plateforme reste sûre et maintenable pour plusieurs années, et tout le code se
trouve au même endroit, avec les mêmes outils.

**Le projet est plus léger et mieux organisé.** Le CMS n'est plus copié dans le
projet mais installé automatiquement ; les réglages sensibles (mots de passe,
clés) sont sortis du code ; chaque environnement (test, production) est
clairement séparé.
*Pourquoi :* une mise à jour de sécurité se fait en une ligne au lieu de
remplacer des centaines de fichiers à la main, et aucun secret ne peut être
diffusé par erreur.

**La mise en ligne est automatisée.** Une modification validée est construite et
déployée automatiquement, d'abord sur l'environnement de test, puis en
production, avec un contrôle que chaque service répond bien.
*Pourquoi :* moins d'erreurs humaines et des mises en ligne rapides et
reproductibles.

## 2. Le restaurant a son propre espace

**Les contenus du restaurant ont quitté les réglages du site web.** Textes,
horaires, images, fichiers et liens se gèrent maintenant dans un espace
**Restaurant** dédié de l'administration, à côté de l'espace **Menu**. Les
contenus existants ont été migrés automatiquement. Le formulaire enregistre au
fur et à mesure, et un PDF « menu pop-up » a été ajouté à côté du menu publié.
*Pourquoi :* l'équipe du restaurant travaille dans un espace qui lui est propre,
réservé aux personnes autorisées, sans risque de modifier par erreur un réglage
du site web, et sans perte de saisie.

**Le site du restaurant se présente correctement à Google.** Il transmet des
informations structurées de type « Restaurant » (nom, adresse, horaires) et un
plan du site.
*Pourquoi :* Google comprend qu'il s'agit d'un restaurant et peut afficher ces
informations directement dans ses résultats.

## 3. Le site web : trois améliorations de fond

Le site web a été entièrement refait, comme prévu. Au-delà des nouvelles pages et
du nouveau design, trois améliorations transversales méritent d'être soulignées.

### 3.1 Accessibilité

Le site est utilisable par tout le monde, y compris au clavier ou avec un lecteur
d'écran : un lien permet de sauter la navigation ; menus, filtres, FAQ et
recherche fonctionnent au clavier et annoncent leurs changements aux lecteurs
d'écran ; les images et animations ont des textes alternatifs éditables dans le
CMS ; les animations sont réduites pour les visiteurs qui l'ont demandé dans
leurs réglages.
*Pourquoi :* c'est une obligation croissante pour les organismes publics et
parapublics, cela élargit votre audience, et les moteurs de recherche valorisent
les sites accessibles.

### 3.2 Référencement (SEO)

**Un seul outil de référencement, réellement utilisé.** Deux modules SEO
cohabitaient dans le CMS : « Meta Knight », un ancien module qui n'était plus
utilisé mais restait dans le code, et « Kirby SEO », l'outil actuel, dont les
réglages étaient saisis dans l'administration mais n'étaient pas repris par le
site web. Meta Knight a été retiré, et Kirby SEO est désormais branché de bout
en bout : titre, description, adresse officielle de la page, image et texte de
partage pour les réseaux sociaux sont générés de façon uniforme pour toutes les
pages, avec un repli sur les réglages du site lorsqu'une page n'a rien de
spécifique. Le plan du site et les consignes d'indexation sont produits
automatiquement.
*Pourquoi :* ce que vous saisissez dans l'onglet SEO est enfin visible par Google
et les réseaux sociaux. Plus aucune page « oubliée » : chaque partage sur
LinkedIn ou WhatsApp affiche la bonne image et le bon texte, Google découvre les
nouvelles pages seul, et l'environnement de test n'est jamais indexé par erreur.

**Des données structurées sur chaque page.** C'est le point le plus important.
Chaque page contient désormais une « carte d'identité », invisible pour le
visiteur mais lue par Google et les assistants IA, qui dit précisément ce qu'est
la page : un événement avec ses dates et son lieu, une offre d'emploi ou une
mission, un projet et les personnes qui le portent, l'équipe, une question et sa
réponse pour la FAQ, et pour toutes les pages, le fil d'Ariane et le lien vers
l'organisation ForPro.
*Pourquoi :* Google peut afficher vos événements et offres d'emploi dans ses
résultats enrichis (Google Jobs, agenda, questions/réponses), ce qui augmente
fortement la visibilité. Ces informations sont produites par le CMS à partir des
contenus existants : rien de plus à saisir.

### 3.3 Contenu lisible par les assistants IA

De plus en plus de personnes s'informent via ChatGPT, Claude, Perplexity ou les
résumés IA de Google. Ces assistants lisent le web, mais lisent mal les pages
classiques, chargées de menus et d'éléments visuels.

**Un fichier `llms.txt` et une version texte de chaque page.** Le fichier
`llms.txt`, un standard récent, est un plan du site rédigé en langage simple :
présentation de ForPro, informations clés, et liste des pages importantes
décrites en une ligne. Chaque page existe aussi en version texte épurée, en
ajoutant `.md` à son adresse (par exemple `/entreprendre/mentorat.md`), produite
à partir du même contenu que la page normale.
*Pourquoi :* un assistant IA qui visite for-pro.ch sait immédiatement qui vous
êtes, où trouver l'information, et lit le contenu exact, sans bruit. Il vous cite
donc correctement.

**Vous gardez la main sur le message.** Une section « Présentation IA » dans les
réglages SEO du site permet de rédiger la présentation utilisée par ces outils.
Une page exclue des moteurs de recherche l'est aussi des assistants IA.
*Pourquoi :* c'est vous qui décidez comment ForPro est décrit aux IA.

## 4. Ce que nous avons retiré, et pourquoi

**Le module de calendrier et de prise de rendez-vous.** Il n'était plus utilisé.
Sa suppression concerne une centaine de fichiers et près de 10 000 lignes de
code, dans l'administration comme sur le site web.
*Pourquoi :* du code inutilisé doit quand même être maintenu, mis à jour et
sécurisé. Il freinait la mise à jour vers Kirby 5 et constituait une porte
d'entrée potentielle pour des attaques.

**Les doublons et les restes obsolètes.** L'ancien module SEO « Meta Knight »
(installé mais plus utilisé, voir section 3.2), une enveloppe technique devenue
inutile, la copie complète de Kirby, une archive de contenus périmée et
d'anciens scripts.
*Pourquoi :* du code présent mais inutilisé doit quand même être maintenu et
peut entrer en conflit avec l'outil actuel ; un projet allégé est plus clair,
plus rapide à installer et plus facile à reprendre.

## 5. Autres améliorations

**Images et vitesse.** Les images trop grandes envoyées dans le CMS sont
automatiquement réduites et converties ; les fichiers médias sont compressés et
mis en cache ; les longues listes (agenda, projets, missions) se chargent au fil
du défilement.
*Pourquoi :* plus d'images qui cassent l'affichage, et des pages plus rapides,
en particulier sur mobile.

**Messages d'erreur clairs.** Si le CMS est momentanément indisponible, le site
affiche une page d'erreur propre plutôt qu'une page blanche.
*Pourquoi :* une meilleure expérience pour le visiteur et un diagnostic plus
rapide pour nous.

## En résumé

Au-delà du nouveau site, cette refonte laisse ForPro avec une plateforme :

- **plus accessible** : le site web est utilisable par tout le monde, au clavier
  comme avec un lecteur d'écran, et répond aux attentes croissantes en matière
  d'accessibilité ;
- **mieux référencée** : chaque page se présente correctement à Google, avec des
  informations complètes et des données structurées qui rendent vos événements,
  offres d'emploi et questions fréquentes visibles dans les résultats enrichis ;
- **prête pour les assistants IA** : ForPro est lisible et citable par ChatGPT,
  Claude, Perplexity et les résumés IA de Google, avec un message que vous
  maîtrisez ;
- **plus simple à maintenir** : une base technique à jour, un seul projet pour
  les quatre parties, un restaurant qui a son propre espace, des modules
  inutilisés supprimés et des mises en ligne automatisées.

Le résultat est une plateforme plus visible, plus inclusive et moins coûteuse à
faire évoluer dans les années à venir.
