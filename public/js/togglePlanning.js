let toggler = document.getElementsByClassName("tableDayPlanning");
let i;

/* Boucle sur chaque element / card */
for (i = 0; i < toggler.length; i++) {

    /* pour chaque élement on place un écouteur au click */
    toggler[i].addEventListener("click", function() {

      /* on cible le dernier enfant de notre div que l'on veut caché*/
      let divElement= this.lastElementChild;
      console.log(divElement);

      /* bascule entre une class hidden ou inversement */
      if ( divElement.classList == "tbodyPlanning") {
        divElement.classList.remove("tbodyPlanning");
        divElement.classList.add("tbodyPlanningHidden");
      }
      else {
        divElement.classList.remove("tbodyPlanningHidden");
        divElement.classList.add("tbodyPlanning");
      }
      
  });
}