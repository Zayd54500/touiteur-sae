document.addEventListener("DOMContentLoaded", () => {
  const textarea = document.getElementById("contenu");
  const compteur = document.getElementById("compteur-caracteres");

  if (!textarea || !compteur) return;

  function actualiserCompteur() {
    const longueur = [...textarea.value].length;

    compteur.textContent = `${longueur} / 235`;

    compteur.classList.remove("text-secondary", "text-warning", "text-danger");

    if (longueur >= 235) {
      compteur.classList.add("text-danger");
    } else if (longueur >= 200) {
      compteur.classList.add("text-warning");
    } else {
      compteur.classList.add("text-secondary");
    }
  }

  textarea.addEventListener("input", actualiserCompteur);
  actualiserCompteur();
});
