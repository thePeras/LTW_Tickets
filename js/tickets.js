const currentSearchParams = new URLSearchParams((location.href.split("?")[1] ?? ''));
var offset = currentSearchParams.get("offset") ?? 0;
var tab = currentSearchParams.get("tab") ?? "unassigned"
var end = false;
var fetchingTickets = false;
const limit = 10;
var sortOrder = "";
var searchInput = "";

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

function drawNewTicketCard(jsonObject) {
    const element = document.querySelector(".ticket-list");
    const card = document.createElement("div");
    card.classList.add("ticket-card");
    card.onclick = () => { location.href = `/ticket?id=${jsonObject["id"]}` };
    //TODO: time ago JS side
    card.innerHTML += `
        <h3>#${jsonObject['id']} - ${jsonObject['title']}</h3>
        <h5>${jsonObject['timeAgo']}</h5>
        <p>${jsonObject['description']}</p>
        <footer>
            <div>  
                <span class="tag">${jsonObject['status']}</span>
                ${jsonObject['hashtags'].split(',').map(hashtag => `<span class="tag">${hashtag}</span>`).join('')}
            </div>
            
        </footer>
    `;

    element.appendChild(card);


}

const getMoreTicketsData = async (ev) => {
    if (ev.deltaY < 0) return;
    if (end) return;
    const element = document.querySelector(".ticket-list")
    if (element === null) return;
    if (element.lastElementChild === null) return;
    if (isOnScreen(element.lastElementChild) && !fetchingTickets) {
        fetchingTickets = true;
        console.log("fetching new ticket data...");
        const res = await fetch(`/api/tickets?limit=10&offset=${offset + element.children.length}${sortOrder !== '' ? `&sortOrder=${sortOrder}` : ''}&tab=${tab}${searchInput !== '' ? `&text=${searchInput}` : ''}`,
            { method: "GET" });
        if (res.status !== 200) {
            console.log(`Users list request failed with status ${res.status}`);
        }
        const resJson = await res.json();
        if (resJson.length === 0) {
            end = true;
            return;
        }
        resJson.forEach(drawNewTicketCard);
        fetchingTickets = false;

    }
};
document.addEventListener("scroll", getMoreTicketsData);


async function handleSortOptionChange(value) {
    sortOrder = value === 'lastCreated' ? 'DESC' : 'ASC';
    await fetchTickets();
}

async function searchNewParam(event) {
    if (event.target.value.length < 3 && event.target.value.length > 1
        && isNaN(event.target.value)) return;
    searchInput = event.target.value;
    offset = 0;
    end = false;
    await fetchTickets();

}

async function fetchTickets() {
    fetchingTickets = true;
    var res = undefined;
    if (searchInput.length === 0) {
        res = await fetch(`/api/tickets?limit=10&offset=0${sortOrder !== '' ? `&sortOrder=${sortOrder}` : ''}&tab=${tab}${searchInput !== '' ? `&text=${searchInput}` : ''}`,
            { method: "GET" });
    }

    if (res === undefined) {
        res = await fetch(`/api/tickets?limit=10&offset=${offset}${sortOrder !== '' ? `&sortOrder=${sortOrder}` : ''}&tab=${tab}${searchInput !== '' ? `&text=${searchInput}` : ''}`,
            { method: "GET" });
    }


    if (res.status !== 200) {
        console.log(`Something went wrong while fetching tickets search query with status ${res.status}`)
        return;
    }

    const resJson = await res.json();
    const ticketList = document.querySelector(".ticket-list");
    while (ticketList.firstChild) {
        ticketList.removeChild(ticketList.lastChild);
    }

    resJson.map(drawNewTicketCard);
    fetchingTickets = false;
}

const searchDebounce = debounce(searchNewParam, 300);

document.addEventListener("DOMContentLoaded", (ev) => {
    const search = document.querySelector("#search");
    search.addEventListener("input", searchDebounce);
});