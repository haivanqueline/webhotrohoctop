let body = document.body;
let profile = document.querySelector('.header .flex .profile');
let sidebar = document.querySelector('.side-bar');
let searchform = document.querySelector('.header .flex .search-form');
let togglebt = document.querySelector('#toggle-bt');
let darkmode = localStorage.getItem('dark-mode');
document.querySelector('#user-bt').onclick = () => {
    profile.classList.toggle('active');
    searchform.classList.remove('active');
};
document.querySelector('#search-bt').onclick = () => {
    searchform.classList.toggle('active');
    profile.classList.remove('active');
};

document.querySelector('#menu-bt').onclick = () => {
    sidebar.classList.toggle('active');
    body.classList.toggle('active');
};
document.querySelector('#close-bar').onclick = () => {
    sidebar.classList.remove('active');
};
window.onscroll = () => {
    profile.classList.remove('active');
    searchform.classList.remove('active');
    if (window.innerWidth < 1200) {
        sidebar.classList.remove('active');
        body.classList.remove('active');
    }
};
const enabledarkmode = () => {
    togglebt.classList.replace('fa-sun', 'fa-moon');
    body.classList.add('dark');
    localStorage.setItem('dark-mode', 'enabled');
};
const disabledarkmode = () => {
    togglebt.classList.replace('fa-moon', 'fa-sun');
    body.classList.remove('dark');
    localStorage.setItem('dark-mode', 'disabled');
};

togglebt.onclick = (e) => {
    let darkmode = localStorage.getItem('dark-mode');
    if (darkmode === 'disabled') {
        enabledarkmode();
    } else {
        disabledarkmode();
    }
}
if (darkmode === 'enabled') {
    enabledarkmode();
}
document.querySelectorAll('input[type="number"]').forEach(inputnumber => {
    inputnumber.oninput = () => {
        if (inputnumber.value.length > inputnumber.maxLength) {
            inputnumber.value = inputnumber.value.slice(0, inputnumber.maxLength);
        }
    };
});




const chatinput = document.querySelector(".chat-input textarea");
const sendchatbt = document.querySelector(".chat-input span");
const chatbox = document.querySelector(".chatbox");
const yolostudytoggle = document.querySelector(".yolostudy-toggle");
const yolostudyclosebt = document.querySelector(".close-bt");
const createchatli = (message, classname) => {
    const chatli = document.createElement("li");
    chatli.classList.add("chat", classname);
    let chatcontent = classname === "outgoing" ? `<p></p>` : `<span><i class="fas fa-robot"></i></span><p></p>`;
    chatli.innerHTML = chatcontent;
    chatli.querySelector("p").textContent = message;
    return chatli;
}

let usermessage;
const API_KEY = "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";
const inputinitheight = chatinput.scrollHeight;
let isRequestPending = false;

const generateresponse = (incomingchatli) => {
    const API_URL = "https://api.openai.com/v1/chat/completions";
    const messageelement = incomingchatli.querySelector("p");
    const requestoptions = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${API_KEY}`
        },
        body: JSON.stringify({
            model: "gpt-3.5-turbo",
            messages: [
                { role: "user", content: usermessage }
            ]
        })
    };

    if (isRequestPending) {
        messageelement.textContent = "Lỗi rồi bạn ơi! Đợi một chút";
        return;
    }

    isRequestPending = true;

    fetch(API_URL, requestoptions)
        .then(res => res.json())
        .then(data => {
            messageelement.textContent = data.choices[0].message.content;
            isRequestPending = false;
        }).catch((error) => {
            messageelement.classList.add("error");
            messageelement.textContent = "Lỗi rồi bạn ơi! Đợi một chút";
            isRequestPending = false;
        }).finally(() => chatbox.scrollTo(0, chatbox.scrollHeight));
};

const handlechat = () => {
    usermessage = chatinput.value.trim();
    if (!usermessage) return;
    chatinput.value = "";
    chatinput.style.height = `${inputinitheight}px`;

    chatbox.appendChild(createchatli(usermessage, "outgoing"));
    chatbox.scrollTo(0, chatbox.scrollHeight);

    setTimeout(() => {
        const incomingchatli = createchatli("Đang suy nghĩ...", "...");
        chatbox.appendChild(incomingchatli);
        chatbox.scrollTo(0, chatbox.scrollHeight);
        generateresponse(incomingchatli);
    }, 1000);
};
chatinput.addEventListener("input", () => {
    chatinput.style.height = `${inputinitheight}px`;
    chatinput.style.height = `${chatinput.scrollHeight}px`;
});
chatinput.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handlechat();
    }
});
sendchatbt.addEventListener("click", handlechat);
yolostudytoggle.addEventListener("click", () => document.body.classList.toggle("show-yolostudy"));
yolostudyclosebt.addEventListener("click", () => document.body.classList.remove("show-yolostudy"));