-- ============================================
-- DONNÉES COHÉRENTES SUR LA GUERRE EN IRAN
-- Base de données d'analyse géopolitique
-- ============================================

-- Suppression des données existantes (optionnel, faire avec précaution)
-- TRUNCATE contenu_tag CASCADE;
-- TRUNCATE image CASCADE;
-- TRUNCATE contenu CASCADE;
-- TRUNCATE tag CASCADE;
-- TRUNCATE page CASCADE;
-- TRUNCATE type CASCADE;

-- ============================================
-- 1. TYPES DE CONTENU
-- ============================================
INSERT INTO type (nom_type, balise) VALUES
('Titre Principal', 'h1'),
('Sous Titre', 'h2'),
('Paragraphe', 'p'),
('Citation', 'blockquote');

-- ============================================
-- 2. RÔLES UTILISATEURS
-- ============================================
INSERT INTO role (libelle, description) VALUES
('admin', 'Administrateur avec accès complet'),
('user', 'Utilisateur standard avec accès limité');

-- ============================================
-- 3. UTILISATEURS (mdp: admin123 / user123)
-- ============================================
INSERT INTO utilisateur (nom, mdp, id_role) VALUES
('admin', '$2y$10$u308rsGS2CDatlbDbUDiPOqg33hqFinP8JO/ogX4c.s1u9A9.BmGy', 1),
('user', '$2y$10$0ocqhh7WOwkNVVtuWlJuEOmmmfIpf63fnMcKN5NXY2tOb3DYX4BtG', 2);

-- ============================================
-- 4. PAGES
-- ============================================
INSERT INTO page (slug) VALUES
('accueil'),
('actualites'),
('analyse-guerre-iran'),
('conflit-israel-iran'),
('sanctions-economiques'),
('acteurs-regionaux');

-- ============================================
-- 5. TAGS
-- ============================================
INSERT INTO tag (libelle) VALUES
('Iran'),
('Guerre'),
('Géopolitique'),
('Conflit'),
('Moyen-Orient'),
('Israel'),
('Sanctions'),
('Pétrole'),
('Nucléaire'),
('Russie'),
('Chine'),
('États-Unis'),
('Drones'),
('Armée'),
('Terrorisme'),
('Diplomatie'),
('Crise humanitaire'),
('Réfugiés');

-- ============================================
-- 6. CONTENU DE LA PAGE "accueil" (id_page = 1)
-- ============================================

-- Titre principal
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Observatoire du Conflit Iranien', 1, 'observatoire-iran', 1, NULL, 1);

-- Sous-titres de l'accueil
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Dernières analyses stratégiques', 2, 'dernieres-analyses', 1, 1, 1),
('Points chauds du moment', 2, 'points-chauds', 1, 1, 2),
('Indicateurs clés', 2, 'indicateurs-cles', 1, 1, 3);

-- Paragraphes pour "Dernières analyses stratégiques"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('La situation au Moyen-Orient connaît des bouleversements sans précédent depuis l''escalade du conflit entre Israël et l''Iran. Les récentes frappes aériennes sur les installations nucléaires iraniennes ont déclenché une riposte mesurée mais significative de Téhéran, impliquant des tirs de missiles balistiques et l''utilisation croissante de drones suicide. Cette escalade soulève des questions fondamentales sur la stabilité régionale et les équilibres stratégiques mondiaux.', 3, 'analyse-strategique-1', 1, 2, 1),
('Les experts s''accordent à dire que nous assistons à un changement de paradigme dans la doctrine militaire iranienne. Autrefois cantonnée à une stratégie de guerre asymétrique via ses proxys régionaux (Hezbollah, milices irakiennes, Houthis), l''Iran adopte désormais une posture plus offensive directe. Cette mutation tactique redéfinit les règles d''engagement dans la région et oblige les puissances occidentales à revoir leur approche diplomatique.', 3, 'analyse-strategique-2', 1, 2, 2);

-- Paragraphes pour "Points chauds du moment"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le détroit d''Ormuz demeure un point de friction majeur. Environ 20% du pétrole mondial transite par cette voie stratégique. Les récentes saisies de pétroliers par les Gardiens de la Révolution islamique ont fait grimper les prix du baril de 15% en deux semaines. Les compagnies maritimes redirigent leurs navires vers des routes alternatives plus longues et coûteuses, impactant directement l''économie mondiale.', 3, 'point-chaud-ormuz', 1, 3, 1),
('La province du Sistan-Baloutchistan, à l''est de l''Iran, est devenue le théâtre d''affrontements réguliers entre les forces iraniennes et des groupes séparatistes baloutches soutenus par des puissances étrangères. Les civils pris au piège de ces combats subissent une grave crise humanitaire, avec des pénuries d''eau potable et de médicaments. Plus de 200 000 personnes auraient déjà fui vers le Pakistan voisin.', 3, 'point-chaud-baloutchistan', 1, 3, 2);

-- Paragraphes pour "Indicateurs clés"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le rial iranien a perdu 60% de sa valeur depuis le début du conflit. L''inflation atteint désormais 55%, avec des prix alimentaires en hausse de 80% dans certaines régions. La monnaie nationale s''échange à plus de 600 000 rials pour un dollar sur le marché noir, contre 260 000 avant l''escalade.', 3, 'indicateur-rial', 1, 4, 1),
('Les pertes humaines sont estimées à plus de 15 000 morts côté iranien (militaires et civils) depuis le début des hostilités ouvertes. Les forces israéliennes et américaines déplorent environ 300 pertes. Les frappes aériennes ont détruit ou endommagé plus de 200 sites militaires et infrastructures critiques iraniennes.', 3, 'indicateur-pertes', 1, 4, 2);

