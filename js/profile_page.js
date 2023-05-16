function toggleEditableFields() {
    var displayNameInput = document.getElementById('displayName');
    var usernameInput = document.getElementById('username');
    var emailInput = document.getElementById('email');

    displayNameInput.readOnly = !displayNameInput.readOnly;
    usernameInput.readOnly = !usernameInput.readOnly;
    emailInput.readOnly = !emailInput.readOnly;
}

function handleEditClick() {
    toggleEditableFields();

    var editButton = document.getElementById('editButton');
    var saveButton = document.getElementById('saveButton');

    editButton.style.display = 'none';
    saveButton.style.display = 'inline-block';
}

document.getElementById('editButton').addEventListener('click', handleEditClick);