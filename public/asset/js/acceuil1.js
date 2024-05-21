const bureauInfo = document.getElementById('bureauInfo');
  // Sélectionnez la grande image principale
  const h3= document.querySelector('h3')



  // Sélectionnez l'élément <svg> correspondant à l'image principale
  const Etage1 = document.getElementById("etage11")

  // Ajoutez un écouteur d'événements de clic à chaque zone cliquable pour l'Etage 1
  Etage1.addEventListener('click', function(event) {
    // Vérifiez si l'élément cliqué est une zone cliquable (un élément path)
    console.log("click")
    if (event.target.tagName === 'path') {
      const bureauId = event.target.getAttribute('id');
      console.log(bureauId)

      fetch(`/get_bureau_details/${bureauId}`)
              .then(response => response.json())
              .then(data => {
                // Afficher les détails du bureau dans le DOM
                bureauInfo.style.backgroundColor = 'aqua';
                bureauInfo.innerHTML = `
                    <dl>
                        <dt>Nom du Bureau</dt>
                        <dd>${data.bureau.nomBureau}</dd>
                        <dt>Nombre de Places</dt>
                        <dd>${data.bureau.nombrePlace}</dd>
                    </dl>
                    <h4>Personnes liées à ce bureau :</h4>
                    <ol id="personList">

                        ${data.personnes.map(personne => `
                            <li data-person-id="${personne.id}">
                                <h5> <em>${personne.nom} ${personne.prenom}</em>  </h5>
                                <div id="personDetails_${personne.id}" style="display: none;">
                                    <dl>
                                        <dt>Statut</dt>
                                        <dd>${personne.statut}</dd>
                                        <dt>Date d'arrivée</dt>
                                        <dd>${new Date(personne.date_arrive).toLocaleDateString()}</dd>
                                        <dt>Date de départ</dt>
                                        <dd>${personne.date_depart ? new Date(personne.date_depart).toLocaleDateString() : 'Non spécifiée'}</dd>
                                        <dt>Téléphone</dt>
                                        <dd>${personne.telephone}</dd>
                                    </dl>
                                </div>
                            </li>
                        `).join('')}
                    </ol>
                `;

                // Ajouter un écouteur d'événements à chaque personne de la liste
                document.querySelectorAll('#personList li').forEach(person => {
                  person.addEventListener('click', function() {
                    const personId = this.getAttribute('data-person-id');
                    const personDetails = document.querySelector(`#personDetails_${personId}`);

                    // Afficher ou masquer les détails de la personne
                    if (personDetails.style.display === 'none') {
                      personDetails.style.display = 'block';
                    } else {
                      personDetails.style.display = 'none';
                    }
                  });
                });
              })
              .catch(error => console.error('Erreur lors de la récupération des détails du bureau:', error));
    }
  });


  // Sélectionnez le map correspondant à l'image principale
  const Etage2 = document.getElementById("etage22");
  console.log("click")
  // Ajoutez un écouteur d'événements de clic à chaque zone cliquable pour l'Etage 2
  Etage2.addEventListener('click', function(event) {
    // Vérifiez si l'élément cliqué est une zone cliquable
    if (event.target.tagName === 'path') {
      const bureauId = event.target.getAttribute('id');
      console.log(bureauId)

      fetch(`/get_bureau_details/${bureauId}`)
              .then(response => response.json())
              .then(data => {
                // Afficher les détails du bureau dans le DOM
                bureauInfo.style.backgroundColor = 'aqua';
                bureauInfo.innerHTML = `
                    <dl>
                        <dt>Nom du Bureau</dt>
                        <dd>${data.bureau.nomBureau}</dd>
                        <dt>Nombre de Places</dt>
                        <dd>${data.bureau.nombrePlace}</dd>
                    </dl>
                    <h4>Personnes liées à ce bureau :</h4>
                    <ol id="personList">

                        ${data.personnes.map(personne => `
                            <li data-person-id="${personne.id}">
                                <h5> <em>${personne.nom} ${personne.prenom}</em>  </h5>
                                <div id="personDetails_${personne.id}" style="display: none;">
                                    <dl>
                                        <dt>Statut</dt>
                                        <dd>${personne.statut}</dd>
                                        <dt>Date d'arrivée</dt>
                                        <dd>${new Date(personne.date_arrive).toLocaleDateString()}</dd>
                                        <dt>Date de départ</dt>
                                        <dd>${personne.date_depart ? new Date(personne.date_depart).toLocaleDateString() : 'Non spécifiée'}</dd>
                                        <dt>Téléphone</dt>
                                        <dd>${personne.telephone}</dd>
                                    </dl>
                                </div>
                            </li>
                             
                        `).join('')}
                    </ol>
                `;

                // Ajouter un écouteur d'événements à chaque personne de la liste
                document.querySelectorAll('#personList li').forEach(person => {
                  person.addEventListener('click', function() {
                    const personId = this.getAttribute('data-person-id');
                    const personDetails = document.querySelector(`#personDetails_${personId}`);

                    // Afficher ou masquer les détails de la personne
                    if (personDetails.style.display === 'none') {
                      personDetails.style.display = 'block';
                    } else {
                      personDetails.style.display = 'none';
                    }
                  });
                });
              })
              .catch(error => console.error('Erreur lors de la récupération des détails du bureau:', error));
    }


  });