-- ============================================
-- 7. CONTENU DE LA PAGE "actualites" (id_page = 2)
-- ============================================

-- Titre principal
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Actualités du Conflit Iranien', 1, 'actu-iran', 2, NULL, 1);

-- Sous-titres
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Frappes israéliennes : ce que l''on sait', 2, 'frappes-israeliennes', 2, 5, 1),
('La réponse iranienne se précise', 2, 'reponse-iranienne', 2, 5, 2),
('Réactions internationales', 2, 'reactions-internationales', 2, 5, 3),
('Analyse des drones Shahed', 2, 'analyse-drones', 2, 5, 4);

-- Paragraphes pour "Frappes israéliennes : ce que l'on sait"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Dans la nuit du 15 au 16 mars 2026, l''armée de l''air israélienne a mené une série de frappes ciblées contre trois sites militaires iraniens situés près d''Ispahan et de Natanz. Selon des sources diplomatiques anonymes, ces frappes visaient spécifiquement des centrifugeuses avancées IR-9 utilisées pour l''enrichissement d''uranium à 84%, seuil dangereusement proche de l''arme nucléaire. Les satellites commerciaux ont capturé des images montrant d''importants dégâts structurels sur les installations, avec des panaches de fumée s''élevant jusqu''à 500 mètres d''altitude.', 3, 'details-frappes', 2, 6, 1),
('L''opération, baptisée "Iron Harvest", a mobilisé une trentaine d''avions furtifs F-35I Adir, accompagnés de ravitailleurs pour permettre le raid longue distance (plus de 2000 km). La défense aérienne iranienne, composée de systèmes S-300 russes et de missiles Khordad-15 fabriqués localement, n''aurait intercepté que 15% des projectiles, révélant des failles critiques dans le dispositif de protection des sites sensibles. Le gouvernement israélien n''a ni confirmé ni infirmé son implication, maintenant sa stratégie d''ambiguïté tactique.', 3, 'details-frappes-suite', 2, 6, 2);

-- Paragraphes pour "La réponse iranienne se précise"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le Guide suprême iranien, l''ayatollah Ali Khamenei, a promis lors d''un discours télévisé une "riposte cinglante et historique" qui "changera la carte du Moyen-Orient". Dans l''immédiat, les Forces Qods du Corps des Gardiens de la Révolution islamique (CGRI) ont lancé une salve de 120 missiles balistiques Fateh-110 et Zolfaghar en direction des positions israéliennes sur les hauteurs du Golan et de la base de Dimona. Heureusement, la plupart ont été interceptés par le dôme de fer et les systèmes Arrow israéliens, mais plusieurs impacts ont été signalés près de zones civiles, causant des dégâts matériels.', 3, 'riposte-iran', 2, 7, 1),
('Parallèlement aux frappes directes, Téhéran a activé ses réseaux proxys régionaux. Le Hezbollah libanais a intensifié ses tirs de roquettes sur le nord d''Israël, contraignant l''évacuation de dizaines de milliers de civils. En Syrie, des milices pro-iraniennes ont ciblé des bases américaines à Al-Tanf et Deir Ezzor, provoquant des affrontements qui ont fait plusieurs blessés parmi les forces de la coalition. Cette stratégie d''encerclement vise à disperser les capacités de défense israéliennes sur plusieurs fronts simultanément.', 3, 'riposte-iran-suite', 2, 7, 2);

-- Paragraphes pour "Réactions internationales"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les États-Unis ont dépêché le porte-avions USS Gerald R. Ford et son groupe aérien en Méditerranée orientale, en signe de soutien à leur allié israélien. Le secrétaire d''État a déclaré : "Notre engagement envers la sécurité d''Israël est inébranlable. Nous appelons toutes les parties à la retenue, mais nous nous réservons le droit de protéger nos intérêts et ceux de nos alliés." Parallèlement, les forces américaines en Irak et en Syrie ont été placées en état d''alerte maximale.', 3, 'reaction-usa', 2, 8, 1),
('La Russie et la Chine ont appelé à une désescalade immédiate et proposé leur médiation. Le ministre russe des Affaires étrangères, Sergueï Lavrov, a rencontré son homologue iranien à Moscou pour discuter d''un accord-cadre sur le programme nucléaire en échange d''un allègement des sanctions. La Chine, quant à elle, a annoncé l''envoi d''un émissaire spécial dans la région pour tenter de renouer le dialogue, tout en poursuivant ses achats de pétrole iranien via des circuits contournant les sanctions occidentales.', 3, 'reaction-russie-chine', 2, 8, 2);

-- Paragraphes pour "Analyse des drones Shahed"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les drones Shahed-136, surnommés "drones suicide" ou "mopeds volants", sont devenus l''arme signature de l''Iran dans ce conflit. D''un coût unitaire estimé à seulement 20 000 dollars, ces engins volants en forme d''aile delta peuvent parcourir plus de 2000 km avec une charge explosive de 40 kg. Leur moteur à essence les rend particulièrement bruyants, d''où leur surnom de "mobylette volante". Malgré leur lenteur, leur nombre et leur faible signature radar les rendent difficiles à intercepter de manière économique pour les défenses conventionnelles.', 3, 'analyse-shahed', 2, 9, 1),
('L''Iran produirait actuellement plus de 300 Shahed par mois, grâce à des chaînes d''assemblage industrialisées et des composants électroniques souvent détournés du marché civil. La Russie a également acquis une licence de production et utilise massivement ces drones sur le front ukrainien. Les experts estiment que cette guerre fait office de laboratoire grandeur nature pour tester et perfectionner ces systèmes d''arme, qui pourraient redéfinir les conflits de demain en rendant la guerre aérienne accessible à des puissances moyennes.', 3, 'analyse-shahed-suite', 2, 9, 2);

