<?php

// Add tab to LearnPress profile
add_action('plugins_loaded', function() {
    if (defined('LEARNPRESS_VERSION')) {
        add_filter('learn-press/profile-tabs', function($tabs) {
            $tabs['todo'] = [
                'title' => '<i class="fa-solid fa-list-ul"></i> ' . __('To-Do List', 'learnpress'),
                'callback' => 'render_todo_list_tab',
                'priority' => 50,
            ];
            return $tabs;
        });
    }
});


add_shortcode('todo_list','render_todo_list_tab');

function render_todo_list_tab() {
    $todos = get_user_meta(get_current_user_id(), '_todo_list', true);
    if (!is_array($todos)) $todos = [];
    ob_start();
    ?>
    <div class="todo-wrapper">
        <div class="add-task">
            <textarea id="new-todo" placeholder="Add new task"></textarea>
            <button id="add-todo" class='add-action'>Add</button>
        </div>
        <ul id="todo-list">
            <?php foreach ($todos as $index => $item): ?>
                <li data-index="<?= $index ?>" class="<?= $item['done'] ? 'done' : '' ?>">
                    <input type='checkbox' class='checkbox' <?= $item['done'] ? 'checked' : '' ?> />
                    <div class="todo-content">
                        <p class="todo-text" title='Click edit to modify'><?= $item['text'] ?></p>
                    </div>
                    <div class="actions">
                        <button class="edit-todo">Edit</button>
                        <button class="delete-todo">Delete</button>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}


// AJAX Handlers
add_action('wp_ajax_todo_list_action', function() {
    check_ajax_referer('todo_ajax_nonce', 'nonce');

    $action = $_POST['todo_action'];
    $text = sanitize_text_field($_POST['text'] ?? '');
    $index = intval($_POST['index'] ?? -1);

    $user_id = get_current_user_id();
    $todos = get_user_meta($user_id, '_todo_list', true);
    if (!is_array($todos)) $todos = [];

    switch ($action) {
        case 'add':
            $todos[] = ['text' => $text, 'done' => false];
            break;
        case 'delete':
            if (isset($todos[$index])) unset($todos[$index]);
            $todos = array_values($todos);
            break;
        case 'toggle':
            if (isset($todos[$index])) $todos[$index]['done'] = !$todos[$index]['done'];
            break;
        case 'update':
            if (isset($todos[$index])) $todos[$index]['text'] = $text;
            break;
    }

    update_user_meta($user_id, '_todo_list', $todos);
    wp_send_json_success($todos);
});
