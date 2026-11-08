document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    document.querySelector('[data-menu-toggle]')?.addEventListener('click', () => {
        sidebar?.classList.toggle('open');
    });

    document.querySelectorAll('[data-confirm]').forEach((button) => {
        button.addEventListener('click', (event) => {
            const message = button.dataset.confirm || 'Confirmer la suppression ?';
            const form = button.closest('form');
            if (!window.confirm(message)) {
                event.preventDefault();
                event.stopPropagation();
            } else if (form && button.type !== 'submit') {
                event.preventDefault();
                form.requestSubmit();
            }
        });
    });

    const search = document.querySelector('[data-filter-search]');
    const role = document.querySelector('[data-filter-role]');
    const table = document.querySelector('[data-filter-table]');
    const pagination = document.querySelector('[data-pagination]');
    const rows = table ? Array.from(table.querySelectorAll('tbody tr[data-search]')) : [];
    const emptyRow = table?.querySelector('[data-filter-empty]');
    const pageSize = 10;
    let page = 1;

    const updateTable = () => {
        if (!table) return;
        const query = (search?.value || '').trim().toLocaleLowerCase();
        const selectedRole = role?.value || '';
        const matches = rows.filter((row) => {
            const text = (row.dataset.search || '').toLocaleLowerCase();
            return text.includes(query) && (!selectedRole || row.dataset.role === selectedRole);
        });
        const pageCount = Math.max(1, Math.ceil(matches.length / pageSize));
        page = Math.min(page, pageCount);
        rows.forEach((row) => { row.hidden = true; });
        matches.slice((page - 1) * pageSize, page * pageSize).forEach((row) => { row.hidden = false; });
        if (emptyRow) emptyRow.hidden = matches.length > 0;

        const count = document.querySelector('[data-result-count]');
        if (count) count.textContent = `${matches.length} résultat${matches.length > 1 ? 's' : ''}`;
        if (!pagination) return;
        pagination.replaceChildren();
        if (matches.length <= pageSize) return;
        for (let number = 1; number <= pageCount; number += 1) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = String(number);
            button.setAttribute('aria-label', `Page ${number}`);
            if (number === page) button.setAttribute('aria-current', 'page');
            button.addEventListener('click', () => { page = number; updateTable(); });
            pagination.append(button);
        }
    };

    search?.addEventListener('input', () => { page = 1; updateTable(); });
    role?.addEventListener('change', () => { page = 1; updateTable(); });
    updateTable();

    document.querySelectorAll('[data-course-search]').forEach((input) => {
        input.addEventListener('input', () => {
            const query = input.value.trim().toLocaleLowerCase();
            document.querySelectorAll('[data-course-card]').forEach((card) => {
                card.hidden = !card.textContent.toLocaleLowerCase().includes(query);
            });
        });
    });
});
