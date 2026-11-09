let count = 0;
const button = document.getElementById("counterBtn");
const counterText = document.getElementById("counter");
button.addEventListener("click", function () {
    count = count + 1;
    counterText.textContent = "Нажатий: " + count;
});