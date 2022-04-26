let toggler = document.getElementsByClassName("divSingleCard");
let i;

/* Boucle sur chaque element / card */
for (i = 0; i < toggler.length; i++) {

    /* pour chaque élement on place un écouteur au click */
    toggler[i].addEventListener("click", function() {

      /* on cible le dernier enfant de notre div que l'on veut caché*/
      let divElement= this.lastElementChild;

      /* bascule entre une class hidden ou inversement */
      if ( divElement.classList == "divListDocumentationsHidden") {
        divElement.classList.remove("divListDocumentationsHidden");
        divElement.classList.add("divListDocumentations");
      }
      else {
        divElement.classList.remove("divListDocumentations");
        divElement.classList.add("divListDocumentationsHidden");
      }
      
  });
}

