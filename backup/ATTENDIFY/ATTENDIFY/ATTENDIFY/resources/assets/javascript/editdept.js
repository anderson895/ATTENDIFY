document.addEventListener('DOMContentLoaded', () => {
    // Get the close buttons
    const closeButtons = document.querySelectorAll('.close');
    
    // Add click event to each close button
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Get the parent form container and hide it
            const formDiv = button.closest('.formDiv--');
            if (formDiv) {
                formDiv.style.display = 'none';
            }
        });
    });

    // Handle the Add and Edit buttons to hide other forms
    document.querySelectorAll('.add, .edit').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.formDiv--').forEach(form => {
                form.style.display = 'none';
            });
        });
    });

    // Edit button functionality
    document.querySelectorAll('.edit').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            // Fetch data using AJAX or populate fields directly if data is available
            const row = document.getElementById(`rowdepthead${id}`);
            if (row) {
                document.getElementById('editId').value = id;
                document.getElementById('editFirstName').value = row.children[0].textContent;
                document.getElementById('editEmail').value = row.children[1].textContent;
                document.getElementById('editPhoneNumber').value = row.children[2].textContent;
                document.getElementById('editYearlevel').value = row.children[3].textContent;
                document.getElementById('editForm').style.display = 'block';
            }
        });
    });
});
