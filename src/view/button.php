<?php
echo '<a href="./frontController.php?controller=' . $controller . '&action=' . $action;
if (isset($params)) echo '&' . $params;
echo '">
    <div class="flex gap-2">
        <span class="material-symbols-outlined">' . $logo . '</span>
        <p>' . $title . '</p>
    </div>
</a>';