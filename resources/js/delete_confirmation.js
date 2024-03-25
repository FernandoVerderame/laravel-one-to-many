const deleteForms = document.querySelectorAll('.delete-form');
const modal = document.getElementById('modal');
const modalTitle = document.querySelector('.modal-title');
const modalBody = document.querySelector('.modal-body');
const confirmationButton = document.getElementById('modal-confirmation-button');

let activeForm = null;

deleteForms.forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();

        activeForm = form;

        const projectTitle = form.dataset.project;

        // Insert contents
        confirmationButton.innerText = 'Delete Confirmation';
        confirmationButton.className = 'btn btn-danger';
        modalTitle.innerText = 'Delete project';
        modalBody.innerText = `Are you sure to delete ${projectTitle}?`;
    })
})

confirmationButton.addEventListener('click', () => {
    if (activeForm) activeForm.submit();
});

modal.addEventListener('hidden.bs.modal', () => {
    activeForm = null;
})