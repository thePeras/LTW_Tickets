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
    var cancelButton = document.getElementById('cancelButton');

    editButton.style.display = 'none';
    saveButton.style.display = 'inline-block';
    cancelButton.style.display = 'inline-block';
}

function handleCancelClick(){
    window.location.reload();
}

function handleSaveClick() {
    var usernameInput = document.getElementById('username');
    var username = usernameInput.value;
    var usernameRegex = /^[a-zA-Z0-9]+$/;
    if (!usernameRegex.test(username)) {
        return;
    }

    toggleEditableFields();

    var editButton = document.getElementById('editButton');
    var saveButton = document.getElementById('saveButton');
    var cancelButton = document.getElementById('cancelButton');

    editButton.style.display = 'inline-block';
    saveButton.style.display = 'none';
    cancelButton.style.display = 'none';
}

document.getElementById('editButton').addEventListener('click', handleEditClick);
