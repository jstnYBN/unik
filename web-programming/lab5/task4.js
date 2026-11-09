const loadBtn = document.getElementById("loadBtn");
const result = document.getElementById("result");
async function loadData() {
    try {
        const response = await fetch("https://jsonplaceholder.typicode.com/posts");
        const data = await response.json();

        result.textContent = "";

        for (const post of data) {
            const p = document.createElement("p");
            p.textContent = post.title;
            result.appendChild(p);
        }

    } catch (error) {
        result.textContent = "Не удалось загрузить данные";
    }
}
loadBtn.addEventListener("click", loadData);