const cards = document.querySelectorAll('.eq-item');
const tray = document.querySelector('.compare-tray');
const countEl = document.getElementById('compareCount');
const btn = document.getElementById('compareBtn');
const input = document.getElementById('compareItems');

const selected = new Set();

cards.forEach(card => {
    card.addEventListener('click', () => {
        const id = card.dataset.id;
        const box = card.querySelector('.compare-card');

        if (selected.has(id)) {
            selected.delete(id);
            box.classList.remove('selected');
        } else {
            selected.add(id);
            box.classList.add('selected');
        }

        countEl.textContent = selected.size;
        input.value = [...selected].join(',');
        btn.disabled = selected.size < 2;
        tray.classList.toggle('show', selected.size > 0);
    });
});

btn.addEventListener('click', () => {
    document.getElementById('compareForm').submit();
});

/* FILTER */
const search = document.getElementById('searchInput');
const cat = document.getElementById('categoryFilter');
const status = document.getElementById('statusFilter');

function filter() {
    cards.forEach(i => {
        const ok =
            i.dataset.name.includes(search.value.toLowerCase()) &&
            (!cat.value || i.dataset.category === cat.value) &&
            (!status.value || i.dataset.status === status.value);
        i.style.display = ok ? 'block' : 'none';
    });
}

[search, cat, status].forEach(el => el.addEventListener('input', filter));
