<?php
echo '<a class="flex bg-white shadow-around p-2 rounded-2xl" href="./frontController.php?controller=' . $controller . '&action=' . $action;
if (isset($params)) echo '&' . $params;
echo '">
    <div class="flex gap-2 justify-center items-center">
        <span class="material-symbols-outlined">' . $logo . '</span>
        <p>' . $title . '</p>
    </div>
</a>';