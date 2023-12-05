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
    if(deleteButton === null) {
        return;
    }

    let employeeId = deleteButton.getAttribute('data-employee-id')
    let employeeName = deleteButton.getAttribute('data-employee-name');
    let redirectToUrl = deleteButton.getAttribute('data-employee-index-url');

    let modal = new ConfirmDialog('Pozor!', `Opravdu chcete smazat zaměstnance ${employeeName}?`, () => deleteEmployee(employeeId, redirectToUrl));

    deleteButton.addEventListener('click', function(event) {
        event.preventDefault();
        modal.show();
    });
});

document.addEventListener('DOMContentLoaded', () => {
    new DropdownMenu('menuToggle', 'headerMenu');
});

class DropdownMenu {
    constructor(toggleButtonId, menuId) {
        this.menuToggle = document.getElementById(toggleButtonId);
        this.menu = document.getElementById(menuId);

        if(this.menuToggle !== null) {
            this.menuToggle.addEventListener('click', () => {
                this.menu.classList.toggle('show-menu');
            });
        }
    }
}

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

function autocomplete(inputElement, apiUrl) {
    let timeoutId;

    inputElement.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            const searchTerm = inputElement.value.trim();
            if (searchTerm) {
                fetch(`${apiUrl}/${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        showSuggestions(inputElement, data);
                    });
            }
            else {
                removeSuggestions('.autocomplete-suggestions');
            }
        }, 200);
    });
}

function showSuggestions(inputElement, suggestions) {
    let oldSuggestions = document.querySelector('.autocomplete-suggestions');
    if (oldSuggestions) {
        oldSuggestions.remove();
    }

    let suggestionsDiv = document.createElement('div');
    suggestionsDiv.classList.add('autocomplete-suggestions');

    if (suggestions.length === 0) {
        let div = document.createElement('div');
        div.textContent = 'Nenalezeno...';
        suggestionsDiv.appendChild(div);
    }
    else {
        suggestions.forEach(suggestion => {
            let div = document.createElement('div');
            div.textContent = suggestion.first_name + ' ' + suggestion.second_name;
            div.onclick = function() {
                inputElement.value = suggestion.first_name + ' ' + suggestion.second_name;
                suggestionsDiv.remove();
            };
            suggestionsDiv.appendChild(div);
        });
    }

    inputElement.parentNode.insertBefore(suggestionsDiv, inputElement.nextSibling);
}

function removeSuggestions(className)
{
    let suggestionsDiv = document.querySelector(className);
    if (suggestionsDiv) {
        suggestionsDiv.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('employee-search');
    if(searchInput !== null) {
        autocomplete(searchInput, '/api/employees/search');
    }
});
