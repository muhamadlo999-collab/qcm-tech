--
USE qcm_tech;


INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(1, 'Que signifie HTML ?',
    'HyperText Markup Language', 'High Tech Modern Language', 'HyperText Modern Links', 'Home Tool Markup Language', 1),

(1, 'Quelle balise crée un lien hypertexte ?',
    '<link>', '<a>', '<href>', '<url>', 2),

(1, 'Quelle balise représente le titre principal d\'une page ?',
    '<title>', '<head>', '<h1>', '<header>', 3),

(1, 'Quelle balise est utilisée pour insérer une image ?',
    '<img>', '<image>', '<pic>', '<src>', 1),

(1, 'Quelle balise crée une liste non ordonnée ?',
    '<ol>', '<li>', '<ul>', '<list>', 3),

(1, 'Quel attribut définit l\'adresse d\'un lien hypertexte ?',
    'src', 'link', 'href', 'url', 3),

(1, 'Quelle balise crée un tableau en HTML ?',
    '<tb>', '<tab>', '<tbl>', '<table>', 4),

(1, 'Quelle balise définit une ligne dans un tableau ?',
    '<td>', '<tr>', '<th>', '<row>', 2),

(1, 'Quel attribut HTML rend un champ de formulaire obligatoire ?',
    'mandatory', 'obligatory', 'required', 'needed', 3),

(1, 'Quelle balise définit un paragraphe en HTML ?',
    '<p>', '<par>', '<para>', '<pg>', 1),

(1, 'Quelle balise HTML5 définit la zone principale du contenu ?',
    '<content>', '<body>', '<main>', '<section>', 3),

(1, 'Quel attribut affiche une info-bulle au survol ?',
    'alt', 'hint', 'tooltip', 'title', 4),

(1, 'Quelle balise HTML permet d\'inclure du JavaScript ?',
    '<js>', '<javascript>', '<script>', '<code>', 3),

(1, 'Quelle balise crée un saut de ligne en HTML ?',
    '<lb>', '<br>', '<nl>', '<break>', 2),

(1, 'Quelle balise HTML5 représente le pied de page ?',
    '<bottom>', '<foot>', '<footer>', '<end>', 3);

INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(2, 'Que signifie CSS ?',
    'Computer Style Sheets', 'Cascading Style Sheets', 'Creative Style System', 'Colorful Style Sheets', 2),

(2, 'Quelle propriété CSS change la couleur du texte ?',
    'text-color', 'font-color', 'color', 'foreground', 3),

(2, 'Quelle propriété CSS modifie la taille de la police ?',
    'text-size', 'font-size', 'text-style', 'font-style', 2),

(2, 'Quelle valeur de display crée une mise en page en grille ?',
    'flex', 'block', 'inline', 'grid', 4),

(2, 'Quel sélecteur CSS cible un élément par son identifiant ?',
    '.nom', '#nom', '*nom', '>nom', 2),

(2, 'Quel sélecteur CSS cible un élément par sa classe ?',
    '#nom', '.nom', '*nom', '~nom', 2),

(2, 'Quelle propriété CSS ajoute un espace intérieur à un élément ?',
    'margin', 'spacing', 'padding', 'border', 3),

(2, 'Quelle propriété CSS ajoute un espace extérieur à un élément ?',
    'padding', 'margin', 'border', 'gap', 2),

(2, 'Quelle propriété CSS active le mode flexbox ?',
    'display: grid', 'display: block', 'display: flex', 'display: inline', 3),

(2, 'Quelle propriété CSS définit la couleur d\'arrière-plan ?',
    'color', 'bg-color', 'background-color', 'fill', 3),

(2, 'Quelle propriété CSS contrôle la transparence d\'un élément ?',
    'visibility', 'opacity', 'transparent', 'alpha', 2),

(2, 'Quelle propriété CSS arrondit les coins d\'un élément ?',
    'border-style', 'corner-radius', 'border-radius', 'round', 3),

(2, 'Quelle propriété CSS définit la largeur d\'un élément ?',
    'size', 'length', 'width', 'horizontal', 3),

