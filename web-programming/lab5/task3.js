const news = [
    { title: "Открылась новая библиотека", views: 120 },
    { title: "Итоги олимпиады по программированию", views: 340 },
    { title: "Расписание сессии обновлено", views: 980 },
    { title: "Встреча выпускников факультета", views: 75 },
];
const popularNews = news.filter(function (item) {
    return item.views > 200;
});
const newsStrings = popularNews.map(function (item) {
    return item.title + " (" + item.views + " просмотров)";
});
const list = document.getElementById("newsList");

for (const text of newsStrings) {
    const li = document.createElement("li");
    li.textContent = text;
    list.appendChild(li);
}