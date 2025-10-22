# Nom du projet

Description : Application web hébergée sur AwardSpace pour la gestion des transactions et des comptes clients.

---

## URL de l'application

- Environnement de production : `http://etransactionnelle.atwebpages.com/`

---

## Accès démo (COMPTES FACTICES, PERMISSIONS)

> Ces comptes sont **démo**

| Courrier            | Mot de passe |
| ------------------- | ------------ |
| `maisel@andrea.com` | `5678`       |
| `peter@pan.com`     | `2345`       |
| `lulu@radio.com`    | `3456`       |

**Remarque** : Les autres informations concernant les transactions sont disponibles dans la documentation du projet.

---

## Comment se connecter

1. Ouvrez l'URL de l'application : `http://etransactionnelle.atwebpages.com/`.
2. Cliquez sur **Se connecter**.
3. Entrez l'un des comptes démo du tableau ci‑dessus.
4. Après connexion, testez les fonctionnalités en utilisant les informations personnelles du client pour une transaction (documentation du projet).

---

## Scénarios de test suggérés pour le détective

1. Accédez à la section **Relevés** depuis la page de connexion et vérifiez le solde disponible sur le compte bancaire du client.
2. Dans la section **« Relevés » → « Client Relevé de commandes »**, vérifiez l’ID de la dernière expédition (dernière transaction) pour s’assurer qu’après une nouvelle transaction, l’ID change correctement.
3. Dans la section **« Relevés » → « Produits en stock »**, vérifiez la quantité du produit **ID 7, Cisco UCS C220 M6** pour voir si elle diminue après une transaction.
   _Note : ce produit est codé en dur dans le code._
4. Retournez dans la section **Relevés** une fois la transaction terminée dans la section **Expédition** pour vérifier que toutes les valeurs ont été mises à jour et confirmer le succès de la transaction.
