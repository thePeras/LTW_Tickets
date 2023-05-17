function toggleEditableFields() {
    var profilePicture = document.getElementById('profilePicture');
    var displayNameInput = document.getElementById('displayName');
    var usernameInput = document.getElementById('username');
    var emailInput = document.getElementById('email');

    displayNameInput.readOnly = !displayNameInput.readOnly;
    usernameInput.readOnly = !usernameInput.readOnly;
    emailInput.readOnly = !emailInput.readOnly;
}

function handleEditClick() {
    toggleEditableFields();

    var profilePicture = document.getElementById('profilePicture');

    var editButton = document.getElementById('editButton');
    var saveButton = document.getElementById('saveButton');
    var cancelButton = document.getElementById('cancelButton');

    profilePicture.addEventListener('click', openFileExplorer);
    profilePicture.style.cursor = 'pointer';
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

function openFileExplorer() {
    document.getElementById('fileInput').click();
}

function handleFileSelect(event) {
    var file = event.target.files[0];

    var reader = new FileReader();
    reader.onload = (function(theFile) {
        return function(e) {
            var image = document.getElementById('profilePicture');
            image.src = e.target.result;
        };
    })(file);
    reader.readAsDataURL(file);
}
