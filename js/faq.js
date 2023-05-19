const currentSearchParams = new URLSearchParams((location.href.split("?")[1] ?? ''));
var offset = currentSearchParams.get("offset") ?? 0;
var end = false;
var fetchingFAQs = false;
var searchInput = '';

const limit = 10;

function isOnScreen(element) {
    const rect = element.getBoundingClientRect();
    return window.innerHeight > rect.top && rect.top >= 0;

}

const debounce = (func, delay) => {
    let timerId = null;
    return (...args) => {
        clearInterval(timerId);
        timerId = setTimeout(() => func(...args), delay);
    }
}

async function buildResults(result) {
    const faqContent = document.querySelector(".faq-content");

    const question = document.createElement("div");

    const userRes = await fetch(`/api/clients/${encodeURI(result["createdByUser"])}`, { method: "get" });

    if (userRes.status !== 200) {
        console.log(`Error while getting user building new FAQ entry with status ${userRes.status}`);
        return;
    }

    const userJson = await userRes.json();



    var contents = result["content"].split("\n");
    contents = contents.map((value, _) => "<p>" + value + "</p>");

    question.classList.add("faq-question");

    question.innerHTML = `
    <header>
        <h2>#${result["id"]} - ${result["title"]}</h2>
        <i class="ri-add-circle-line"></i>
    </header>
    <div class="content">
        ${contents}
        <div class="created-by">
            <p>By:</p>
            <img class="avatar" src="/assets/images/person.png" alt="user">
            <p class="display-name">${userJson["displayName"]}</p>
        </div>
    `;

    faqContent.appendChild(question);
}

async function searchNewParam(event) {
    searchInput = event.target.value
    if (searchInput.length < 3) return;
    offset = 0;
    end = false;

    fetchingFAQs = true;

    const res = await fetch(`/api/faqs?limit=10&offset=${offset}&q=${encodeURI(searchInput)}`,
        { method: "GET" });

    if (res.status !== 200) {
        console.log(`Something went wrong while fetching FAQ search query with status ${res.status}`)
        return;
    }

    const resJson = await res.json();

    const faqContent = document.querySelector(".faq-content");
    while (faqContent.firstChild) {
        faqContent.removeChild(faqContent.lastChild);
    }

    await Promise.all(resJson.map(buildResults));
    addResultClick();
    fetchingFAQs = false;

}

const searchDebounce = debounce(searchNewParam, 300);

const addResultClick = () => {
    const faqQuestions = document.querySelectorAll('.faq-question');
    console.log(faqQuestions);

    faqQuestions.forEach((faqQuestion) => {
        if(faqQuestion.hasAttribute("click-listener")){
            return;
        }
        faqQuestion.toggleAttribute("click-listener", true);
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
    })
};


async function getNewFaqs(ev) {
    if (ev.deltaY < 0) return;
    if (end) return;
    const element = document.querySelector(".faq-content")
    if (element === null) return;
    if (element.lastElementChild === null) return;
    if (isOnScreen(element.lastElementChild) && !fetchingFAQs) {
        fetchingFAQs = true;
        fetchingUsers = false;
        const res = await fetch(`/api/faqs?limit=10&offset=${offset + element.children.length}${searchInput !== '' ? "&q=" + encodeURI(searchInput) : ''}`,
            { method: "GET" });
        if (res.status !== 200) {
            console.log(`FAQ list request failed with status ${res.status}`);
        }
        const resJson = await res.json();
        console.log(resJson);
        if (resJson.length === 0) {
            end = true;
            return;
        }
        await Promise.all(resJson.map(buildResults));
        addResultClick();
        fetchingFAQs = false;
    }
}

document.addEventListener("DOMContentLoaded", addResultClick);

document.addEventListener("DOMContentLoaded", (ev) => {
    const searchInput = document.querySelector('.search-input');

    searchInput.addEventListener("input", searchDebounce);
});

document.addEventListener("scroll", getNewFaqs);