(2, 'Quelle valeur de position retire un élément du flux normal ?',
    'relative', 'static', 'absolute', 'normal', 3),

(2, 'Quelle propriété CSS ajoute une ombre à une boîte ?',
    'shadow', 'box-shadow', 'element-shadow', 'border-shadow', 2);

INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(3, 'Par quel symbole commencent les variables en PHP ?',
    '#', '&', '$', '@', 3),

(3, 'Quelle instruction PHP affiche du texte dans la page ?',
    'print()', 'echo', 'display()', 'show()', 2),

(3, 'Quelle fonction PHP hache un mot de passe de façon sécurisée ?',
    'md5()', 'sha1()', 'password_hash()', 'crypt()', 3),

(3, 'Quelle superglobale PHP récupère les données envoyées en POST ?',
    '$_GET', '$_POST', '$_REQUEST', '$_FORM', 2),

(3, 'Quelle fonction PHP démarre ou reprend une session ?',
    'start_session()', 'session_open()', 'session_start()', 'init_session()', 3),

(3, 'Quelle fonction PHP vérifie si une variable est définie et non null ?',
    'is_set()', 'isset()', 'defined()', 'exists()', 2),

(3, 'Quelle fonction PHP envoie un en-tête HTTP pour rediriger ?',
    'redirect()', 'goto()', 'header()', 'location()', 3),

(3, 'Quelle fonction PHP compte les éléments d\'un tableau ?',
    'length()', 'size()', 'count()', 'sizeof()', 3),

(3, 'Quelle fonction PHP supprime les espaces en début et fin de chaîne ?',
    'strip()', 'clean()', 'trim()', 'remove()', 3),

(3, 'Quel opérateur PHP concatène deux chaînes de caractères ?',
    '+', '&', '.', ',', 3),

(3, 'Quelle fonction PHP vérifie un mot de passe contre son hash ?',
    'password_check()', 'hash_verify()', 'password_verify()', 'check_hash()', 3),

(3, 'Quel mot-clé PHP inclut un fichier et arrête le script si introuvable ?',
    'include', 'require', 'import', 'load', 2),

(3, 'Quelle superglobale PHP stocke les données de session utilisateur ?',
    '$_COOKIE', '$_SESSION', '$_GLOBALS', '$_SERVER', 2),

(3, 'Quelle fonction PHP mélange aléatoirement les éléments d\'un tableau ?',
    'random()', 'rand()', 'shuffle()', 'mix()', 3),

(3, 'Quelle fonction PHP convertit une chaîne en minuscules ?',
    'toLower()', 'lowerCase()', 'strtolower()', 'lowercase()', 3);


INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(4, 'Quelle commande SQL permet de récupérer des données ?',
    'GET', 'FETCH', 'SELECT', 'READ', 3),

(4, 'Quelle commande SQL insère de nouvelles données dans une table ?',
    'ADD', 'INSERT INTO', 'PUT', 'CREATE ROW', 2),

(4, 'Quelle commande SQL modifie des données existantes ?',
    'MODIFY', 'CHANGE', 'UPDATE', 'EDIT', 3),

(4, 'Quelle commande SQL supprime des lignes dans une table ?',
    'REMOVE', 'DROP', 'DELETE', 'ERASE', 3),

(4, 'Quel mot-clé SQL filtre les lignes selon une condition ?',
    'FILTER', 'HAVING', 'WHERE', 'IF', 3),

(4, 'Quelle contrainte SQL garantit l\'unicité d\'une valeur dans une colonne ?',
    'PRIMARY KEY', 'UNIQUE', 'NOT NULL', 'INDEX', 2),

(4, 'Que représente une PRIMARY KEY dans une table ?',
    'Une clé étrangère', 'Une clé de tri', 'Un identifiant unique par ligne', 'Un mot de passe', 3),

(4, 'Quel mot-clé SQL trie les résultats d\'une requête ?',
    'SORT BY', 'GROUP BY', 'ORDER BY', 'ARRANGE BY', 3),

(4, 'Quelle fonction SQL compte le nombre de lignes retournées ?',
    'SUM()', 'TOTAL()', 'COUNT()', 'NUMBER()', 3),