-- ============================================
-- 8. CONTENU DE LA PAGE "analyse-guerre-iran" (id_page = 3)
-- ============================================

-- Titre principal
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Analyse Géopolitique Approfondie du Conflit Iranien', 1, 'analyse-geopolitique', 3, NULL, 1);

-- Sous-titres principaux
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les origines historiques du conflit', 2, 'origines-historiques', 3, 10, 1),
('La dimension nucléaire : enjeux et perspectives', 2, 'dimension-nucleaire', 3, 10, 2),
('Les alliances régionales et internationales', 2, 'alliances-regionales', 3, 10, 3),
('L''économie de guerre iranienne', 2, 'economie-guerre', 3, 10, 4),
('Les scénarios d''évolution du conflit', 2, 'scenarios-evolution', 3, 10, 5);

-- Paragraphes pour "Les origines historiques du conflit"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les racines de l''hostilité entre l''Iran et les puissances occidentales, particulièrement les États-Unis et Israël, plongent dans la révolution islamique de 1979. La prise d''otages de l''ambassade américaine à Téhéran a scellé une rupture diplomatique qui dure encore aujourd''hui, avec des sanctions économiques quasi ininterrompues depuis plus de quatre décennies. Cette animosité historique a été entretenue et amplifiée par des événements majeurs : le soutien iranien au Hezbollah durant la guerre civile libanaise, l''affaire Iran-Contra, la désignation de l''Iran comme membre de "l''axe du mal" par George W. Bush en 2002, et plus récemment l''assassinat ciblé du général Qassem Soleimani par les États-Unis en 2020.', 3, 'origines-1', 3, 11, 1),
('Parallèlement, la rivalité avec Israël s''est intensifiée après la victoire de la révolution islamique. L''Iran ne reconnaît pas le droit à l''existence d''Israël et soutient activement les groupes armés palestiniens (Hamas, Jihad islamique) ainsi que le Hezbollah libanais, considérés comme des organisations terroristes par l''Occident et Israël. La guerre par procuration ("proxy war") qui oppose les deux pays depuis les années 1980 est entrée dans une phase d''affrontement direct depuis 2024, avec des frappes aériennes israéliennes régulières sur le sol iranien et des représailles iraniennes contre des cibles israéliennes à l''étranger.', 3, 'origines-2', 3, 11, 2);

-- Paragraphes pour "La dimension nucléaire : enjeux et perspectives"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le programme nucléaire iranien reste au cœur des tensions internationales. Démarré dans les années 1950 avec l''aide des États-Unis ("Atoms for Peace"), il a été relancé après la révolution et surtout après la guerre Iran-Irak (1980-1988). L''Iran a toujours affirmé que son programme était exclusivement civil, visant la production d''électricité et d''isotopes médicaux. Cependant, les inspections de l''AIEA ont régulièrement révélé des activités non déclarées, notamment l''enrichissement d''uranium à des niveaux proches de l''usage militaire (84% en mars 2026). La rupture progressive de l''accord de Vienne (JCPOA) après le retrait américain de 2018 a permis à Téhéran d''accélérer ses recherches et de réduire considérablement le "temps de rupture" (le délai nécessaire pour produire une bombe) désormais estimé à moins de deux semaines.', 3, 'nuclaire-1', 3, 12, 1),
('Plusieurs scénarios se dessinent concernant l''avenir du programme nucléaire iranien. Le premier serait une reprise des négociations sous l''égide de l''UE et de la Chine, aboutissant à un nouvel accord plus strict incluant le contrôle des missiles balistiques et une limitation des capacités d''enrichissement pour 25 ans. Le deuxième scénario, jugé plus probable par les analystes, est celui d''une "capacité de seuil" (threshold capability) où l''Iran maîtriserait toute la chaîne du cycle du combustible sans franchir le pas ultime de l''arme. Enfin, le scénario catastrophe serait une frappe préventive israélienne massive déclenchant une guerre régionale généralisée, avec des conséquences imprévisibles sur l''économie mondiale et l''environnement (radioactivité, marée noire dans le Golfe).', 3, 'nuclaire-2', 3, 12, 2);

-- Paragraphes pour "Les alliances régionales et internationales"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le conflit iranien redessine la carte des alliances au Moyen-Orient. Le camp pro-iranien comprend traditionnellement la Syrie de Bachar al-Assad, le Hezbollah libanais, les Houthis yéménites, diverses milices chiites irakiennes (dont les Kataeb Hezbollah) et plus récemment la Russie, avec laquelle l''Iran a signé un accord de partenariat stratégique incluant la livraison de systèmes S-400 et de chasseurs Su-35. La Chine, bien que plus distante militairement, est le premier partenaire commercial de l''Iran (jusqu''à 500 000 barils de pétrole par jour) et protège régulièrement Téhéran au Conseil de sécurité de l''ONU.', 3, 'alliances-1', 3, 13, 1),
('En face, Israël bénéficie du soutien inconditionnel des États-Unis, qui maintiennent une présence militaire significative dans la région (bases au Qatar, à Bahreïn, à Djibouti, en Irak et en Jordanie). Les monarchies du Golfe (Arabie Saoudite, Émirats arabes unis, Bahreïn), traditionnellement rivales de l''Iran, ont toutefois normalisé leurs relations avec Téhéran en 2025 sous médiation chinoise, adoptant une posture de neutralité active dans le conflit actuel. Cette recomposition diplomatique complique la stratégie israélienne, qui ne peut plus compter sur un front arabe uni contre l''Iran.', 3, 'alliances-2', 3, 13, 2);

