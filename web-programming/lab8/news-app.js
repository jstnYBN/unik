const form = document.querySelector('#news-form');
const list = document.querySelector('#news-list');
const message = document.querySelector('#message');
const reload = document.querySelector('#reload');
const newsCount = document.querySelector('#news-count');
const search = document.querySelector('#search');
let news = [];
let busy = false;
let editingId = null;

function setBusy(value) {
    busy = value;
    document.querySelectorAll('button').forEach(button => {
        button.disabled = value;
    });
    form.querySelectorAll('input, textarea').forEach(field => {
        if (field !== search) field.disabled = value;
    });
    list.setAttribute('aria-busy', String(value));
}

function updateNewsCount() {
    if (!newsCount) return;
    const count = news.length;
    newsCount.textContent = count;
}

function getFilteredNews() {
    const query = search.value.trim().toLowerCase();
    if (!query) return news;
    return news.filter(item => item.title.toLowerCase().includes(query));
}

async function request(payload) {
    const options = payload === undefined ? {} : {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    };
    let response;
    try {
        response = await fetch('api-news.php', options);
    } catch {
        throw new Error('Нет связи с сервером. Перед повтором обновите список.');
    }
    let data;
    try {
        data = await response.json();
    } catch {
        throw new Error('Сервер вернул не JSON. Проверьте ответ во вкладке Network.');
    }
    if (!response.ok) {
        throw new Error(data.error || `Ошибка HTTP ${response.status}`);
    }
    return data;
}

async function run(task) {
    if (busy) return;
    setBusy(true);
    message.textContent = 'Выполняется запрос…';
    try {
        await task();
    } catch (error) {
        message.textContent = error.message;
    } finally {
        setBusy(false);
    }
}

function render() {
    list.replaceChildren();
    updateNewsCount();
    const filtered = getFilteredNews();
    if (filtered.length === 0) {
        const empty = document.createElement('li');
        empty.textContent = news.length === 0 ? 'Новостей пока нет.' : 'Ничего не найдено.';
        list.append(empty);
        return;
    }
    for (const item of filtered) {
        const li = document.createElement('li');
        
        if (editingId === item.id) {
            // Режим редактирования
            const titleInput = document.createElement('input');
            titleInput.type = 'text';
            titleInput.value = item.title;
            titleInput.maxLength = 255;
            titleInput.required = true;
            
            const contentTextarea = document.createElement('textarea');
            contentTextarea.value = item.content;
            contentTextarea.maxLength = 10000;
            contentTextarea.required = true;
            
            const saveButton = document.createElement('button');
            saveButton.type = 'button';
            saveButton.textContent = 'Сохранить';
            saveButton.disabled = busy;
            saveButton.addEventListener('click', () => {
                const newTitle = titleInput.value.trim();
                const newContent = contentTextarea.value.trim();
                
                if (!newTitle || !newContent) {
                    message.textContent = 'Заполните все поля.';
                    return;
                }
                
                run(async () => {
                    const data = await request({
                        action: 'update',
                        id: item.id,
                        title: newTitle,
                        content: newContent
                    });
                    news = news.map(row => 
                        String(row.id) === String(data.item.id) ? data.item : row
                    );
                    editingId = null;
                    render();
                    message.textContent = 'Новость обновлена.';
                });
            });
            
            const cancelButton = document.createElement('button');
            cancelButton.type = 'button';
            cancelButton.textContent = 'Отмена';
            cancelButton.disabled = busy;
            cancelButton.addEventListener('click', () => {
                editingId = null;
                render();
            });
            
            li.append(titleInput, document.createElement('br'), contentTextarea, document.createElement('br'), saveButton, cancelButton);
        } else {
            // Обычный режим просмотра
            const title = document.createElement('strong');
            const content = document.createElement('p');
            const editButton = document.createElement('button');
            const deleteButton = document.createElement('button');
            
            title.textContent = item.title;
            content.textContent = item.content;
            
            editButton.type = 'button';
            editButton.textContent = 'Редактировать';
            editButton.disabled = busy;
            editButton.addEventListener('click', () => {
                editingId = item.id;
                render();
            });
            
            deleteButton.type = 'button';
            deleteButton.textContent = 'Удалить';
            deleteButton.disabled = busy;
            deleteButton.addEventListener('click', () => {
                if (busy || !confirm('Удалить эту новость?')) return;
                run(async () => {
                    const data = await request({ action: 'delete', id: item.id });
                    news = news.filter(row => String(row.id) !== String(data.deleted));
                    render();
                    message.textContent = 'Новость удалена.';
                });
            });
            
            li.append(title, content, editButton, deleteButton);
        }
        
        list.append(li);
    }
}

function loadNews() {
    return run(async () => {
        const data = await request();
        news = data.news;
        render();
        message.textContent = 'Список загружен.';
    });
}

form.addEventListener('submit', event => {
    event.preventDefault();
    if (busy) return;
    const fields = new FormData(form);
    const payload = {
        action: 'create',
        title: fields.get('title'),
        content: fields.get('content')
    };
    run(async () => {
        const data = await request(payload);
        news.unshift(data.item);
        render();
        form.reset();
        message.textContent = 'Новость добавлена.';
    });
});

search.addEventListener('input', render);
reload.addEventListener('click', loadNews);
loadNews();