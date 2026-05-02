import './bootstrap';

/**
 * Gère le comportement du formulaire de filtrage
 * Supprime les champs vides du formulaire avant soumission
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filter-form');

    if (!form) return;

    form.addEventListener('submit', function () {
        this.querySelectorAll('input, select').forEach(field => {
            // Si le champ est vide, supprime son attribut name
            if (!field.value) {
                field.removeAttribute('name');
            }
        });
    });
});