-- Paragraphes pour "L'économie de guerre iranienne"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Malgré des décennies de sanctions internationales, l''Iran a développé une "économie de résistance" capable de soutenir un effort de guerre prolongé. Le pays est largement autosuffisant dans les domaines militaire (missiles, drones, blindés), énergétique (raffinage pétrolier) et agroalimentaire. Le budget militaire iranien a bondi de 40% en 2025 pour atteindre près de 30 milliards de dollars, soit environ 6% du PIB. Les Gardiens de la Révolution contrôlent également un vaste empire économique évalué à plus de 100 milliards de dollars, incluant des entreprises de construction, de télécommunications, de banque et d''import-export.', 3, 'economie-1', 3, 14, 1),
('Cependant, la population civile paie un lourd tribut à cette priorité budgétaire. La pauvreté touche désormais près de 40% des Iraniens (contre 20% en 2015). Les pénuries de médicaments sont chroniques, causant des milliers de décès évitables chaque année. La fuite des cerveaux s''est accélérée : plus de 300 000 jeunes diplômés (ingénieurs, médecins, informaticiens) quittent le pays annuellement, principalement vers la Turquie, le Canada et les pays européens. Cette saignée démographique menace à long terme la capacité d''innovation et de développement du pays.', 3, 'economie-2', 3, 14, 2);

-- Paragraphes pour "Les scénarios d'évolution du conflit"
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les experts militaires et géopolitiques envisagent trois scénarios principaux pour les mois à venir. Le scénario le plus probable (60% de chances) est celui d''une guerre d''usure à basse intensité, avec des frappes ponctuelles israéliennes sur les sites nucléaires et des représailles iraniennes limitées via drones et missiles, sans déclenchement d''une guerre ouverte généralisée. Ce scénario permettrait à chaque camp de sauver la face tout en évitant l''escalade incontrôlée.', 3, 'scenarios-1', 3, 15, 1),
('Le deuxième scénario (30% de chances) serait un conflit régional limité à quelques semaines, déclenché par une frappe israélienne majeure nécessitant une riposte iranienne massive, entraînant Israël, les États-Unis, l''Iran et ses proxies dans une guerre de haute intensité, mais circonscrite géographiquement au Levant et au Golfe. Ce scénario ferait probablement des milliers de morts et provoquerait un choc pétrolier historique, avec des prix du baril dépassant 200 dollars.', 3, 'scenarios-2', 3, 15, 2),
('Enfin, un scénario catastrophe (10% de chances) verrait l''utilisation d''armes de destruction massive (nucléaire ou chimique), l''implication directe de la Russie et de la Chine aux côtés de l''Iran, et un embrasement généralisé du Moyen-Orient. Ce scénario apocalyptique, que tous les acteurs s''accordent à vouloir éviter, serait dévastateur pour l''économie mondiale et la stabilité internationale, avec des conséquences humanitaires incalculables pour les populations civiles de la région.', 3, 'scenarios-3', 3, 15, 3);

-- ============================================
-- 9. CONTENU DE LA PAGE "conflit-israel-iran" (id_page = 4)
-- ============================================

-- Titre principal
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le Face-à-Face Israël-Iran : Chronique d''une Guerre Annoncée', 1, 'israel-iran', 4, NULL, 1);

-- Sous-titres
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('La guerre de l''ombre : 40 ans d''affrontements clandestins', 2, 'guerre-ombre', 4, 16, 1),
('Les capacités militaires comparées', 2, 'capacites-militaires', 4, 16, 2),
('Le rôle des cyberattaques dans le conflit', 2, 'cyber-attaques', 4, 16, 3),
('L''après-conflit : reconstruire une région dévastée', 2, 'apres-conflit', 4, 16, 4);

