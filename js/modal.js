function closeModal() {
    const body = document.querySelector("body");
    body.style.overflow = "auto";
    const modalElement = document.querySelector(".modal");
    if (modalElement === null) return;
    modalElement.animate([
        { opacity: 1 },
        { opacity: 0, visbility: "hidden" },
    ], { duration: 200, iterations: 1 }).onfinish = (event) => {
        modalElement.style.display = "none";
        const modalContentElement = document.querySelector(".modal-content");
        modalContentElement.classList = ["modal-content"] //reset classes
        if (modalContentElement === null) return;
        modalContentElement.innerHTML = '';
    };

}
