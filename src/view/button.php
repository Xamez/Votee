<?php
echo '<a class="button flex justify-center bg-white border-lightPurple text-main hover:text-white border-2 p-2 rounded-3xl" href="./frontController.php?controller=' . $controller . '&action=' . $action;
if (isset($params)) echo '&' . $params;
echo '">
    <div class="flex gap-2 justify-center items-center">
        <span class="material-symbols-outlined">' . $logo . '</span>
        <p>' . $title . '</p>
    </div>
</a>';