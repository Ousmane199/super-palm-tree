// Sélectionnez toutes les images miniatures
const thumbnails = document.querySelectorAll('.fixeimg2');



// Sélectionnez l'image principale
const mapImage = document.querySelector('.mapImage');

// Parcourez toutes les images miniatures et ajoutez un gestionnaire d'événements de clic
thumbnails.forEach(thumbnail => {
  thumbnail.addEventListener('click', function(e) {
    // Obtenez la source de l'image miniature cliquée
    // Mettez à jour la source de l'image principale avec la source de l'image miniature



    const h3= document.querySelector('h3')
    h3.innerHTML = "Etage "+e.target.alt[5]
    // Masquez l'image de l'étage 1 et affichez l'image de l'étage 2 si l'image miniature cliquée est de l'étage 2
    const premier = document.getElementById("etage11");
    const deuxieme = document.getElementById("etage22");

    if(e.target.id == 'e2')
    {
      premier.style.display = 'none';
      h3.id='etage2'
      deuxieme.style.display = 'block';

    } else
    {
      h3.id='etage1'
      premier.style.display = 'block';
      deuxieme.style.display = 'none';
    }
  });
});