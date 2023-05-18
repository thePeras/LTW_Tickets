
document.addEventListener("DOMContentLoaded", (ev) => {
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach((faqQuestion) => {
        faqQuestion.addEventListener('click', (e) => {
            // Clicking in the content do nothing
            if (e.target.classList.contains('content') || e.target.parentElement.classList.contains('content')) {
                return;
            }
    
            //ri-add-circle-line: closed status
            //ri-close-circle-line: open status
            faqQuestion.querySelector('i').classList.toggle('ri-add-circle-line');
            faqQuestion.querySelector('i').classList.toggle('ri-close-circle-line');
            faqQuestion.classList.toggle('active');
        });
    });
});

