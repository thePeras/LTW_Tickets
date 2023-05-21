
const currentSearchParams = new URLSearchParams((location.href.split("?")[1] ?? ''));
var offset = currentSearchParams.get("offset") ?? 0;
var end = false;
var fetchingDepartments = false;
const limit = 10;

function makeAddDepartmentModal() {
    const body = document.querySelector("body");
    body.style.overflow = "hidden";

    const modalElement = document.querySelector(".modal");
    if (modalElement === null) return;

    const modalContentElement = document.querySelector(".modal-content");
    modalContentElement.classList.toggle("add-new-department-modal");
    if (modalContentElement === null) return;

    //TODO: make members search bar

    modalContentElement.innerHTML = `
    <h1>Add new department</h1>
    <form method="post">
        <input type="hidden" name="action" value="newDepartment">
        <input type="hidden" name="lastHref" value="${location.href}">


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
            <input type="button" class="button cancel" onclick="closeModal()" value="Cancel">
            <input type="submit" class="button primary" value="Create">
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


function makeDeleteModal(department) {
    const body = document.querySelector("body");
    body.style.overflow = "hidden";

    const modalElement = document.querySelector(".modal");
    if (modalElement === null) return;

    const modalContentElement = document.querySelector(".modal-content");
    modalContentElement.classList.toggle("delete-user-modal");
    if (modalContentElement === null) return;

    //TODO: inject CSRF token
    modalContentElement.innerHTML = `
    <h1>Delete department</h1>
    <p>Are you sure that you want to delete department <b>${department}</b>? <br/> This action is irreversible!</p>
    <div class="modal-buttons">
        <button class="button cancel" onclick="closeModal()"><p>Cancel</p></button>
        <form method="post" action="admin">
            <input type="hidden" name="action" value="deleteDepartment">
            <input type="hidden" name="name" value="${department}">
            <input type="hidden" name="lastHref" value="${location.href}">


            <input type="submit" class="button delete" value="Delete">
        </form>
        </div>`;
    modalElement.style.display = "block";
    modalElement.style.opacity = 0;
    modalElement.animate([
        { opacity: 0 },
        { opacity: 1, visbility: "visible" },
    ], { duration: 200, iterations: 1 }).onfinish = (event) => {
        modalElement.style.opacity = 1;
    }
}


async function makeEditModal(department) {

    const res = await fetch(`/api/departments/${department}`, { method: "GET" });

    if (res.status !== 200) {
        console.log(`failed to get ${department} data... with status ${res.status}`);
        return;
    }

    const resJson = await res.json();

    const body = document.querySelector("body");
    body.style.overflow = "hidden";

    const modalElement = document.querySelector(".modal");
    if (modalElement === null) return;

    const modalContentElement = document.querySelector(".modal-content");
    modalContentElement.classList.toggle("add-new-department-modal");
    if (modalContentElement === null) return;

    //TODO: make members search bar

    modalContentElement.innerHTML = `
    <h1>Edit department</h1>
    <form method="post">
        <input type="hidden" name="action" value="editDepartment">
        <input type="hidden" name="lastHref" value="${location.pathname}">


        <label for="name">
            <p>Name:</p>
        </label>
        <input type="text" name="name" required value=${resJson["name"]}>
        <label for="description">
            <p>Description:</p>
        </label>
        <input type="hidden" name=members"> 
        <textarea type="text" rows="10" name="description" required>${resJson["description"]}</textarea> 
        <div class="modal-buttons">
            <input type="button" class="button cancel" onclick="closeModal()" value="Cancel">
            <input type="submit" class="button primary" value="Save">

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

function isOnScreen(element) {
    const rect = element.getBoundingClientRect();
    return window.innerHeight > rect.top && rect.top >= 0;

}

function drawNewRow(jsonObject) {
    const element = document.querySelector(".department-tbody");
    const tr = document.createElement("tr");

    tr.classList.add("department-entry");

    tr.innerHTML = `
    <td>
    <p class="department-name">${jsonObject["name"]}</p>
    </td>
    <td>
        <p class="department-description">${jsonObject["description"]}</p>
    </td>
    <td>
        <p class="department-members">${jsonObject["clients"].length} Members</p>
    </td>
    <td>
        <i class="ri-edit-line icon" onclick="makeEditModal('${jsonObject["name"]} ')"></i>
    </td>
    <td>
        <i class="ri-delete-bin-line icon delete" onclick="makeDeleteModal('${jsonObject["name"]}')")></i>
    </td>
    `;

    element.appendChild(tr);
}


const getNewTableData = async (ev) => {
    if (ev.deltaY < 0) return;
    if (end) return;
    const element = document.querySelector(".department-tbody")
    if (element === null) return;
    if (element.lastElementChild === null) return;
    if (isOnScreen(element.lastElementChild) && !fetchingDepartments) {
        fetchingDepartments = true;
        console.log("fetching new user data...");
        //fetch
        const sortBy = currentSearchParams.get("sort");
        const res = await fetch(`/api/departments?limit=10&offset=${offset + element.children.length}${sortBy !== null ? "&sort=" + sortBy : ''}`,
            { method: "GET" });

        if (res.status !== 200) {
            console.log(`Department list request failed with status ${res.status}`);
        }
        const resJson = await res.json();
        console.log(resJson);
        if (resJson.length === 0) {
            end = true;
            return;
        }
        //draw
        resJson.forEach(drawNewRow);
        fetchingDepartments = false;

    }
};
document.addEventListener("scroll", getNewTableData);
