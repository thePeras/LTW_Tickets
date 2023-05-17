document.querySelectorAll(".ticket-card").forEach((ticketCard) => {
    ticketCard.addEventListener("click", (e) => {
        const id = e.currentTarget.dataset.id;
        window.location.href = '/ticket?id=' + id;
    });
});

async function makeLabelsModal() {
    const body = document.querySelector("body");
    body.style.overflow = "hidden";

    const res = await fetch(`/api/labels`, { method: "GET" });

    if (res.status !== 200) {
        console.log(`failed to get data... with status ${res.status}`);
        return;
    }

    const resJson = await res.json();

    const modalElement = document.createElement("div");
    modalElement.classList.add("modal");

    const modalContentElement = document.createElement("div");
    modalContentElement.classList.add("modal-content");

    body.appendChild(modalElement);
    modalElement.appendChild(modalContentElement);

    //modalContentElement.classList.toggle("edit-user-modal");

    //TODO: get csrf token

    modalContentElement.innerHTML = `
        <h2>Search Label</h2>
        <input type="text" placeholder="Search label...">
        <div class="main-edit-content">
        
        </div>
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

async function makeUserAssignModal() {
    const body = document.querySelector("body");
    body.style.overflow = "hidden";

    const res = await fetch(`/api/clients`, { method: "GET" });

    const modalElement = document.createElement("div");
    modalElement.classList.add("modal");

    const modalContentElement = document.createElement("div");
    modalContentElement.classList.add("modal-content");

    modalElement.appendChild(modalContentElement);
    body.appendChild(modalElement);

    //TODO: get csrf token

    modalContentElement.innerHTML = `
        <h2>Assign User</h2>

        <div class="main-edit-content">
            

            <div class="modal-buttons">
                <input type="button" class="cancel-button" onclick="closeModal()" value="Cancel">
            </div>
        </div>
        `;

    const searchField = document.createElement("input");
    searchField.type = "text";
    searchField.placeholder = "Search user...";

    mainContent = modalContentElement.querySelector(".main-edit-content");
    mainContent.parentNode.insertBefore(searchField, mainContent);

    searchField.addEventListener("input", async (e) => {
        const search = searchField.value;

        //TODO: 

        const res = await fetch(`/api/clients?q=${search}`, { method: "GET" });

        if (res.status !== 200) {
            console.log(`failed to get data... with status ${res.status}`);
            return;
        }

        const resJson = await res.json();
    });

    modalElement.style.display = "block";
    modalElement.style.opacity = 0;
    modalElement.animate([
        { opacity: 0 },
        { opacity: 1, visbility: "visible" },
    ], { duration: 200, iterations: 1 }).onfinish = (event) => {
        modalElement.style.opacity = 1;
    }
}