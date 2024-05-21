// Sélectionnez le bouton de soumission
const submitButton = document.getElementById('submitButton');

// Ajoutez un écouteur d'événements pour le clic sur le bouton de soumission
submitButton.addEventListener('click', function(event) {
  event.preventDefault(); // Empêchez le formulaire de se soumettre normalement

  // Effectuez la recherche avec la valeur actuelle de la barre de recherche
  performSearch();
});

// Fonction pour effectuer la recherche
function performSearch() {
  const query = searchInput.value.trim();

  // Vérifiez si la requête de recherche est vide
  if (query === '') {
    // Effacez les résultats précédents s'il y en a
    clearSearchResults();
    return;
  }

  // Effectuer une requête AJAX pour récupérer les résultats de recherche
  fetch(`/search_personnes?query=${query}`)
          .then(response => response.json())
          .then(data => {
            // Afficher les résultats de recherche dans le DOM
            displaySearchResults(data);
          })
          .catch(error => console.error('Erreur lors de la recherche de personnes:', error));
}

// Sélectionnez l'élément de recherche
const searchInput = document.getElementById('searchInput');

// Ajoutez un écouteur d'événements pour la saisie de texte
searchInput.addEventListener('input', function() {
  const query = searchInput.value.trim();

  // Vérifiez si la requête de recherche est vide
  if (query === '') {
    // Effacez les résultats précédents s'il y en a
    clearSearchResults();
    return;
  }

  // Effectuer une requête AJAX pour récupérer les résultats de recherche
  fetch(`/search_personnes?query=${query}`)
          .then(response => response.json())
          .then(data => {
            // Afficher les résultats de recherche dans le DOM
            displaySearchResults(data);
          })
          .catch(error => console.error('Erreur lors de la recherche de personnes:', error));
});

// Fonction pour effacer les résultats de recherche précédents
function clearSearchResults() {
  const searchResultsContainer = document.getElementById('bureauInfo');
  searchResultsContainer.innerHTML = '';
  bureauInfo.style.backgroundColor = 'honeydew'

}

// Fonction pour afficher les résultats de recherche dans le DOM
// Fonction pour afficher les résultats de recherche dans le DOM
function displaySearchResults(results) {
  const searchResultsContainer = document.getElementById('bureauInfo');
  searchResultsContainer.innerHTML = '';


  // Construire la liste des résultats de recherche
  const ul = document.createElement('ul');
  results.forEach(result => {
    const li = document.createElement('li');
    li.style.listStyleType = "none";
    searchResultsContainer.style.backgroundColor="aqua"
    li.textContent = `${result.nom} ${result.prenom}`;

    // Ajouter un écouteur d'événements pour afficher les détails de la personne lors du clic sur son nom
    li.addEventListener('click', function() {
      // Effectuer une requête AJAX pour récupérer les détails de la personne
      fetch(`/get_person_details/${result.id}`)
              .then(response => response.json())
              .then(personData => {
                // Afficher les détails de la personne dans le DOM
                displayPersonDetails(personData);
              })
              .catch(error => console.error('Erreur lors de la récupération des détails de la personne :', error));
    });

    ul.appendChild(li);
  });
  searchResultsContainer.appendChild(ul);
}

// Fonction pour afficher les détails de la personne dans le DOM
function displayPersonDetails(personData) {
  // Créer un élément de liste de définitions pour afficher les détails de la personne
  const personDetailsList = document.createElement('dl');

  // Ajouter le nom de la personne à la liste de définitions
  const dtNom = document.createElement('dt');
  dtNom.textContent = 'Nom';
  const ddNom = document.createElement('dd');
  ddNom.textContent = personData.nom;
  personDetailsList.appendChild(dtNom);
  personDetailsList.appendChild(ddNom);

  // Ajouter le prénom de la personne à la liste de définitions
  const dtPrenom = document.createElement('dt');
  dtPrenom.textContent = 'Prénom';
  const ddPrenom = document.createElement('dd');
  ddPrenom.textContent = personData.prenom;
  personDetailsList.appendChild(dtPrenom);
  personDetailsList.appendChild(ddPrenom);

  // Ajouter le nom du bureau à la liste de définitions
  const dtBureau = document.createElement('dt');
  dtBureau.textContent = 'Bureau';
  const ddBureau = document.createElement('dd');
  ddBureau.textContent = personData.nombureau;
  personDetailsList.appendChild(dtBureau);
  personDetailsList.appendChild(ddBureau);

  // Ajouter les autres détails de la personne à la liste de définitions
  const dtStatut = document.createElement('dt');
  dtStatut.textContent = 'Statut';
  const ddStatut = document.createElement('dd');
  ddStatut.textContent = personData.statut;
  personDetailsList.appendChild(dtStatut);
  personDetailsList.appendChild(ddStatut);

  const dtDateArrivee = document.createElement('dt');
  dtDateArrivee.textContent = 'Date d\'arrivée';
  const ddDateArrivee = document.createElement('dd');
  ddDateArrivee.textContent = new Date(personData.date_arrive).toLocaleDateString();
  personDetailsList.appendChild(dtDateArrivee);
  personDetailsList.appendChild(ddDateArrivee);

  const dtDateDepart = document.createElement('dt');
  dtDateDepart.textContent = 'Date de départ';
  const ddDateDepart = document.createElement('dd');
  ddDateDepart.textContent = personData.date_depart ? new Date(personData.date_depart).toLocaleDateString() : 'Non spécifiée';
  personDetailsList.appendChild(dtDateDepart);
  personDetailsList.appendChild(ddDateDepart);

  const dtTelephone = document.createElement('dt');
  dtTelephone.textContent = 'Téléphone';
  const ddTelephone = document.createElement('dd');
  ddTelephone.textContent = personData.telephone;
  personDetailsList.appendChild(dtTelephone);
  personDetailsList.appendChild(ddTelephone);

  // Effacer les résultats de recherche précédents et afficher les détails de la personne
  const searchResultsContainer = document.getElementById('bureauInfo');
  bureauInfo.style.backgroundColor = 'aqua'
  searchResultsContainer.innerHTML = '';
  searchResultsContainer.appendChild(personDetailsList);
}

