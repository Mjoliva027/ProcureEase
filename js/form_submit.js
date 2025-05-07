// form_submit.js
document.addEventListener('DOMContentLoaded', () => {
    const supplierForm = document.getElementById('supplierForm');
    const governmentForm = document.getElementById('governmentForm');

    if (supplierForm) {
        supplierForm.addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm('supplier');
        });
    }

    if (governmentForm) {
        governmentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            submitForm('government');
        });
    }
});

function submitForm(role) {
    let formData;
    let url = '';

    if (role === 'supplier') {
        formData = new FormData(document.getElementById('supplierForm'));
        url = 'submit_supplier.php';
    } else if (role === 'government') {
        formData = new FormData(document.getElementById('governmentForm'));
        url = 'submit_government.php';
    }

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            alert('Form submitted successfully!');
            window.location.href = role === 'supplier' ? 'supplier_dashboard.php' : 'government_dashboard.php';
        } else {
            alert('Error: ' + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong!');
    });
}

