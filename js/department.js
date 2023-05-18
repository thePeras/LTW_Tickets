

function makeAddDepartmentModel(){
    const body = document.querySelector("body");
    body.style.overflow = "hidden";

    const modalElement = document.querySelector(".modal");
    if (modalElement === null) return;

    const modalContentElement = document.querySelector(".modal-content");
    modalContentElement.classList.toggle("add-new-department-modal");
    if (modalContentElement === null) return;

    //TODO: make members search bar

    modalContentElement.innerHTML = `
    <h1>Add new Department</h1>
    <form method="post">
        <input type="hidden" name="action" value="newDepartment">
        <input type="hidden" name="lastHref" value="${location.pathname}">


        <label for="name">
            <p>Name:</p>
        </label>
        <input type="text" name="name" required>
        <label for="description">
            <p>Description:</p>
        </label>
        <input type="hidden" name=members"> 
        <textarea type="text" rows="10" name="description" required></textarea> 
        <div class="modal-buttons">
            <input type="button" class="cancel-button" onclick="closeModal()" value="Cancel">
            <input type="submit" class="submit-button" value="Add">

        </div>
    </form>
    `;

    modalElement.style.display = "block";
    modalElement.style.opacity = 0;
    modalElement.animate([
        { opacity: 0 },
        { opacity: 1, visbility: "visible" },
    ], { duration: 200, iterations: 1 }).onfinish = (event) => {
        modalElement.style.opacity = 1;
    }
}