document.addEventListener('DOMContentLoaded', function() {
    let createForm = document.getElementById('accountCreateForm');
    if(createForm !== null) {
        createForm.addEventListener('submit', function(event) {
            event.preventDefault();

            let originalFormData = new FormData(this);
            let modifiedFormData = {};

            modifiedFormData['employee_id'] = this.getAttribute('data-employee-id');

            for (const [key, value] of originalFormData.entries()) {
                let match = key.match(/^account\[(.+)]$/);
                if(match) {
                    if(match[1] === 'expiration' && value === '') {
                        modifiedFormData[match[1]] = null;
                    } else {
                        modifiedFormData[match[1]] = value;
                    }
                }
                else {
                    modifiedFormData[key] = value;
                }
            }

            fetch('http://localhost:8080/api/accounts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(modifiedFormData)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('success: ', data);
                })
                .catch((error) => {
                    console.log('error', error);
                })
        })
    }
})