(4, 'Quel mot-clé SQL permet de joindre deux tables ?',
    'MERGE', 'COMBINE', 'JOIN', 'LINK', 3),

(4, 'Quelle clause SQL regroupe les lignes selon une colonne ?',
    'ORDER BY', 'GROUP BY', 'SORT BY', 'FILTER BY', 2),

(4, 'Quelle contrainte SQL interdit les valeurs nulles dans une colonne ?',
    'NOT EMPTY', 'REQUIRED', 'NOT NULL', 'MANDATORY', 3),

(4, 'Quel type SQL est adapté pour stocker un long texte ?',
    'VARCHAR', 'STRING', 'TEXT', 'LONGCHAR', 3),

(4, 'Quelle commande SQL crée une nouvelle table ?',
    'MAKE TABLE', 'NEW TABLE', 'CREATE TABLE', 'BUILD TABLE', 3),

(4, 'Quel mot-clé SQL évite les doublons dans les résultats ?',
    'UNIQUE', 'DIFFERENT', 'DISTINCT', 'NODUPE', 3);


INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(5, 'Que signifie IP dans le contexte des réseaux informatiques ?',
    'Internet Protocol', 'Internal Process', 'Information Path', 'Input Port', 1),

(5, 'Sur combien de bits est codée une adresse IPv4 ?',
    '16 bits', '64 bits', '128 bits', '32 bits', 4),

(5, 'Quel protocole traduit un nom de domaine en adresse IP ?',
    'HTTP', 'FTP', 'DNS', 'DHCP', 3),

(5, 'Quel port utilise le protocole HTTP par défaut ?',
    '21', '443', '80', '25', 3),

(5, 'Quel port utilise le protocole HTTPS par défaut ?',
    '80', '443', '8080', '22', 2),

(5, 'Que signifie MAC dans "adresse MAC" ?',
    'Media Access Control', 'Machine Address Code', 'Main Access Channel', 'Module Access Control', 1),

(5, 'Quel protocole attribue automatiquement une adresse IP à un appareil ?',
    'DNS', 'FTP', 'DHCP', 'HTTP', 3),

(5, 'Quel équipement réseau permet de relier plusieurs réseaux différents ?',
    'Un switch', 'Un routeur', 'Un hub', 'Un modem', 2),

(5, 'Quelle adresse IP est réservée à la machine locale (loopback) ?',
    '192.168.0.1', '255.255.255.0', '127.0.0.1', '0.0.0.0', 3),

(5, 'Que signifie FTP ?',
    'File Transfer Protocol', 'Fast Transfer Process', 'File Transfer Path', 'Fast TCP Protocol', 1),

(5, 'Quel modèle de référence réseau comporte 7 couches ?',
    'TCP/IP', 'OSI', 'HTTP', 'ISO', 2),

(5, 'Quelle couche du modèle OSI gère le transport fiable des données ?',
    'Couche réseau', 'Couche liaison', 'Couche transport', 'Couche session', 3),

(5, 'Que signifie LAN ?',
    'Large Area Network', 'Local Area Network', 'Linked Access Node', 'Light Area Network', 2),

(5, 'Quel protocole sécurise les communications HTTPS ?',
    'SSH', 'TLS/SSL', 'FTP', 'VPN', 2),

(5, 'Combien d\'octets compose une adresse IPv4 ?',
    '2', '6', '4', '8', 3);


INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(6, 'Qu\'est-ce qu\'un algorithme ?',
    'Un langage de programmation', 'Une suite d\'instructions pour résoudre un problème', 'Un type de base de données', 'Un protocole réseau', 2),

(6, 'Quelle structure répète un bloc d\'instructions tant qu\'une condition est vraie ?',
    'if/else', 'switch', 'boucle (while/for)', 'fonction', 3),

(6, 'Que se passe-t-il si une fonction récursive n\'a pas de cas de base ?',
    'Elle retourne un résultat correct', 'Elle génère une erreur de syntaxe', 'Elle provoque une boucle infinie ou un stack overflow', 'Elle retourne zéro', 3),

