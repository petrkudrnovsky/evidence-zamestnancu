function deleteEmployee(employeeId, redirectToUrl) {
    fetch(`/api/employees/${employeeId}`, { method: 'DELETE' })
        .then(response => {
            if (response.ok) {
                console.log('Employee was delete successfully');
                window.location.href = redirectToUrl;
            } else {
                console.error('Cannot delete employee');
            }
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    let deleteButton = document.getElementById('deleteButton');
    let modal = document.getElementById('deleteConfirmationModal');
    let confirmDelete = document.getElementById('confirmDelete');
    let cancelDelete = document.getElementById('cancelDelete');

    deleteButton.addEventListener('click', function(event) {
        event.preventDefault();
        modal.style.display = 'block';
    });

    confirmDelete.addEventListener('click', function() {
        let employeeId = deleteButton.getAttribute('data-employee-id')
        let redirectToUrl = deleteButton.getAttribute('data-employee-index-url');
        deleteEmployee(employeeId, redirectToUrl);
    });

    cancelDelete.addEventListener('click', function() {
        modal.style.display = 'none';
    });
});

class Dialog {
    constructor(title, message) {
        this.title = title;
        this.message = message;
        this.dialogElement = this.createDialogElement();
    }

    createDialogElement() {
        const dialog = document.createElement('div');
        dialog.classList.add('dialog');

        const titleElement = document.createElement('h2');
        titleElement.textContent = this.title;
        dialog.appendChild(titleElement);

        const messageElement = document.createElement('p');
        messageElement.textContent = this.message;
        dialog.appendChild(messageElement);

        const closeButton = document.createElement('button');
        closeButton.textContent = 'Zrušit';
        closeButton.onclick = () => this.hide();
        dialog.appendChild(closeButton);

        dialog.style.display = 'none';

        document.body.appendChild(dialog);

        return dialog;
    }

    show() {
        this.dialogElement.style.display = 'block';
    }

    hide() {
        this.dialogElement.style.display = 'none';
    }
}