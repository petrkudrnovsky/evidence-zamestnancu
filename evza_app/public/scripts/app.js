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

    let employeeId = deleteButton.getAttribute('data-employee-id')
    let employeeName = deleteButton.getAttribute('data-employee-name');
    let redirectToUrl = deleteButton.getAttribute('data-employee-index-url');

    let modal = new ConfirmDialog('Pozor!', `Opravdu chcete smazat zaměstnance ${employeeName}?`, () => deleteEmployee(employeeId, redirectToUrl));

    deleteButton.addEventListener('click', function(event) {
        event.preventDefault();
        modal.show();
    });
});

class InfoDialog {
    constructor(title, message) {
        this.title = title;
        this.message = message;
        this.dialogElement = this.createDialogElement();
    }

    createDialogElement() {
        const dialog = document.createElement('div');
        dialog.classList.add('evza-dialog');

        const titleElement = document.createElement('h2');
        titleElement.textContent = this.title;
        dialog.appendChild(titleElement);

        const messageElement = document.createElement('p');
        messageElement.textContent = this.message;
        dialog.appendChild(messageElement);

        const closeButton = document.createElement('button');
        closeButton.textContent = 'OK';
        closeButton.classList.add('button');
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

class ConfirmDialog {
    constructor(title, message, onConfirm) {
        this.title = title;
        this.message = message;
        this.onConfirm = onConfirm;
        this.dialogElement = this.createDialogElement();
    }

    createDialogElement() {
        const dialog = document.createElement('div');
        dialog.classList.add('evza-dialog');

        const titleElement = document.createElement('h2');
        titleElement.textContent = this.title;
        dialog.appendChild(titleElement);

        const messageElement = document.createElement('p');
        messageElement.textContent = this.message;
        dialog.appendChild(messageElement);

        const confirmButton = document.createElement('button');
        confirmButton.textContent = 'Ano';
        confirmButton.classList.add('button');
        confirmButton.classList.add('mr-2');
        confirmButton.classList.add('mt-2');
        confirmButton.onclick = () => this.onConfirm();
        dialog.appendChild(confirmButton);

        const closeButton = document.createElement('button');
        closeButton.textContent = 'Ne';
        closeButton.classList.add('button');
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