-- Paragraphes pour ces sous-titres
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Pendant près de quarante ans, Israël et l''Iran se sont livrés à une "guerre de l''ombre" faite d''assassinats ciblés, de sabotages d''installations nucléaires, de piratages informatiques et d''attaques contre des navires en mer Rouge et dans le Golfe d''Oman. Cette guerre invisible a connu son apogée avec l''assassinat du physicien nucléaire Mohsen Fakhrizadeh en novembre 2020, attribué au Mossad, et les explosions mystérieuses dans l''usine d''enrichissement de Natanz. Les services secrets iraniens ont répliqué par des tentatives d''enlèvement et d''assassinat de diplomates et d''anciens responsables israéliens à l''étranger, notamment en Turquie et en Europe.', 3, 'ombre-1', 4, 17, 1),
('Aujourd''hui, cette guerre occulte est devenue officielle, avec des frappes aériennes revendiquées et des déclarations martiales de part et d''autre. La rupture du "shadow war" marque un tournant historique au Moyen-Orient, car c''est la première fois que deux puissances militaires régionales majeures s''affrontent directement, sans l''écran de leurs proxys. Les conséquences de cette escalade sont encore difficiles à mesurer, mais les experts s''accordent sur un point : la région ne sera plus jamais la même.', 3, 'ombre-2', 4, 17, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Israël dispose de l''armée la plus technologiquement avancée du Moyen-Orient, avec une force aérienne moderne (F-35I, F-15, F-16), des systèmes de défense anti-missiles multicouches (Dôme de fer, Fronde de David, Flèche) et une capacité nucléaire non déclarée estimée à plusieurs centaines d''ogives. L''IDF (Israel Defense Forces) peut mobiliser plus de 500 000 soldats en 48 heures et dispose d''un budget annuel de 25 milliards de dollars.', 3, 'capacites-israel', 4, 18, 1),
('L''Iran mise quant à lui sur la quantité plutôt que la qualité, avec un million d''hommes sous les drapeaux (dont 150 000 dans les Gardiens de la Révolution), un arsenal massif de missiles balistiques et de croisière (plus de 3000 unités), et une doctrine militaire basée sur la saturation des défenses ennemies. Son talon d''Achille reste sa force aérienne, obsolète (F-14 Tomcat datant des années 1970, MiG-29 russes) et sa marine de surface, inexistante. L''Iran parie donc sur la guerre asymétrique, les drones et les frappes de missiles depuis son territoire et ceux de ses alliés régionaux.', 3, 'capacites-iran', 4, 18, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le cyberespace est devenu un champ de bataille à part entière entre les deux nations. En avril 2024, une cyberattaque massive attribuée à l''unité 8200 du renseignement israélien a paralysé pendant 72 heures les terminaux pétroliers de l''île de Kharg, bloquant 90% des exportations iraniennes et coûtant des milliards de dollars au régime. En représailles, les hackers iraniens ont défiguré des sites gouvernementaux israéliens, volé des données sensibles de l''armée, et tenté (avec un succès limité) de perturber le système de gestion de l''eau en Israël.', 3, 'cyber-1', 4, 19, 1),
('Cette guerre invisible a des conséquences concrètes : les rançongiciels se multiplient, les infrastructures critiques sont de plus en plus vulnérables, et les citoyens des deux pays paient le prix de cette escalade numérique. Les experts estiment que les coûts cumulés de la cyberguerre dépassent désormais les 10 milliards de dollars pour chaque camp, en pertes directes et en investissements défensifs. Aucune trêve n''est en vue dans cette cinquième dimension du conflit.', 3, 'cyber-2', 4, 19, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Quelle que soit l''issue du conflit actuel, les défis de la reconstruction seront colossaux. Les infrastructures pétrolières iraniennes sont gravement endommagées, avec des pertes estimées à plus de 200 milliards de dollars. Les villes iraniennes proches des sites militaires (Ispahan, Téhéran, Chiraz) ont subi des dégâts considérables, et des milliers d''immeubles résidentiels sont inhabitables. Côté israélien, les dégâts sont moindres grâce à l''efficacité de la défense antiaérienne, mais les conséquences psychologiques sur une population déjà éprouvée par des décennies de conflits sont importantes.', 3, 'reconstruction-1', 4, 20, 1),
('La communauté internationale devra se mobiliser pour financer la reconstruction et prévenir une nouvelle escalade. La Chine, intéressée par les contrats de reconstruction, a d''ores et déjà proposé un plan d''investissement de 50 milliards de dollars sur 5 ans, conditionné à des réformes politiques en Iran. L''Union européenne envisage un "Marshall Plan" pour le Moyen-Orient, incluant la normalisation des relations entre Israël et l''Iran en échange de la fin du programme nucléaire et du retrait des milices iraniennes de Syrie, du Liban et de l''Irak. L''avenir de la région dépendra de la volonté politique des protagonistes à saisir cette opportunité historique de paix.', 3, 'reconstruction-2', 4, 20, 2);

-- ============================================
-- 10. CONTENU DE LA PAGE "sanctions-economiques" (id_page = 5)
-- ============================================

-- Titre principal
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Sanctions contre l''Iran : Bilan et Perspectives', 1, 'sanctions-iran', 5, NULL, 1);

-- Sous-titres
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Quarante ans de sanctions : historique', 2, 'historique-sanctions', 5, 21, 1),
('Contournement et résilience iranienne', 2, 'contournement-sanctions', 5, 21, 2),
('Impact humanitaire et social', 2, 'impact-humanitaire', 5, 21, 3),
('Vers un nouvel ordre économique mondial', 2, 'nouvel-ordre-economique', 5, 21, 4);

-- Paragraphes
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le régime de sanctions contre l''Iran s''est progressivement renforcé depuis la révolution de 1979. Les premières sanctions américaines visaient les avoirs iraniens gelés et l''embargo pétrolier. Dans les années 2000, les sanctions se sont multilatéralisées avec l''UE et l''ONU pour contraindre l''Iran à suspendre son programme d''enrichissement d''uranium. L''apogée des sanctions a été atteint entre 2010 et 2015, avec des mesures dites "secondaires" menaçant de sanctionner toute entreprise étrangère commerçant avec l''Iran, isolant quasiment totalement le pays du système financier international (SWIFT).', 3, 'historique-1', 5, 22, 1),
('Le JCPOA de 2015 (accord de Vienne) avait permis une levée partielle des sanctions en échange de limitations du programme nucléaire. Mais le retrait américain unilatéral en 2018 sous la présidence Trump a rétabli et même durci les sanctions, avec une politique de "pression maximale" visant à réduire à zéro les exportations pétrolières iraniennes. L''échec des négociations de Vienne en 2022-2023 a enterré tout espoir de retour à l''accord, conduisant à la situation actuelle où l''Iran subit les sanctions les plus sévères de son histoire.', 3, 'historique-2', 5, 22, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Face à cet étau financier, l''Iran a développé un vaste système de contrebande et de contournement des sanctions. Les exportations pétrolières passent désormais par des flottes fantômes de pétroliers qui éteignent leurs transpondeurs, transfèrent leur cargaison en mer sur d''autres navires, ou falsifient leurs origines via des certificats d''origine d''autres pays (Malaisie, Oman, Émirats). Les paiements s''effectuent en cryptomonnaies, en or, ou via des chambres de compensation gérées par des banques chinoises et russes non connectées à SWIFT.', 3, 'contournement-1', 5, 23, 1),
('Le troc (pétrole contre biens) est également largement utilisé, notamment avec la Chine qui fournit à l''Iran des pièces détachées pour son industrie automobile, des équipements médicaux, et même des composants électroniques utilisés dans les drones et missiles. La Russie a également mis en place des circuits d''approvisionnement en armes et en technologie, contournant à la fois les sanctions contre l''Iran et celles qui la frappent elle-même depuis l''invasion de l''Ukraine. Ce "triangle de la contrebande" (Iran-Russie-Chine) fragilise l''efficacité des sanctions occidentales.', 3, 'contournement-2', 5, 23, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le coût humanitaire des sanctions est régulièrement dénoncé par les ONG et les agences de l''ONU. L''accès aux médicaments contre le cancer, aux insulines, aux vaccins et aux équipements hospitaliers est sévèrement restreint, malgré les dérogations théoriques pour les produits humanitaires. Les banques étrangères refusent souvent de traiter les transactions liées à l''Iran par peur de représailles américaines, ce qui retarde ou bloque les livraisons. L''espérance de vie en Iran a diminué de 3 ans depuis 2018, et la mortalité infantile a augmenté de 25%.', 3, 'humanitaire-1', 5, 24, 1),
('Les classes moyennes et populaires sont les premières victimes de l''effondrement du rial et de l''inflation galopante. De nombreux Iraniens ne peuvent plus acheter de viande, de fruits ou de produits laitiers. Les files d''attente devant les boulangeries et les distributeurs de pain subventionné sont devenues monnaie courante dans les grandes villes. Ce désespoir économique alimente les mouvements de protestation sociale, réprimés dans le sang par le régime, créant un cercle vicieux de violence et de répression qui fragilise encore davantage le pays.', 3, 'humanitaire-2', 5, 24, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les sanctions contre l''Iran s''inscrivent dans un basculement plus large de l''ordre économique mondial. L''essor des BRICS+ (Brésil, Russie, Inde, Chine, Afrique du Sud, rejoints par l''Iran, l''Arabie Saoudite, les Émirats, l''Égypte, l''Éthiopie et l''Argentine) offre des alternatives au dollar et aux institutions de Bretton Woods. L''Iran a ainsi signé des accords bilatéraux de swap de devises avec la Chine, l''Inde et la Russie, permettant des échanges commerciaux sans passer par le système financier occidental.', 3, 'ordre-mondial-1', 5, 25, 1),
('Cette fragmentation monétaire menace à terme l''hégémonie du dollar et l''efficacité des sanctions américaines. Si les BRICS+ parviennent à créer une monnaie commune de réserve, comme évoqué lors du sommet de Johannesburg en 2025, les sanctions occidentales perdraient une grande partie de leur mordant. Les experts estiment que ce basculement pourrait prendre une décennie, mais la guerre en Ukraine et le conflit Iran-Israël accélèrent ce processus historique de dédollarisation.', 3, 'ordre-mondial-2', 5, 25, 2);

-- ============================================
-- 11. CONTENU DE LA PAGE "acteurs-regionaux" (id_page = 6)
-- ============================================

-- Titre principal
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les Acteurs Régionaux du Conflit Iranien', 1, 'acteurs-regionaux', 6, NULL, 1);

-- Sous-titres
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le Hezbollah : la carte maîtresse de l''Iran', 2, 'hezbollah-iran', 6, 26, 1),
('Les Houthis du Yémen : une menace sur le commerce mondial', 2, 'houthis-yemen', 6, 26, 2),
('Les milices irakiennes : un arc chiite sous influence', 2, 'milices-irakiennes', 6, 26, 3),
('Les monarchies du Golfe : entre neutralité et méfiance', 2, 'monarchies-golfe', 6, 26, 4);

-- Paragraphes
INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Le Hezbollah ("Parti de Dieu") est sans conteste le proxy le plus puissant et le plus dangereux de l''Iran. Créé en 1982 avec l''aide des Gardiens de la Révolution, ce mouvement chiite libanais dispose aujourd''hui de 100 000 combattants aguerris, d''un arsenal estimé à 150 000 roquettes et missiles capables d''atteindre tout point du territoire israélien, et d''une branche politique représentée au parlement libanais. Son secrétaire général, Hassan Nasrallah, a récemment menacé de "transformer la vie en Israël en enfer" en cas d''attaque contre le Liban ou d''escalade majeure contre l''Iran.', 3, 'hezbollah-1', 6, 27, 1),
('L''implication du Hezbollah dans la guerre civile syrienne (aux côtés de Bachar al-Assad) lui a permis d''acquérir une expérience de combat urbain inestimable et de tester de nouvelles armes fournies par l''Iran, notamment des drones suicide Shahed et des missiles de croisière précis. Les services de renseignement occidentaux estiment que le Hezbollah possède désormais la capacité de frapper des cibles stratégiques avec une précision chirurgicale, y compris des centrales électriques, des usines chimiques et des bases aériennes israéliennes. Détruire le Hezbollah nécessiterait une guerre terrestre massive que ni Israël ni les États-Unis ne souhaitent engager.', 3, 'hezbollah-2', 6, 27, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Au Yémen, les Houthis (Ansar Allah) sont devenus un acteur clé de la stratégie iranienne de harcèlement du commerce mondial. Depuis 2019, ils attaquent régulièrement des pétroliers et des navires marchands en mer Rouge et dans le Golfe d''Aden, utilisant des drones, des missiles anti-navires, et même des mines flottantes. Ces attaques ont contraint les grandes compagnies maritimes (Maersk, MSC, CMA CGM) à suspendre le transit par le canal de Suez, empruntant la route beaucoup plus longue du Cap de Bonne-Espérance, avec des surcoûts de 30% sur les prix des marchandises.', 3, 'houthis-1', 6, 28, 1),
('Les Houthis contrôlent désormais la majeure partie du Yémen, y compris la capitale Sanaa, et ont résisté à huit années d''intervention militaire d''une coalition menée par l''Arabie Saoudite. Leur arsenal s''est considérablement amélioré grâce aux livraisons iraniennes, avec des missiles balistiques capables d''atteindre le territoire saoudien et émirati, et des drones longue portée (Waeed, Qasef) inspirés des modèles iraniens. La normalisation des relations entre l''Iran et l''Arabie Saoudite en 2025 sous médiation chinoise a réduit les tensions, mais les Houthis conservent leur capacité de nuisance et leur allégeance idéologique à Téhéran.', 3, 'houthis-2', 6, 28, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('L''Irak est devenu depuis 2003 un terrain d''influence majeur pour l''Iran, qui y a développé un réseau dense de partis politiques, de milices armées et d''entreprises économiques. Les Forces de mobilisation populaire (Hachd al-Chaabi), officiellement intégrées à l''armée irakienne, comptent plus de 150 000 hommes, pour la plupart chiites et pro-iraniens. Les principales milices (Kataeb Hezbollah, Asaib Ahl al-Haq, Badr Brigade) sont directement entraînées, équipées et financées par les Gardiens de la Révolution, et disposent d''arsenaux considérables de roquettes, de missiles et de drones.', 3, 'milices-irak-1', 6, 29, 1),
('L''influence iranienne en Irak est également économique et religieuse : des centaines d''entreprises iraniennes opèrent dans les secteurs de la construction, de l''énergie, de l''agroalimentaire et du commerce de détail. Les lieux saints chiites de Najaf et Kerbala attirent des millions de pèlerins iraniens chaque année, renforçant les liens culturels et religieux entre les deux pays. Cette mainmise iranienne inquiète les États-Unis, qui maintiennent encore 2500 soldats en Irak censés lutter contre l''État islamique mais qui servent aussi de "force de dissuasion" contre une expansion iranienne trop visible. Les attaques contre les bases américaines en Irak, attribuées aux milices pro-iraniennes, sont récurrentes et entretiennent une tension permanente.', 3, 'milices-irak-2', 6, 29, 2);

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('Les monarchies du Golfe (Arabie Saoudite, Émirats arabes unis, Qatar, Koweït, Oman, Bahreïn) ont longtemps été les rivales régionales de l''Iran, soutenues par les États-Unis. La guerre au Yémen, l''influence iranienne en Irak et au Liban, et les attaques contre les installations pétrolières saoudiennes en 2019 ont alimenté une méfiance profonde et durable. Cependant, la donne a changé à partir de 2025 avec les accords de Pékin : l''Arabie Saoudite et l''Iran ont rouvert leurs ambassades, rétabli leurs liaisons aériennes et signé des accords de coopération économique et sécuritaire.', 3, 'golfe-1', 6, 30, 1),
('Aujourd''hui, les monarchies du Golfe adoptent une position de neutralité pragmatique dans le conflit Iran-Israël. Elles ne souhaitent pas être entraînées dans une guerre dévastatrice qui menacerait leurs infrastructures pétrolières et leur stabilité intérieure. Le Qatar joue un rôle de médiateur, accueillant des pourparlers indirects entre Téhéran et Washington. Les Émirats ont normalisé leurs relations avec Israël (accords d''Abraham de 2020) mais maintiennent des canaux de communication ouverts avec l''Iran. L''Arabie Saoudite, nouvelle puissance régionale sous l''impulsion du prince héritier Mohammed ben Salmane, cherche à équilibrer ses relations avec les États-Unis, la Chine, l''Iran et Israël, dans une stratégie de "tous azimuts" qui maximise son influence et sa sécurité.', 3, 'golfe-2', 6, 30, 2);

-- ============================================
-- 12. ASSOCIATION IMAGES - CONTENU
-- ============================================

-- Images pour la page accueil
INSERT INTO image (id_contenu, path) VALUES
(1, 'images/iran-flag-map.jpg'),
(2, 'images/tehran-skyline.jpg'),
(4, 'images/oruz-strait.jpg'),
(5, 'images/iran-protest.jpg');

-- Images pour la page actualites
INSERT INTO image (id_contenu, path) VALUES
(6, 'images/israel-f35.jpg'),
(7, 'images/iran-missile-launch.jpg'),
(8, 'images/biden-netanyahu.jpg'),
(9, 'images/shahed-drone.jpg');

-- Images pour analyse-guerre-iran
INSERT INTO image (id_contenu, path) VALUES
(10, 'images/khamenei-speech.jpg'),
(11, 'images/iran-nuclear-facility.jpg'),
(12, 'images/putin-khamenei.jpg'),
(13, 'images/tehran-bazaar.jpg'),
(14, 'images/war-destruction.jpg');

-- Images pour conflit-israel-iran
INSERT INTO image (id_contenu, path) VALUES
(16, 'images/mossad-operation.jpg'),
(17, 'images/idf-soldiers.jpg'),
(18, 'images/cyber-warfare.jpg'),
(19, 'images/post-war-reconstruction.jpg');

-- Images pour sanctions-economiques
INSERT INTO image (id_contenu, path) VALUES
(21, 'images/oil-sanctions.jpg'),
(22, 'images/iran-black-market.jpg'),
(23, 'images/hospital-iran.jpg'),
(24, 'images/brics-summit.jpg');

-- Images pour acteurs-regionaux
INSERT INTO image (id_contenu, path) VALUES
(26, 'images/hezbollah-fighter.jpg'),
(27, 'images/houthi-attack.jpg'),
(28, 'images/iraq-militia.jpg'),
(29, 'images/saudi-crown-prince.jpg');

-- ============================================
-- 13. ASSOCIATION TAGS - CONTENU
-- ============================================

-- Tags pour la page accueil
INSERT INTO contenu_tag (id_contenu, id_tag) VALUES
(1, 1), (1, 2), (1, 3),   -- Observatoire du Conflit Iranien
(2, 1), (2, 16),          -- Dernières analyses
(3, 1), (3, 5), (3, 8),   -- Points chauds
(4, 1), (4, 7), (4, 8);   -- Indicateurs

-- Tags pour actualites
INSERT INTO contenu_tag (id_contenu, id_tag) VALUES
(6, 1), (6, 2), (6, 6),   -- Frappes israéliennes
(7, 1), (7, 2), (7, 14),  -- Réponse iranienne
(8, 1), (8, 3), (8, 12),  -- Réactions internationales
(9, 1), (9, 14), (9, 15); -- Analyse drones

-- Tags pour analyse-guerre-iran
INSERT INTO contenu_tag (id_contenu, id_tag) VALUES
(10, 1), (10, 3), (10, 5),   -- Analyse géopolitique
(11, 1), (11, 3), (11, 9),   -- Origines historiques
(12, 1), (12, 3), (12, 9),   -- Dimension nucléaire
(13, 1), (13, 3), (13, 10), (13, 11), (13, 12), -- Alliances
(14, 1), (14, 7), (14, 8),   -- Économie de guerre
(15, 1), (15, 2), (15, 5);   -- Scénarios

-- Tags pour conflit-israel-iran
INSERT INTO contenu_tag (id_contenu, id_tag) VALUES
(16, 1), (16, 6), (16, 2),   -- Face-à-face Israël-Iran
(17, 1), (17, 6), (17, 2),   -- Guerre de l'ombre
(18, 1), (18, 6), (18, 14),  -- Capacités militaires
(19, 1), (19, 6), (19, 13),  -- Cyberattaques
(20, 1), (20, 5), (20, 17);  -- Après-conflit

-- Tags pour sanctions-economiques
INSERT INTO contenu_tag (id_contenu, id_tag) VALUES
(21, 1), (21, 7), (21, 8),   -- Sanctions contre l'Iran
(22, 1), (22, 7), (22, 8),   -- Historique sanctions
(23, 1), (23, 7), (23, 8),   -- Contournement
(24, 1), (24, 7), (24, 17),  -- Impact humanitaire
(25, 1), (25, 7), (25, 11);  -- Nouvel ordre économique

-- Tags pour acteurs-regionaux
INSERT INTO contenu_tag (id_contenu, id_tag) VALUES
(26, 1), (26, 5), (26, 15),  -- Acteurs régionaux
(27, 1), (27, 5), (27, 15),  -- Hezbollah
(28, 1), (28, 5), (28, 15),  -- Houthis
(29, 1), (29, 5), (29, 15),  -- Milices irakiennes
(30, 1), (30, 5), (30, 15);  -- Monarchies du Golfe

-- ============================================
-- 14. CITATIONS (type 4) pour enrichir le contenu
-- ============================================

INSERT INTO contenu (texte, id_type, slug, id_page, id_parent, ordre) VALUES
('"Le peuple iranien ne reculera jamais face à l''intimidation. Nous sommes une nation de héros, et nous défendrons notre honneur et notre indépendance jusqu''à notre dernier souffle."', 4, 'citation-khamenei', 3, 10, 6),
('"Israël a le droit de se défendre par tous les moyens contre la menace nucléaire iranienne. Nous ne permettrons jamais à un régime qui appelle à notre destruction de disposer de l''arme absolue."', 4, 'citation-netanyahu', 4, 16, 5),
('"Les sanctions sont une forme de guerre économique. Elles tuent des innocents, des enfants, des patients dans les hôpitaux. C''est un crime contre l''humanité."', 4, 'citation-raisi', 5, 21, 5),
('"L''Iran est devenu une puissance de drones incontournable. Nos ennemis devraient réfléchir à deux fois avant de nous attaquer."', 4, 'citation-garde-revolution', 3, 10, 7);

-- ============================================
-- FIN DU SCRIPT
-- ============================================