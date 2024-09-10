<?php
$messageForm = $this->session->userdata("message_form");

if ($messageForm): ?>
    <div class="alert alert-<?= $messageForm['class'] ?>">
        <?= $messageForm['message'] ?>
    </div>

    <?php
    $this->session->unset_userdata("message_form");
endif; ?>