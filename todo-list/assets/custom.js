jQuery(document).ready(function ($) {
    function renderList(todos) {
        const $list = $('#todo-list');
        $list.empty();

        todos.forEach((item, index) => {
            const doneClass = item.done ? 'done' : '';
            const checkedAttr = item.done ? 'checked' : '';

            const li = `
                <li data-index="${index}" class="${doneClass}">
                    <input type="checkbox" class="checkbox" ${checkedAttr} />
                    <div class="todo-content">
                        <p class="todo-text" title="Click edit to modify">${item.text}</p>
                    </div>
                    <div class="actions">
                        <button class="edit-todo">Edit</button>
                        <button class="delete-todo">Delete</button>
                    </div>
                </li>
            `;
            $list.append(li);
        });
    }

    // Add task
    $('#add-todo').on('click', function () {
        const text = $('#new-todo').val();
        if (!text.trim()) return;

        $.post(todo_ajax_obj.ajax_url, {
            action: 'todo_list_action',
            nonce: todo_ajax_obj.nonce,
            todo_action: 'add',
            text: text
        }, function (res) {
            if (res.success) {
                $('#new-todo').val('');
                renderList(res.data);
            }
        });
    });

    // Delete task
    $('#todo-list').on('click', '.delete-todo', function () {
        const index = $(this).closest('li').data('index');

        $.post(todo_ajax_obj.ajax_url, {
            action: 'todo_list_action',
            nonce: todo_ajax_obj.nonce,
            todo_action: 'delete',
            index: index
        }, function (res) {
            if (res.success) renderList(res.data);
        });
    });

    // Toggle done via checkbox
    $('#todo-list').on('change', '.checkbox', function () {
        const index = $(this).closest('li').data('index');

        $.post(todo_ajax_obj.ajax_url, {
            action: 'todo_list_action',
            nonce: todo_ajax_obj.nonce,
            todo_action: 'toggle',
            index: index
        }, function (res) {
            if (res.success) renderList(res.data);
        });
    });

    // Toggle Edit/Save
    $('#todo-list').on('click', '.edit-todo', function () {
        const $btn = $(this);
        const $li = $btn.closest('li');
        const index = $li.data('index');
        const $contentDiv = $li.find('.todo-content');

        if ($btn.text() === 'Edit') {
            const currentText = $contentDiv.find('.todo-text').text();
            const $textarea = $(`<textarea class="edit-textarea">${currentText}</textarea>`);
            $contentDiv.empty().append($textarea);
            $btn.text('Save');
            $textarea.focus();
        } else {
            const newText = $contentDiv.find('.edit-textarea').val().trim();
            if (!newText) return;

            $.post(todo_ajax_obj.ajax_url, {
                action: 'todo_list_action',
                nonce: todo_ajax_obj.nonce,
                todo_action: 'update',
                index: index,
                text: newText
            }, function (res) {
                if (res.success) renderList(res.data);
            });
        }
    });
});
