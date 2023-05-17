function closeModal() {
    const body = document.querySelector("body");
    body.style.overflow = "auto";
    const modalElement = document.querySelector(".modal");
    if (modalElement === null) return;
    modalElement.animate([
        { opacity: 1 },
        { opacity: 0, visbility: "hidden" },
    ], { duration: 200, iterations: 1 }).onfinish = (event) => {
        modalElement.remove();
    };

}