(6, 'Quelle est la complexité d\'une recherche séquentielle dans un tableau non trié ?',
    'O(1)', 'O(log n)', 'O(n)', 'O(n²)', 3),

(6, 'Quelle structure de données fonctionne en mode LIFO (dernier entré, premier sorti) ?',
    'File (Queue)', 'Tableau', 'Pile (Stack)', 'Liste chaînée', 3),

(6, 'Quelle structure de données fonctionne en mode FIFO (premier entré, premier sorti) ?',
    'Pile (Stack)', 'File (Queue)', 'Arbre', 'Graphe', 2),

(6, 'Quelle est la complexité du tri à bulles dans le pire cas ?',
    'O(n)', 'O(log n)', 'O(n log n)', 'O(n²)', 4),

(6, 'Qu\'est-ce qu\'une variable en programmation ?',
    'Une constante immuable', 'Un espace mémoire nommé pour stocker une valeur', 'Une fonction', 'Une boucle', 2),

(6, 'En quoi consiste la recherche dichotomique (binaire) ?',
    'Trier un tableau', 'Chercher en divisant l\'espace de recherche par 2 à chaque étape', 'Parcourir tous les éléments un par un', 'Supprimer les doublons', 2),

(6, 'Quelle notation est utilisée pour décrire la complexité temporelle d\'un algorithme ?',
    'Notation UML', 'Notation Big O', 'Notation SQL', 'Notation hexadécimale', 2);

INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(7, 'Quel est le rôle principal d\'un système d\'exploitation ?',
    'Naviguer sur internet', 'Gérer les ressources matérielles et logicielles d\'un ordinateur', 'Créer des bases de données', 'Compiler du code source', 2),

(7, 'Quelle commande Linux affiche le répertoire de travail actuel ?',
    'ls', 'cd', 'pwd', 'dir', 3),

(7, 'Quelle commande Linux liste les fichiers et dossiers d\'un répertoire ?',
    'pwd', 'ls', 'cat', 'find', 2),

(7, 'Que signifie OS en informatique ?',
    'Open Source', 'Operating System', 'Output Service', 'Online Server', 2),

(7, 'Quel système de fichiers est natif sous Linux ?',
    'NTFS', 'FAT32', 'ext4', 'exFAT', 3),

(7, 'Quelle commande Linux crée un nouveau dossier ?',
    'touch', 'create', 'mkdir', 'newdir', 3),

(7, 'Qu\'est-ce qu\'un processus en informatique ?',
    'Un fichier système', 'Un programme en cours d\'exécution', 'Une commande réseau', 'Un pilote de périphérique', 2),

(7, 'Quelle commande Linux affiche la liste des processus en cours ?',
    'proc', 'task', 'ps', 'run', 3),

(7, 'Quel utilisateur possède tous les droits sur un système Linux ?',
    'admin', 'superuser', 'root', 'sudo', 3),

(7, 'Quelle commande Linux copie un fichier vers un autre emplacement ?',
    'mv', 'cp', 'copy', 'dup', 2);


INSERT INTO questions (categorie_id, question, reponse1, reponse2, reponse3, reponse4, bonne_reponse) VALUES
(8, 'Qui a inventé le World Wide Web ?',
    'Bill Gates', 'Steve Jobs', 'Tim Berners-Lee', 'Linus Torvalds', 3),

(8, 'En quelle année a été créé le réseau ARPANET, ancêtre d\'Internet ?',
    '1989', '1975', '1969', '1983', 3),

(8, 'Que signifie CPU ?',
    'Central Processing Unit', 'Computer Power Unit', 'Central Program Utility', 'Core Processing Unit', 1),

(8, 'Quel langage de programmation est exécuté nativement par les navigateurs web ?',
    'PHP', 'Python', 'JavaScript', 'Java', 3),

(8, 'Que signifie le terme Open Source ?',
    'Logiciel entièrement gratuit', 'Logiciel dont le code source est librement accessible', 'Logiciel sans licence', 'Logiciel fonctionnant en ligne uniquement', 2);